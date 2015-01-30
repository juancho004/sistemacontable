<?php 
/**
 * Class master .
 *
 * @author Jcbarreno <jcbarreno.b@gmail.com>
 * @version 1.0
 * @package 
 */
class ModelClient {

	protected $prefix;
	protected $app;

	public function  __construct($app, $prefix)
	{
		$this->prefix = $prefix;
		$this->app 	= $app;
		$this->tabClient 	= "{$this->prefix}client";
	}

	public function crudClient($view=false,$params=false)
	{
		$table 		= $this->tabClient;
		$data 		= array();
		$dataForm	= new stdClass();

		switch ($view) {
			case 'create':
				foreach ($params as $key => $value) {
					$data[$value['name']] = $value['value'];
				}

				$dataForm->name 	= $data['name'];
				$dataForm->lastName = $data['lastName'];
				$dataForm->phone 	= $data['phone'];
				$dataForm->address 	= $data['address'];
				$dataForm->nit 		= $data['nit'];

				$isValid 					= $this->validateEmptyForm($dataForm);

				if ( !$isValid->status ){
					return $isValid;
				}

				$exist = $this->validateExistClient($dataForm->nit,$table);

				if ( !$exist->status ){
					return $exist;
				}

				$query = "insert into {$table} (name,lastName,nit,address,phoneNumber) ";
				$query.= "values ('".$dataForm->name."','".$dataForm->lastName."',".$dataForm->phone.",'".$dataForm->address."','".$dataForm->nit."')";
				return $this->insert($query,$table);

			break;
			
			case 'read':

				$query = "SELECT * FROM {$table} ";
				return $this->getListStock($query);
			break;

			case 'edit':
				$query = "SELECT * FROM {$table} WHERE id = {$params}";
				return $this->getListStock($query,true);
			break;

			case 'update':
				
				foreach ($params as $key => $value) {
					$data[$value['name']] = $value['value'];
				}

				$dataForm->id 		= $data['id'];
				$dataForm->name 	= $data['name'];
				$dataForm->lastName = $data['lastName'];
				$dataForm->phone 	= $data['phone'];
				$dataForm->address 	= $data['address'];
				$dataForm->nit 		= $data['nit'];

				$isValid 			= $this->validateEmptyForm($dataForm);

				if ( !$isValid->status ){
					return $isValid;
				}
		
				return $this->update('UPDATE '.$table.' SET name="'.$this->app->escape($dataForm->name).'", lastName="'.$this->app->escape($dataForm->lastName).'",nit="'.$this->app->escape($dataForm->nit).'",address="'.$this->app->escape($dataForm->address).'",phoneNumber='.$this->app->escape($dataForm->phone).' WHERE id = '.$dataForm->id.' ');
				
			break;

			case 'delete':
			$id = (int)$params;
				return $this->delete("DELETE FROM {$table} WHERE id = {$id}");
			break;
		}

		return $response;

	}

	public function validateExistClient($nit,$table)
	{
		$response = new stdClass();
		try{

			$query = "SELECT id FROM {$table} WHERE nit = '{$nit}' ";
			#_pre($query);exit;
			$exist = $this->app['dbs']['mysql_silex']->fetchAssoc($query);
			$total = !(boolean)count($exist['id']);
			
			if(!$total){
				$response->status 	= FALSE;
				$response->message 	= "El registro ya existe, si quieres actualizarlo, ve a la sección de actualización";
			}else{
				$response->status 	= TRUE;
				$response->message 	= "Ok";
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
		#_pre($query);
		#exit;
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
					<td>'.$value['name'].'</td>
					<td>'.$value['lastName'].'</td>
					<td>'.$value['nit'].'</td>
					<td>'.$value['address'].'</td>
					<td>'.$value['phoneNumber'].'</td>
					<td>
						<img onClick="crudClient( \''.$value['id'].'\',\'delete\');" src="'.$this->app['source'].'home/foundation-icons/svgs/fi-page-delete.svg" >
						<img onClick="crudClient( \''.$value['id'].'\',\'edit\');" src="'.$this->app['source'].'home/foundation-icons/svgs/fi-page-edit.svg" >
					</td>
					</tr>';
				}	
			}else{
				
				foreach ($list as $key => $value) {

					$htmlList.='<input type="hidden" value="'.$value['id'].'" name="id" />';
					$htmlList.='
							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>Nombre:
										<input id="name" type="text" placeholder="Ingresa nombre" name="name" value="'.$value['name'].'" />
									</label>
								</div>
							</div>

							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>Apellido:
										<input id="lastName" type="text" placeholder="Ingresa apellido" name="lastName" value="'.$value['lastName'].'" />
									</label>
								</div>
							</div>

							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>Telefono:
										<input id="phone" type="text" placeholder="Ingresa nombre de producto" name="phone" value="'.$value['phoneNumber'].'" />
									</label>
								</div>
							</div>

							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>Dirección:
										<textarea id="address" placeholder="Ingresa una descripción" name="address" >'.$value['address'].'</textarea>
									</label>
								</div>
							</div>

							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>NIT:
										<input id="nit" type="text" placeholder="Ingresa nombre de producto" name="nit" value="'.$value['nit'].'" />
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
										'.$this->crudClient("read").'
									</tbody>
								</table>';
			return $response;
		}catch(Exception $e){
			$response->status 	= FALSE;
			$response->message 	= "Error #02: No se pudo eliminar el registro.";
			return $e->getMessage();
		}
	}

	public function getClient()
	{
		$query = "SELECT id, name FROM {$this->tabClient} ";
		$list = $this->app['dbs']['mysql_silex']->fetchAll($query);

		$select = "<select id='select_client' name='select_client' >";
		$select.='<option value="0">Selecciona un cliente</option>';

		foreach ($list as $key => $value) {
				$select.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
		$select.= "</select>";

		return $select;
	}

}
?>