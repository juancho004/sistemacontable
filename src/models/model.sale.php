<?php 
/**
 * Class master .
 *
 * @author Jcbarreno <jcbarreno.b@gmail.com>
 * @version 1.0
 * @package 
 */
class ModelSale {

	protected $prefix;
	protected $app;

	public function  __construct($app, $prefix)
	{
		$this->prefix = $prefix;
		$this->app 	= $app;
		$this->tabSale 	= "{$this->prefix}sale";
		$this->stock 	= new ModelStock($app,$prefix);
	}

	public function productSale($option = false,$row=0 )
	{
		$drop = '';
		if($option){
			$drop = '<div style="cursor:pointer;" class="remove-item">X</div>';
		}

		$productSale = '
		<div class="row medium-uncollapse large-collapse">
		'.$drop.'
			<div class="large-5 columns">
				<label>Producto:
					'.$this->stock->getStock($row).'
				</label>
			</div>
			
			<div class="large-2 columns">
				<span>&nbsp;</span>
			</div>
			
			<div class="large-5 columns">
				<label>Cantidad:
					<input id="input-item-'.$row.'" class="totalStock" type="text" placeholder="Ingresa nombre de producto" name="totalStock[]" disabled/>
				</label>
			</div>

		</div>';
		return $productSale;
	}

	public function registerSale($params)
	{
		$listSale		= array();
		$response	 	= new stdClass();
		$client 		= $params['select_client'];
		$listProduct 	= $params['select_product'];
		$listTotalItem 	= $params['totalStock'];
		$registerDate	= date("Y-m-d H:i:s");
		$correlative 	= $this->getCorrelativeNumber();

		for ($i=0; $i < count($listProduct); $i++) { 
			$listSale[] = array("item" => $listProduct[$i], "total" => $listTotalItem[$i] );
		}

		$this->stock->discountStock($listSale);

		$query = "INSERT INTO fc_sale (registerDate, billNumber, id_user, id_client) VALUES ('".$registerDate."', '".$correlative."', '1', ".$client.") ";
		$resp = (boolean)$this->app['dbs']['mysql_silex']->executeQuery($query);
		$id_sale = $this->app['db']->lastInsertId('id');
		$response->idSale 	= $id_sale;

		if( !(boolean)$id_sale ){
			$response->status 	= $resp;
			$response->message 	= (!$resp)? "Ocurrio un error, cominicate con el administrador":"Ok";
			return $response;
		}

		$arrayError = array();

		foreach ($listSale as $key => $value) {
			$query = "INSERT INTO fc_bill (totalProduct, id_sale, id_stock) VALUES (".$value['total'].", ".$id_sale.", ".$value['item'].") ";
			$return = (boolean)$this->app['dbs']['mysql_silex']->executeQuery($query);
			if( $return ){
				$arrayError[] = array( "id" =>  $value['item'] , "total" => $value['total'] , "status" => false );
			}
		}

		if( count($arrayError) >= 1 )
		{
			#fc_sale_error_log
			foreach ($arrayError as $key => $value) {

				$description = json_encode( array("idProduct" => $value['id'], "total" => $value['total'] ) );
				$dateRegisgter = date("Y-m-d H:i:s");
				$query = "INSERT INTO fc_sale_error_log (error, description, registerDate) VALUES ('Error register in stock', '{$description}', '{$dateRegisgter}' ) ";
				$this->app['dbs']['mysql_silex']->executeQuery($query);
			}
			
			$response->status 	= false;
			$response->message 	= "No se pudieron registar algunos productos.";
			
		}else{
			$response->status 	= true;
			$response->message 	= "ok";
		}
			return $response;

	}


	public function update($query)
	{
		$response = new stdClass();
		$resp = (boolean)$this->app['dbs']['mysql_silex']->executeQuery($query);
		$response->status 	= $resp;
		$response->reloadPage	= true;
		$response->message 	= ($resp)? "Registro actualizado exitosamente":"Ocurrio un error para actualizar el registro, intenta de nuevo más tarde";
		return $response;
	}

	public function getCorrelativeNumber()
	{
		
		try{
			$query = "SELECT billNumber FROM {$this->tabSale} ORDER BY billNumber DESC";
			$code = $this->app['dbs']['mysql_silex']->fetchAssoc($query);
			$code = $code['billNumber'];
			#_pre($code);exit;

		}catch(Exception $e){
			$code = 0;
		}
		$code++;
		#$code = str_pad($code, 10, "0", STR_PAD_LEFT);

		return $code;
	}

	



}
?>