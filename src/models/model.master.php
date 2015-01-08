<?php 
/**
 * Class registger .
 *
 * @author Jcbarreno <jcbarreno.b@gmail.com>
 * @version 1.0
 * @package 
 */
class ModelRegisterCode {

	protected $prefix;
	protected $app;

	public function  __construct($app, $prefix) {
		$this->prefix = $prefix;
		$this->app 	= $app;
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

	public function validateForm($data)
	{
		$response = array();

		foreach ($data as $key => $value) {
			if( empty($value) ){
				$response['status'] 	= false;
				$response['message'] 	= "Tódos los campos son necesarios.";
				return $response;
			}
		}

		foreach ($data as $key => $value) {

			switch ($key) {
				case 'name':
				break;

				case 'lastName':
				break;

				case 'mail':
					if (!ereg("^([a-zA-Z0-9._]+)@([a-zA-Z0-9.-]+).([a-zA-Z]{2,4})$",$value)){ 
						$response['status'] 	= false;
						$response['message'] 	= "Ingresa un corrreo válido.";
						return $response;
					} 
				break;

				case 'phone':
				 	$expresion = '/^[0-9]{8,9}$/';
				 	if(!preg_match($expresion, $value)){ 
						$response['status'] 	= false;
						$response['message'] 	= "Ingresa un teléfono válido.";
						return $response;
					}
				break;

				case 'dpi':
					$expresion = '/^[0-9]{13,13}$/';
				 	if(!preg_match($expresion, $value)){ 
						$response['status'] 	= false;
						$response['message'] 	= "Código de DPI no válido.";
						return $response;
					}
				break;
				
				default:
				break;
			}
		}

		$_SESSION['dataForm'] = encode(json_encode($data));

		$response['status'] 	= true;
		$response['content'] 	= $this->getCodeForm();
		return $response;

	}

	
	public function getReport($params=array(),$search=false)
	{	

		if( $search ){ 
			foreach ($params as $key => $value) {
				if( empty($value) ){
					$response['status'] 	= false;
					$response['message'] 	= "Tódos los campos son necesarios.";
					return $response;
				}
			}
		}

		if( !$search ){

			$typeExport = ( empty($params['init']) ||  empty($params['end']) )? false : true ;
			$dateInit = $params['init'];
			$dateEnd = $params['end'];

		}

		if( $search ){
			$init 	= strtotime($params['init'] );
			$end 	= strtotime($params['end'] );

			$dateInit = $params['init'];
			$dateEnd = $params['end'];

			if( $end < $init ){
				$response['status'] 	= false;
				$response['message'] 	= "Rango de fechas incorrecto.";
				return $response;	
			}
		}

		#registerDate >= '{}' AND registerDate <= '{}' 

		$table 	= $this->prefix."code_bar";
		if( $search ){
			$query 	= "SELECT * FROM {$table} WHERE registerDate >= DATE_FORMAT('{$dateInit}','%Y-%m-%d') AND registerDate <= DATE_FORMAT('{$dateEnd}','%Y-%m-%d')  AND status = 1";
		}else{
			if( $typeExport ){
				$query 	= "SELECT * FROM {$table} WHERE registerDate >= DATE_FORMAT('{$dateInit}','%Y-%m-%d') AND registerDate <= DATE_FORMAT('{$dateEnd}','%Y-%m-%d')  AND status = 1";
			}else{
				$query 	= "SELECT * FROM {$table} WHERE status = 1";	
			}
			
		}

		#_pre($query);exit;

		$list 	= $this->app['dbs']['mysql_silex']->fetchAll($query);

		$table = '<table class="table table-bordered table-hover">';
		$table.= '<thead><tr>';
		$table.= '<th>#</ht>';
		$table.= '<th>Nombre</ht>';
		$table.= '<th>Apellido</ht>';
		$table.= '<th>Tel&eacute;fono</ht>';
		$table.= '<th>DPI</ht>';
		$table.= '<th>Email</ht>';
		$table.= '<th>C&oacute;digo</ht>';
		$table.= '<th>Fecha Registro</ht>';
		$table.= '</tr></thead><tbody>';

		foreach ($list as $key => $value) {
			$table.= '<tr>';
			$table.= "<td>".($key+1)."</td>";
			$table.= "<td>".$value['name']."</td>";
			$table.= "<td>".$value['lastName']."</td>";
			$table.= "<td>".$value['phone']."</td>";
			$table.= "<td>".$value['dpi']."</td>";
			$table.= "<td>".$value['email']."</td>";
			$table.= "<td>".$value['code']."</td>";
			$table.= "<td>".$value['registerDate']."</td>";
			$table.= '</tr>';
		}
		$table.= '</tbody></table>';
		$response = array();
		$response['status'] 	= true;
		$response['content'] 	= $table;
		return $response;
	}

}
?>