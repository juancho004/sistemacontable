<?php 
/**
 * Class master .
 *
 * @author Jcbarreno <jcbarreno.b@gmail.com>
 * @version 1.0
 * @package 
 */
class ModelMaster {

	protected $prefix;
	protected $app;

	public function  __construct($app, $prefix) {
		$this->prefix = $prefix;
		$this->app 	= $app;
	}

	public function _debug()
	{
		$table 		= "{$this->prefix}acl_user";
		$query 		= 'SELECT * FROM '.$table;
		$user  		= $this->app['dbs']['mysql_silex']->fetchAssoc($query);
		_pre($user);
		exit;

	}

	public function validateSession($user,$password)
	{
		$response 	= new stdClass();
		#$password 	= md5($password);	
		$table 		= "{$this->prefix}acl_user";
		try{
			$query 		= 'SELECT id FROM '.$table.' WHERE userName = "'.$user.'" AND password = "'.$password.'" ';
			$user  		= $this->app['dbs']['mysql_silex']->fetchAssoc($query);
	
			if( !empty($user['id']) ):
				#inicia sesión
				@session_name("login_usuario");
				@session_start();

				#registrar inicio de sesion
				$_SESSION["authenticated_user"]	= true; #asignar que el usuario se autentico
				$_SESSION["lastaccess_user"]	= date("Y-n-j H:i:s"); #definir la fecha y hora de inicio de sesión en formato aaaa-mm-dd hh:mm:ss

				$response->status = true;
				$response->message 	= "Ok";
				return $response;

			endif;

			$response->status = false;
			$response->message 	= "El usuario o contraseña no son validos.";
			return $response;

		}catch(Exception $e){
			$response->status = false;
			$response->message 	= "Ocurrio un error";
			return $response;
		}
	}

public function validateSessionActive()
	{


		#inicia sesión
		@session_name("login_usuario");
		@session_start();
		$response 			= new stdClass();
		$response->redirect = FALSE;

		#validar que el usuario esta logueado
		if ( !(@$_SESSION["authenticated_user"]) ) {

			#el usuario NO inicio sesion
			$response->redirect = FALSE;

		} else {
			#el usuario inicio sesion
			$fechaGuardada 			= $_SESSION["lastaccess_user"];
			$ahora 					= date("Y-n-j H:i:s");
			$tiempo_transcurrido 	= (strtotime($ahora)-strtotime($fechaGuardada));

			#comparar el tiempo transcurrido 
			if($tiempo_transcurrido >= 600) {

				#si el tiempo es mayo del indicado como tiempo de vida de la session
				session_destroy(); #destruir la sesión y se redirecciona a lagin
				$response->redirect = FALSE;
				#sino, se actualiza la fecha de la session

			}else {

				#actualizar tiempo de session
				$_SESSION["lastaccess_user"] = $ahora;
				$response->redirect 	= TRUE;
			}
		}
		return $response;
	}

	public function getMenu()
	{
		
		$minStock = $this->getMinStock();

		if(count($minStock) > 0 ){
			$alertStock = '<script type="text/javascript">
								$(document).ready(function(){
									modal_sms("<center><h5>Existen '.count($minStock).' producto(s) con stock bajo</h5></center>");
								});
							</script>
							<section class="middle tab-bar-section  alert-min-stock ">
							<dl class="sub-nav">
							<dt>Productos con stock Bajos</dt>
							<dd class="active"><span data-tooltip aria-haspopup="true" class="has-tip" title="Numero de productos que tiene stock bajo."><a href="#" style="cursor:pointer;" >'.count($minStock).'</a></span></dd>
							</dl>
							</section>';
		}else{
			$alertStock = '<section class="middle tab-bar-section">
							<dl class="sub-nav">
							<dt>Productos con stock Bajos</dt>
							<dd class="active"><span data-tooltip aria-haspopup="true" class="has-tip" title="Numero de productos que tiene stock bajo."><a href="#" style="cursor:pointer;" >0</a></span></dd>
							</dl>
							</section>';
		}

		$menu ='
		<nav class="tab-bar">
			<section class="left-small">
				<a class="left-off-canvas-toggle menu-icon" aria-expanded="false"><span></span></a>
			</section>

			<section class="middle tab-bar-section">
				<h1 class="title">Sistema Contable</h1>
			</section>
			'.$alertStock.'
		</nav>
		<aside class="left-off-canvas-menu">
			<ul class="off-canvas-list">
				<li><label>Clientes</label></li>
				<li class="active"><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'clientList')).'" >Clientes</a></li>
				<!--<li class="active"><a  href="#" onclick="newStock()" >Nuevos Clientes</a></li>-->
				<li><label>Mi Tienda</label></li>
				<li><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'productList')).'"  >Producto</a></li>
				<li><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'providerList')).'" >Proveedor</a></li>
				<li><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'stockList')).'" >Stock</a></li>
				<li><label>Ventas</label></li>
				<li><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'sale')).'" >Ventas</a></li>
				<li><label>Recibos de ventas</label></li>
				<li><a  href="'.$this->app['url_generator']->generate('bill').'" >Recibos</a></li>
				<li><label>Reporteria</label></li>
				<li><a  href="'.$this->app['url_generator']->generate('report', array("type"=> "product")).'" >Reporte Productos</a></li>
				<li><a  href="'.$this->app['url_generator']->generate('report', array("type"=> "client")).'" >Reporte Clientes</a></li>
			</ul>
		</aside>';

		return $menu;
	}

	public function getMinStock(){

		try{
		$query = "SELECT p.name, s.totalStock,s.minStock
					FROM fc_stock as s
					INNER JOIN fc_product as p
					ON p.id = s.id_product
					WHERE s.minStock >= s.totalStock";
		$min  		= $this->app['dbs']['mysql_silex']->fetchAll($query);
		return $min;
	}catch(Exception $e){
		return '';
	}

		
	}

}
?>