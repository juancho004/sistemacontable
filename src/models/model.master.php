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
		$password 	= md5($password);	
		$table 		= "{$this->prefix}user_admin";
		$query 		= 'SELECT * FROM '.$table.' WHERE user = "'.$user.'" AND password = "'.$password.'" ';
		$user  		= $this->app['dbs']['mysql_silex']->fetchAssoc($query);

		if( !empty($user['id']) ):
			#inicia sesión
			@session_name("login_usuario");
			@session_start();

			#registrar inicio de sesion
			$_SESSION["authenticated_user"]	= TRUE; #asignar que el usuario se autentico
			$_SESSION["lastaccess_user"]	= date("Y-n-j H:i:s"); #definir la fecha y hora de inicio de sesión en formato aaaa-mm-dd hh:mm:ss

			return TRUE;

		endif;
	
		return FALSE;

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
			$response->url 		= 'index.php/login';

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
				$response->url 		= 'index.php/login';
				#sino, se actualiza la fecha de la session

			}else {

				#actualizar tiempo de session
				$_SESSION["lastaccess_user"] = $ahora;
				$response->redirect 	= TRUE;
				$response->url 			= 'index.php/home';

			}
		}
		return $response;
	}

	public function getMenu()
	{

		$menu ='
			<nav class="top-bar foundation-bar" data-topbar>
			  <ul class="title-area">
			    <li class="name">
			     <span data-tooltip class="has-tip" title="Try resizing your browser to see how the grid stacks"><h1 class="show-for-small-only"><a href="#">Small screen</a></h1></span>
			     <span data-tooltip class="has-tip" title="Try resizing your browser to see how the grid stacks"><h1 class="show-for-medium-only"><a href="#">Medium Screen</a></h1></span>
			     <span data-tooltip class="has-tip" title="Try resizing your browser to see how the grid stacks"><h1 class="show-for-large-only"><a href="#">Large screen</a></h1></span>
			    </li>
			  </ul>
			  <section class="top-bar-section">
			    <!-- Right Nav Section -->
			    <ul class="right">
			      <li><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'productList')).'"  >Producto</a></li>
			      <li><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'providerList')).'" >Proveedor</a></li>
			      <li><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'stockList')).'" >Stock</a></li>
			      <li class="active"><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'clientList')).'" >CLientes</a></li>
			      <li><a  href="'.$this->app['url_generator']->generate('view', array('view' => 'sale')).'" >Ventas</a></li>
			    </ul>
			  </section>
			</nav>';

		return $menu;
	}

}
?>