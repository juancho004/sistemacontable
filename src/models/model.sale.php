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
					<input id="input-item-'.$row.'" class="totalStock" type="text" placeholder="Ingresa nombre de producto" name="totalStock[]" />
				</label>
			</div>

		</div>';
		return $productSale;
	}

	public function registerSale($params)
	{
		$listSale		= array();
		$client 		= $params['select_client'];
		$listProduct 	= $params['select_product'];
		$listTotalItem 	= $params['totalStock'];
		$registerDate	= date("Y-m-d H:i:s");
		$correlative 	= $this->getCorrelativeNumber();

		for ($i=0; $i < count($listProduct); $i++) { 
			$listSale[] = array("item" => $listProduct[$i], "total" => $listTotalItem[$i] );
		}

		_pre($listSale);exit;

		$this->stock->discountStock();

		$query = "INSERT INTO fc_sale (registerDate, billNumber, id_user, id_client) VALUES ('".$registerDate."', '".$correlative."', '1', ".$client.") ";
		$this->app['dbs']['mysql_silex']->executeQuery($query);
		$id_sale = $this->app['db']->lastInsertId('id');


		foreach ($listSale as $key => $value) {
			$query = "INSERT INTO fc_bill (totalProduct, id_sale, id_stock) VALUES (".$value['total'].", ".$id_sale.", ".$value['item'].") ";
			$this->app['dbs']['mysql_silex']->executeQuery($query);
		}



		
		#_pre($correlative);
		#exit;
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

	public function crudStock($view=false,$params=false)
	{

		$table 		= $this->tabStock;
		$data 		= array();
		$dataForm	= new stdClass();

		switch ($view) {
			case 'create':
				foreach ($params as $key => $value) {
					$data[$value['name']] = $value['value'];
				}

				$dataForm->id_provider 	= $data['select_provider'];
				$dataForm->id_product 	= $data['select_product'];
				$dataForm->totalStock 	= $data['totalStock'];
				$dataForm->minStock 	= $data['minStock'];

				$isValid 					= $this->validateEmptyForm($dataForm);

				if ( !$isValid->status ){
					return $isValid;
				}

				$exist = $this->validateExistRegister($dataForm->id_provider,$dataForm->id_product,$table);
				if ( !$exist->status ){
					return $exist;
				}

				$query = "insert into {$table} (totalStock,minStock,id_provider,id_product) ";
				$query.= "values (".$dataForm->totalStock.",".$dataForm->minStock.",".$dataForm->id_provider.",".$dataForm->id_product.")";
				return $this->insert($query,$table);

			break;
			
			case 'read':

				$query = "SELECT s.id, s.totalStock, s.minStock, s.id_provider, s.id_product, pr.name as provider, pt.name as product 
						FROM {$this->tabStock} s 
						INNER JOIN {$this->tabProvider} as pr 
						ON s.id_provider = pr.id
						INNER JOIN {$this->tabProduct} as pt 
						ON s.id_product = pt.id ";

				return $this->getListStock($query);
			break;

			case 'edit':
				$query = "SELECT s.id, s.totalStock, s.minStock, s.id_provider, s.id_product, pr.name as provider, pt.name as product 
						FROM {$this->tabStock} s 
						INNER JOIN {$this->tabProvider} as pr 
						ON s.id_provider = pr.id
						INNER JOIN {$this->tabProduct} as pt 
						ON s.id_product = pt.id 
						WHERE s.id = {$params}";
				return $this->getListStock($query,true);
			break;

			case 'update':
				
				foreach ($params as $key => $value) {
					$data[$value['name']] = $value['value'];
				}

				$dataForm->id 			= $data['id'];
				$dataForm->id_provider 	= $data['select_provider'];
				$dataForm->id_product 	= $data['select_product'];
				$dataForm->totalStock 	= $data['totalStock'];
				$dataForm->minStock 	= $data['minStock'];

				$isValid 			= $this->validateEmptyForm($dataForm);

				if ( !$isValid->status ){
					return $isValid;
				}
		
				return $this->update('UPDATE '.$table.' SET totalStock='.$this->app->escape($dataForm->totalStock).', minStock='.$this->app->escape($dataForm->minStock).' WHERE id_provider = '.$dataForm->id_provider.' AND id_product = '.$dataForm->id_product.' AND id = '.$dataForm->id.' ');
				
			break;

			case 'delete':
			$id = (int)$params;
				return $this->delete("DELETE FROM {$table} WHERE id = {$id}");
			break;
		}

		return $response;

	}

	public function validateExistRegister($provider,$product,$table)
	{
		$response = new stdClass();

		try{

			$query = "SELECT id FROM {$table} WHERE id_provider = {$provider} AND id_product = {$product} ";
			$exist = $this->app['dbs']['mysql_silex']->fetchAssoc($query);
			$total = !(boolean)count($exist['id']);
			
			if(!$total){
				$response->status 	= FALSE;
				$response->message 	= "El registro ya existe, si quieres actualizarlo, ve a la sección de actualización";
			}else{
				$response->status 	= TRUE;
				$response->message 	= "Registro creado exitosamente";
			}

			return $response;
		}catch(Exception $e){
			$response->status 	= FALSE;
			$response->message 	= "Error #01: No se pudo insertar en en la tabla {$table}.";
			return $e->getMessage();
		}


		

	}

	public function update($query)
	{
		$resp = (boolean)$this->app['dbs']['mysql_silex']->executeQuery($query);
		$response->status 	= $resp;
		$response->reloadPage	= true;
		$response->message 	= ($resp)? "Registro actualizado exitosamente":"Ocurrio un error para actualizar el registro, intenta de nuevo más tarde";
		return $response;
	}

	public function getListStock($query,$action=false)
	{


		$htmlList = "";
		try{
			$list = $this->app['dbs']['mysql_silex']->fetchAll($query);

			if( !$action ){
				foreach ($list as $key => $value) {
					$htmlList.= '<tr class="header-option" >
					<td>'.$value['provider'].'</td>
					<td>'.$value['product'].'</td>
					<td>'.$value['totalStock'].'</td>
					<td>'.$value['minStock'].'</td>
					<td>
						<img onClick="crudStock( \''.$value['id'].'\',\'delete\');" src="'.$this->app['source'].'home/foundation-icons/svgs/fi-page-delete.svg" >
						<img onClick="crudStock( \''.$value['id'].'\',\'edit\');" src="'.$this->app['source'].'home/foundation-icons/svgs/fi-page-edit.svg" >
					</td>
					</tr>';
				}	
			}else{
				
				foreach ($list as $key => $value) {

					$htmlList.='<input type="hidden" value="'.$value['id'].'" name="id" />';
					$htmlList.='
							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>Proveedor:
										'.$this->provider->getProvider($value['id_provider']).'
									</label>
								</div>
							</div>

							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>Producto:
										'.$this->product->getProduct($value['id_product']).'
									</label>
								</div>
							</div>


							<div class="row medium-uncollapse large-collapse">
								<div class="large-5 columns">
									<label>Total Stock:
										<input id="totalStock" type="text" placeholder="Ingresa nombre de producto" name="totalStock" value="'.$value['totalStock'].'" />
									</label>
								</div>
								<div class="large-2 columns">
								<p></p>
								</div>

								<div class="large-5 columns">
									<label>Minimo Stock:
										<input id="minStock" type="text" placeholder="Ingresa nombre de producto" name="minStock" value="'.$value['minStock'].'" />
									</label>
								</div>

							</div>';
				}				
			}

			return $htmlList;
		}catch(Exception $e){
			return "Errors";
		}

		
	}

	public function validateEmptyForm($dataForm)
	{

		$emtyValues = array();
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
			$response->message 	= "Registro creado exitosamente";
			#$response->id 		= $id_tab;
			return $response;
		}catch(Exception $e){
			$response->status 	= FALSE;
			$response->message 	= "Error #01: No se pudo insertar en en la tabla {$table}.";
			return $e->getMessage();
		}
	}

	public function delete($query)
	{
		$response = new stdClass();
		try{
			$this->app['dbs']['mysql_silex']->executeQuery($query);
			$id_tab 				= $this->app['db']->lastInsertId('id');
			$response->status 	= TRUE;
			$response->message 	= "Registro eliminado exitosamente";
			$response->content 	= '<table>
									<thead>
										<tr>
											<th width="200" >Nombre</th>
											<th width="150" >Teléfono</th>
											<th width="450" >Dirección</th>
											<th width="150" >NIT</th>
											<th width="150" ></th>
										</tr>
									</thead>
									<tbody>
										'.$this->crudStock("read").'
									</tbody>
								</table>';
			return $response;
		}catch(Exception $e){
			$response->status 	= FALSE;
			$response->message 	= "Error #02: No se pudo eliminar el registro.";
			return $e->getMessage();
		}
	}

}
?>