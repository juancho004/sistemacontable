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
		$this->tabproduct 	= "{$this->prefix}product";
	}

	public function crudProduct($view=false,$params=false)
	{

		$table 		= $this->tabproduct;
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
				$isValid 					= $this->validateEmptyForm($dataForm);

				if ( !$isValid->status ){
					return $isValid;
				}

				$query = "insert into {$table} (name,description,providerPrice,userPrice) ";
				$query.= "values ('".$dataForm->name."','".$dataForm->description."',".$dataForm->providerPrice.",".$dataForm->userPrice.")";
				return $this->insert($query,$table);
			break;
			
			case 'read':
				return $this->getListProducto("SELECT * FROM {$table}");
			break;

			case 'edit':
				return $this->getListProducto("SELECT * FROM {$table} WHERE id = {$params}",true);
			break;

			case 'update':
				
				foreach ($params as $key => $value) {
					$data[$value['name']] = $value['value'];
				}
				$pricePriver 				= (int)$data['priceProvider'].".".(int)$data['priceProviderCent'];
				$priceClient 				= (int)$data['pricelient'].".".(int)$data['pricelientCent'];
				$dataForm->id 				= $data['id'];
				$dataForm->name 			= $data['name'];
				$dataForm->description 		= $data['description'];
				$dataForm->providerPrice 	= $pricePriver;
				$dataForm->userPrice 		= $priceClient;
				$isValid 					= $this->validateEmptyForm($dataForm);

				if ( !$isValid->status ){
					return $isValid;
				}
				
				return $this->update('UPDATE '.$table.' SET name="'.$this->app->escape($dataForm->name).'", description="'.$this->app->escape($dataForm->description).'", providerPrice='.$dataForm->providerPrice.', userPrice='.$dataForm->userPrice.' WHERE id = '.$dataForm->id.'');
				
			break;

			case 'delete':
			$id = (int)$params;
				return $this->delete("DELETE FROM {$table} WHERE id = {$id}");
			break;
		}

		return $response;

	}

	public function getProduct()
	{
		$query = "SELECT * FROM {$this->tabproduct} ";
		
		$list = $this->app['dbs']['mysql_silex']->fetchAll($query);
		$select = "<select id='select-product' name='select-product' >";

		foreach ($list as $key => $value) {
			$select.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
		$select.= "</select>";
		return $select;
	}

	public function update($query)
	{
		$resp = (boolean)$this->app['dbs']['mysql_silex']->executeQuery($query);
		$response->status 	= $resp;
		$response->reloadPage	= true;
		$response->message 	= ($resp)? "Registro actualizado exitosamente":"Ocurrio un error para actualizar el registro, intenta de nuevo más tarde";
		return $response;
	}

	public function getListProducto($query,$action=false)
	{

		$htmlList = "";
		try{
			$list = $this->app['dbs']['mysql_silex']->fetchAll($query);

			if( !$action ){
				foreach ($list as $key => $value) {
					$htmlList.= '<tr class="header-option" >
					<td>'.$value['name'].'</td>
					<td>'.$value['description'].'</td>
					<td>'.$value['providerPrice'].'</td>
					<td>'.$value['userPrice'].'</td>
					<td>
						<img onClick="crudProduct( \''.$value['id'].'\',\''.delete.'\');" src="'.$this->app['source'].'home/foundation-icons/svgs/fi-page-delete.svg" >
						<img onClick="crudProduct( \''.$value['id'].'\',\''.edit.'\');" src="'.$this->app['source'].'home/foundation-icons/svgs/fi-page-edit.svg" >
					</td>
					</tr>';
				}	
			}else{
				foreach ($list as $key => $value) {

					$priceProvider 	= explode('.', $value['providerPrice']);
					$priceUser 		= explode('.', $value['userPrice']);

					$htmlList.='<input type="hidden" value="'.$value['id'].'" name="id" />';
					$htmlList.='
							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>Nombre:
										<input id="name" type="text" placeholder="Ingresa nombre de producto" name="name" value="'.$value['name'].'" />
									</label>
								</div>
							</div>

							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>Descripción:
										<textarea id="description" placeholder="Ingresa una descripción" name="description" >'.$value['description'].'</textarea>
									</label>
								</div>
							</div>

							<div class="row medium-uncollapse large-collapse">
								<div class="large-36columns">
									<label>Precio Proveedor:
										<div class="row medium-uncollapse large-collapse">
											<div class="large-2 columns">
												<div class="row collapse prefix-round">
													<div class="small-3 columns">
														<a href="#" class="button prefix">Q.</a>
													</div>
													<div class="small-9 columns">
														<input type="text" placeholder="00" name="priceProvider" value="'.(int)$priceProvider[0].'" >
													</div>
												</div>
											</div>

											<div class="large-2 columns">
												<div class="row collapse prefix-round">
													<div class="small-3 columns">
														<a href="#" class="button prefix">¢</a>
													</div>
													<div class="small-9 columns">
														<input type="text" placeholder="00" name="priceProviderCent" value="'.(int)$priceProvider[1].'" >
													</div>
												</div>

											</div>
										</div>
									</label>
								</div>
							</div>

							<div class="row medium-uncollapse large-collapse">
								<div class="large-36columns">
									<label>Precio Cliente:
										<div class="row medium-uncollapse large-collapse">
											<div class="large-2 columns">
												<div class="row collapse prefix-round">
													<div class="small-3 columns">
														<a href="#" class="button prefix">Q.</a>
													</div>
													<div class="small-9 columns">
														<input type="text" placeholder="00" name="pricelient" value="'.(int)$priceUser[0].'" >
													</div>
												</div>
											</div>

											<div class="large-2 columns">
												<div class="row collapse prefix-round">
													<div class="small-3 columns">
														<a href="#" class="button prefix">¢</a>
													</div>
													<div class="small-9 columns">
														<input type="text" placeholder="00" name="pricelientCent" value="'.(int)$priceUser[1].'" >
													</div>
												</div>

											</div>
										</div>
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
										<tr >
											<th width="200" >Nombre</th>
											<th width="400" >Descripción</th>
											<th width="150" >Precio Proveedor</th>
											<th width="150" >Precio Usuario</th>
											<th width="150" ></th>
										</tr>
									</thead>
									<tbody>
										'.$this->crudProduct("read").'
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