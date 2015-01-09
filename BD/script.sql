SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `fc_product` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(45) NULL DEFAULT NULL,
  `providerPrice` DOUBLE NOT NULL DEFAULT 0.0,
  `userPrice` DOUBLE NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_provider` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `phoneNumber` BIGINT(20) NOT NULL DEFAULT 00000000,
  `address` VARCHAR(250) NOT NULL DEFAULT 'Ciudad',
  `nit` VARCHAR(45) NOT NULL DEFAULT 'C/F',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_client` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `nit` VARCHAR(45) NOT NULL,
  `address` VARCHAR(250) NOT NULL,
  `phoneNumber` BIGINT(20) NOT NULL DEFAULT 00000000,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_sale` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `registerDate` DATETIME NOT NULL,
  `billNumber` BIGINT(20) NOT NULL,
  `id_user` INT(11) NOT NULL,
  `fc_stock_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `id_user`, `fc_stock_id`),
  INDEX `fk_fc_sale_fc_stock_idx` (`fc_stock_id` ASC),
  INDEX `fk_fc_sale_fc_acl_user1_idx` (`id_user` ASC),
  CONSTRAINT `fk_fc_sale_fc_stock`
    FOREIGN KEY (`fc_stock_id`)
    REFERENCES `fc_stock` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fc_sale_fc_acl_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `fc_acl_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_role_user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `registerDate` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_action` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_module` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `path` VARCHAR(100) NOT NULL,
  `idParent` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_fc_module_fc_module1_idx` (`idParent` ASC),
  CONSTRAINT `fk_fc_module_fc_module1`
    FOREIGN KEY (`idParent`)
    REFERENCES `fc_module` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_permision` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_action` INT(11) NOT NULL,
  `id_module` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `id_action`, `id_module`),
  INDEX `fk_fc_permision_fc_action1_idx` (`id_action` ASC),
  INDEX `fk_fc_permision_fc_module1_idx` (`id_module` ASC),
  CONSTRAINT `fk_fc_permision_fc_action1`
    FOREIGN KEY (`id_action`)
    REFERENCES `fc_action` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fc_permision_fc_module1`
    FOREIGN KEY (`id_module`)
    REFERENCES `fc_module` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_role_permision` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_role` INT(11) NOT NULL,
  `id_permision` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `id_role`, `id_permision`),
  INDEX `fk_fc_role_permision_fc_role_user1_idx` (`id_role` ASC),
  INDEX `fk_fc_role_permision_fc_permision1_idx` (`id_permision` ASC),
  CONSTRAINT `fk_fc_role_permision_fc_role_user1`
    FOREIGN KEY (`id_role`)
    REFERENCES `fc_role_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fc_role_permision_fc_permision1`
    FOREIGN KEY (`id_permision`)
    REFERENCES `fc_permision` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_acl_user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `userName` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `registerDate` VARCHAR(45) NOT NULL,
  `id_role` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `id_role`),
  INDEX `fk_fc_acl_user_fc_role_user1_idx` (`id_role` ASC),
  CONSTRAINT `fk_fc_acl_user_fc_role_user1`
    FOREIGN KEY (`id_role`)
    REFERENCES `fc_role_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_session` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(45) NOT NULL,
  `dateInit` DATETIME NOT NULL,
  `dateExpiration` DATETIME NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `id_acl_user` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `id_acl_user`),
  INDEX `fk_fc_session_fc_acl_user1_idx` (`id_acl_user` ASC),
  CONSTRAINT `fk_fc_session_fc_acl_user1`
    FOREIGN KEY (`id_acl_user`)
    REFERENCES `fc_acl_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_product_purchase` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `registerDate` VARCHAR(45) NOT NULL,
  `billNumber` VARCHAR(150) NOT NULL,
  `totalPurchase` VARCHAR(45) NULL DEFAULT NULL,
  `id_provider` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `id_provider`),
  INDEX `fk_fc_product_purchase_fc_provider1_idx` (`id_provider` ASC),
  CONSTRAINT `fk_fc_product_purchase_fc_provider1`
    FOREIGN KEY (`id_provider`)
    REFERENCES `fc_provider` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;

CREATE TABLE IF NOT EXISTS `fc_stock` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `totalStock` BIGINT(20) NOT NULL DEFAULT 0,
  `minStock` BIGINT(20) NOT NULL DEFAULT 0,
  `id_provider` INT(11) NOT NULL,
  `id_product` INT(11) NOT NULL,
  `id_purchase` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `id_provider`, `id_product`, `id_purchase`),
  INDEX `fk_fc_stock_fc_provider1_idx` (`id_provider` ASC),
  INDEX `fk_fc_stock_fc_product1_idx` (`id_product` ASC),
  INDEX `fk_fc_stock_fc_product_purchase1_idx` (`id_purchase` ASC),
  CONSTRAINT `fk_fc_stock_fc_provider1`
    FOREIGN KEY (`id_provider`)
    REFERENCES `fc_provider` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fc_stock_fc_product1`
    FOREIGN KEY (`id_product`)
    REFERENCES `fc_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fc_stock_fc_product_purchase1`
    FOREIGN KEY (`id_purchase`)
    REFERENCES `fc_product_purchase` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `fc_bill` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `billNumber` BIGINT(20) NOT NULL DEFAULT 0,
  `registerDate` DATETIME NOT NULL,
  `totalProduct` BIGINT(20) NOT NULL DEFAULT 0,
  `idProduct` INT(11) NOT NULL,
  `priceUnit` BIGINT(20) NOT NULL DEFAULT 0,
  `totalPrice` BIGINT(20) NOT NULL DEFAULT 0,
  `id_sale` INT(11) NOT NULL,
  `id_client` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `id_sale`, `id_client`),
  INDEX `fk_fc_bill_fc_sale1_idx` (`id_sale` ASC),
  INDEX `fk_fc_bill_fc_client1_idx` (`id_client` ASC),
  CONSTRAINT `fk_fc_bill_fc_sale1`
    FOREIGN KEY (`id_sale`)
    REFERENCES `fc_sale` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fc_bill_fc_client1`
    FOREIGN KEY (`id_client`)
    REFERENCES `fc_client` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_spanish2_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
