-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-01-2015 a las 22:51:02
-- Versión del servidor: 5.5.40-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `control_invetario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_acl_user`
--

CREATE TABLE IF NOT EXISTS `fc_acl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `registerDate` datetime NOT NULL,
  `id_role` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_role`),
  KEY `fk_fc_acl_user_fc_role_user1_idx` (`id_role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `fc_acl_user`
--

INSERT INTO `fc_acl_user` (`id`, `userName`, `password`, `status`, `registerDate`, `id_role`) VALUES
(1, 'juancho', '123456', 1, '2015-01-10 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_action`
--

CREATE TABLE IF NOT EXISTS `fc_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_bill`
--

CREATE TABLE IF NOT EXISTS `fc_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `billNumber` bigint(20) NOT NULL DEFAULT '0',
  `registerDate` datetime NOT NULL,
  `totalProduct` bigint(20) NOT NULL DEFAULT '0',
  `idProduct` int(11) NOT NULL,
  `priceUnit` bigint(20) NOT NULL DEFAULT '0',
  `totalPrice` bigint(20) NOT NULL DEFAULT '0',
  `id_sale` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_sale`,`id_client`),
  KEY `fk_fc_bill_fc_sale1_idx` (`id_sale`),
  KEY `fk_fc_bill_fc_client1_idx` (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_client`
--

CREATE TABLE IF NOT EXISTS `fc_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `lastName` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `nit` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `address` varchar(250) COLLATE utf8_spanish2_ci NOT NULL,
  `phoneNumber` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_module`
--

CREATE TABLE IF NOT EXISTS `fc_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `path` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `idParent` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fc_module_fc_module1_idx` (`idParent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_permision`
--

CREATE TABLE IF NOT EXISTS `fc_permision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_action` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_action`,`id_module`),
  KEY `fk_fc_permision_fc_action1_idx` (`id_action`),
  KEY `fk_fc_permision_fc_module1_idx` (`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_product`
--

CREATE TABLE IF NOT EXISTS `fc_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `description` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `providerPrice` double NOT NULL DEFAULT '0',
  `userPrice` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=34 ;

--
-- Volcado de datos para la tabla `fc_product`
--

INSERT INTO `fc_product` (`id`, `name`, `description`, `providerPrice`, `userPrice`) VALUES
(24, 'más'';', 'sadasd', 2.333, 5.33),
(32, 'sada', 'qweqwe', 2.2, 555.2),
(33, 'sdfs', 'sdfsdf', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_product_buy`
--

CREATE TABLE IF NOT EXISTS `fc_product_buy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registerDate` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `billNumber` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `totalPurchase` varchar(45) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_provider`
--

CREATE TABLE IF NOT EXISTS `fc_provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `phoneNumber` bigint(20) NOT NULL DEFAULT '0',
  `address` varchar(250) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'Ciudad',
  `nit` varchar(45) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'C/F',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `fc_provider`
--

INSERT INTO `fc_provider` (`id`, `name`, `phoneNumber`, `address`, `nit`) VALUES
(1, 'aaaaaa', 12345678, '13 avenida b 25-58 zona 13', '3978904-7'),
(5, 'ñijm', 12345678, 'fghjklñ', '45678ij'),
(6, 'asd', 87654321, 'asdasd', 'asd'),
(7, 'osom', 12345678, 'fghsz8', '990i0asdasd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_role_permision`
--

CREATE TABLE IF NOT EXISTS `fc_role_permision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_role` int(11) NOT NULL,
  `id_permision` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_role`,`id_permision`),
  KEY `fk_fc_role_permision_fc_role_user1_idx` (`id_role`),
  KEY `fk_fc_role_permision_fc_permision1_idx` (`id_permision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_role_user`
--

CREATE TABLE IF NOT EXISTS `fc_role_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `registerDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `fc_role_user`
--

INSERT INTO `fc_role_user` (`id`, `name`, `registerDate`) VALUES
(1, 'administrador', '2015-01-09 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_sale`
--

CREATE TABLE IF NOT EXISTS `fc_sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registerDate` datetime NOT NULL,
  `billNumber` bigint(20) NOT NULL,
  `id_user` int(11) NOT NULL,
  `fc_stock_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_user`,`fc_stock_id`),
  KEY `fk_fc_sale_fc_stock_idx` (`fc_stock_id`),
  KEY `fk_fc_sale_fc_acl_user1_idx` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_session`
--

CREATE TABLE IF NOT EXISTS `fc_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  `dateInit` datetime NOT NULL,
  `dateExpiration` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `id_acl_user` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_acl_user`),
  KEY `fk_fc_session_fc_acl_user1_idx` (`id_acl_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fc_stock`
--

CREATE TABLE IF NOT EXISTS `fc_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `totalStock` bigint(20) NOT NULL DEFAULT '0',
  `minStock` bigint(20) NOT NULL DEFAULT '0',
  `id_provider` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_provider`,`id_product`),
  KEY `fk_fc_stock_fc_provider1_idx` (`id_provider`),
  KEY `fk_fc_stock_fc_product1_idx` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `fc_acl_user`
--
ALTER TABLE `fc_acl_user`
  ADD CONSTRAINT `fk_fc_acl_user_fc_role_user1` FOREIGN KEY (`id_role`) REFERENCES `fc_role_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fc_bill`
--
ALTER TABLE `fc_bill`
  ADD CONSTRAINT `fk_fc_bill_fc_client1` FOREIGN KEY (`id_client`) REFERENCES `fc_client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_fc_bill_fc_sale1` FOREIGN KEY (`id_sale`) REFERENCES `fc_sale` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fc_module`
--
ALTER TABLE `fc_module`
  ADD CONSTRAINT `fk_fc_module_fc_module1` FOREIGN KEY (`idParent`) REFERENCES `fc_module` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fc_permision`
--
ALTER TABLE `fc_permision`
  ADD CONSTRAINT `fk_fc_permision_fc_action1` FOREIGN KEY (`id_action`) REFERENCES `fc_action` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_fc_permision_fc_module1` FOREIGN KEY (`id_module`) REFERENCES `fc_module` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fc_role_permision`
--
ALTER TABLE `fc_role_permision`
  ADD CONSTRAINT `fk_fc_role_permision_fc_permision1` FOREIGN KEY (`id_permision`) REFERENCES `fc_permision` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_fc_role_permision_fc_role_user1` FOREIGN KEY (`id_role`) REFERENCES `fc_role_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fc_sale`
--
ALTER TABLE `fc_sale`
  ADD CONSTRAINT `fk_fc_sale_fc_acl_user1` FOREIGN KEY (`id_user`) REFERENCES `fc_acl_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_fc_sale_fc_stock` FOREIGN KEY (`fc_stock_id`) REFERENCES `fc_stock` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fc_session`
--
ALTER TABLE `fc_session`
  ADD CONSTRAINT `fk_fc_session_fc_acl_user1` FOREIGN KEY (`id_acl_user`) REFERENCES `fc_acl_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fc_stock`
--
ALTER TABLE `fc_stock`
  ADD CONSTRAINT `fk_fc_stock_fc_product1` FOREIGN KEY (`id_product`) REFERENCES `fc_product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_fc_stock_fc_provider1` FOREIGN KEY (`id_provider`) REFERENCES `fc_provider` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
