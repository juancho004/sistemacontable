<?php 
/**
 * Class master .
 *
 * @author Jcbarreno <jcbarreno.b@gmail.com>
 * @version 1.0
 * @package 
 */
class ModelProvider {

	protected $prefix;
	protected $app;

	public function  __construct($app, $prefix)
	{
		$this->prefix = $prefix;
		$this->app 	= $app;
		$this->tabprovider 	= "{$this->prefix}provider";
	}

	public function crudProvider($view=false,$params=false)
	{

		$table 		= $this->tabprovider;
		$data 		= array();
		$dataForm	= new stdClass();

		switch ($view) {
			case 'create':
				foreach ($params as $key => $value) {
					$data[$value['name']] = $value['value'];
				}

				$dataForm->name 	= $data['name'];
				$dataForm->phone 	= (!preg_match('/^[0-9]{8,9}$/', (int)$data['phone']) )? "":$data['phone'];
				$dataForm->address 	= $data['address'];
				$dataForm->nit 		= $data['nit'];

				$isValid 					= $this->validateEmptyForm($dataForm);

				if ( !$isValid->status ){
					return $isValid;
				}

				$query = "insert into {$table} (name,phoneNumber,address,nit) ";
				$query.= "values ('".$dataForm->name."',".$dataForm->phone.",'".$dataForm->address."','".$dataForm->nit."')";
				return $this->insert($query,$table);

			break;
			
			case 'read':
				return $this->getListProvider("SELECT * FROM {$table}");
			break;

			case 'edit':
				return $this->getListProvider("SELECT * FROM {$table} WHERE id = {$params}",true);
			break;

			case 'update':
				
				foreach ($params as $key => $value) {
					$data[$value['name']] = $value['value'];
				}

				$dataForm->id 		= $data['id'];
				$dataForm->name 	= $data['name'];
				$dataForm->phone 	= $data['phone'];
				$dataForm->address 	= $data['address'];
				$dataForm->nit 		= $data['nit'];
				$isValid 			= $this->validateEmptyForm($dataForm);

				if ( !$isValid->status ){
					return $isValid;
				}
		
				return $this->update('UPDATE '.$table.' SET name="'.$this->app->escape($dataForm->name).'", phoneNumber="'.$this->app->escape($dataForm->phone).'", address="'.$dataForm->address.'", nit="'.$dataForm->nit.'" WHERE id = '.$dataForm->id.'');
				
			break;

			case 'delete':
			$id = (int)$params;
				return $this->delete("DELETE FROM {$table} WHERE id = {$id}");
			break;
		}

		return $response;

	}

	public function getProvider()
	{
		$query = "SELECT * FROM {$this->tabprovider} ";
		
		$list = $this->app['dbs']['mysql_silex']->fetchAll($query);
		$select = "<select id='select-provider' name='select-provider' >";

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

	public function getListProvider($query,$action=false)
	{

		$htmlList = "";
		try{
			$list = $this->app['dbs']['mysql_silex']->fetchAll($query);

			if( !$action ){
				foreach ($list as $key => $value) {
					$htmlList.= '<tr class="header-option" >
					<td>'.$value['name'].'</td>
					<td>'.$value['phoneNumber'].'</td>
					<td>'.$value['address'].'</td>
					<td>'.$value['nit'].'</td>
					<td>
						<img onClick="crudProvider( \''.$value['id'].'\',\'delete\');" src="'.$this->app['source'].'home/foundation-icons/svgs/fi-page-delete.svg" >
						<img onClick="crudProvider( \''.$value['id'].'\',\'edit\');" src="'.$this->app['source'].'home/foundation-icons/svgs/fi-page-edit.svg" >
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
										<input id="name" type="text" placeholder="Ingresa nombre de producto" name="name" value="'.$value['name'].'"/>
									</label>
								</div>
							</div>

							<div class="row medium-uncollapse large-collapse">
								<div class="large-12 columns">
									<label>Telefono:
										<input id="phone" type="text" placeholder="Ingresa nombre de producto" name="phone"  value="'.$value['phoneNumber'].'" />
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
										<input id="nit" type="text" placeholder="Ingresa nombre de producto" name="nit" value="'.$value['nit'].'"/>
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
										'.$this->crudProvider("read").'
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