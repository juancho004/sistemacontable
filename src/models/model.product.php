<?php 
/**
 * Class master .
 *
 * @author Jcbarreno <jcbarreno.b@gmail.com>
 * @version 1.0
 * @package 
 */
class ModelProduct {

	protected $prefix;
	protected $app;

	public function  __construct($app, $prefix)
	{
		$this->prefix = $prefix;
		$this->app 	= $app;
	}

	public function crudProduct($view=false,$params)
	{
		$table 		= "{$this->prefix}product";
		$data 		= array();
		$dataForm	= new stdClass();

		switch ($view) {
			case 'create':

				foreach ($params as $key => $value) {
					$data[$value['name']] = $value['value'];
				}

				$pricePriver 				= (int)$data['priceProvider'].".".(int)$data['priceProviderCent'];
				$priceClient 				= (int)$data['pricelient'].".".(int)$data['pricelientCent'];
				$dataForm->name 			= $data['name'];
				$dataForm->description 		= $data['description'];
				$dataForm->providerPrice 	= $pricePriver;
				$dataForm->userPrice 		= $priceClient;

				$isValid = $this->validateEmptyForm($dataForm);

				if ( !$isValid->status ){
					return $isValid;
				}

				$query = "insert into {$table} (name,description,providerPrice,userPrice) ";
				$query.= "values ('".$dataForm->name."','".$dataForm->description."',".$dataForm->providerPrice.",".$dataForm->userPrice.")";
				return $this->insert($query,$table);

			break;
			
			case 'read':
			default:
			break;
		}

		return $response;

	}

	public function validateEmptyForm($dataForm)
	{
		$response = new stdClass();
		$response->status = true;
		foreach ($dataForm as $key => $value) {
			if( empty($value) ){
				$emtyValues[] = $key;
			}
		}

		if(count($emtyValues) > 0 ){
			$response->status = false;
			$response->message = "Todos los campos son requeridos.";
			$response->empty = $emtyValues;
		}
		return $response;
	}

	public function insert($query,$table)
	{
		$response = new stdClass();
		try{
			$this->app['dbs']['mysql_silex']->executeQuery($query);
			$id_tab 				= $this->app['db']->lastInsertId('id');
			$response->status 	= TRUE;
			$response->message 	= "Ok";
			$response->id 		= $id_tab;
			return $response;
		}catch(Exception $e){
			$response->status 	= FALSE;
			$response->message 	= "Error #01: No se pudo insertar en en la tabla {$table}.";
			return $e->getMessage();
		}

	}

	public function _debug()
	{
		$table 		= "{$this->prefix}acl_user";
		$query 		= 'SELECT * FROM '.$table;
		$user  		= $this->app['dbs']['mysql_silex']->fetchAssoc($query);
		_pre($user);
		exit;

	}

}
?>