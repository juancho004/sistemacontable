<?php 
/**
 * Class master .
 *
 * @author Jcbarreno <jcbarreno.b@gmail.com>
 * @version 1.0
 * @package 
 */
class ModelStock {

	protected $prefix;
	protected $app;

	public function  __construct($app, $prefix)
	{
		$this->prefix = $prefix;
		$this->app 	= $app;
		$this->tabStock 	= "{$this->prefix}stock";
		$this->tabProvider 	= "{$this->prefix}provider";
		$this->tabProduct 	= "{$this->prefix}product";

		$this->provider 	= new ModelProvider($app,$prefix);
		$this->product 		= new ModelProduct($app,$prefix);
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

				$query = "insert into {$table} (totalStock,minStock,id_provider,id_product,realStock) ";
				$query.= "values (".$dataForm->totalStock.",".$dataForm->minStock.",".$dataForm->id_provider.",".$dataForm->id_product.",".$dataForm->totalStock.")";
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
		
				return $this->update('UPDATE '.$table.' SET totalStock='.$this->app->escape($dataForm->totalStock).', minStock='.$this->app->escape($dataForm->minStock).', realStock='.$this->app->escape($dataForm->totalStock).' WHERE id_provider = '.$dataForm->id_provider.' AND id_product = '.$dataForm->id_product.' AND id = '.$dataForm->id.' ');
				
			break;

			case 'delete':
				$id = (int)$params;
				return $this->delete("DELETE FROM {$table} WHERE id = {$id}");
			break;

			case 'exist':
				return $this->existInStock($params[0],$params[1]);
			break;
		}

		return $response;

	}

	public function existInStock($id,$total)
	{
		$response 	= new stdClass();
		$query 		= "SELECT totalStock FROM {$this->tabStock} WHERE id = {$id} ";
		$exist 		= $this->app['dbs']['mysql_silex']->fetchAssoc($query);
		$response->status 	= ( $exist['totalStock'] > $total )? TRUE:FALSE;
		$response->message 	= (!$response->status)?"No existe stock suficiente para completar la venta":"Ok";
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

	public function getStock($row)
	{
		$query = "SELECT s.id, pt.name as product 
				FROM {$this->tabStock} s 
				INNER JOIN {$this->tabProvider} as pr 
				ON s.id_provider = pr.id
				INNER JOIN {$this->tabProduct} as pt 
				ON s.id_product = pt.id ";

		$list = $this->app['dbs']['mysql_silex']->fetchAll($query);

		$select = "<select id='select-item-".$row."' class='select_product' name='select_product[]' >";
		$select.='<option value="0">Selecciona un producto</option>';

		foreach ($list as $key => $value) {
				$select.='<option value="'.$value['id'].'">'.$value['product'].'</option>';
		}
		$select.= "</select>";

		return $select;
	}

	public function discountStock($listSale)
	{
		foreach ($listSale as $key => $value) {
			#descontar del total de stock
			$query 	= "UPDATE {$this->tabStock} SET  totalStock =  (totalStock - {$value['total']}) WHERE  id = {$value['item']} ";
			$update = $this->app['dbs']['mysql_silex']->executeQuery($query);
		}
		return true;
	}

	public function getBill($id=false)
	{

		
		if(!$id){
			$query = "SELECT s.id, s.registerDate
						FROM fc_sale s
						ORDER BY registerDate ASC";
			$listSale = $this->app['dbs']['mysql_silex']->fetchAll($query);


			$listClient = array();
			$listProduct = array();
			$listBill = array();
			$listDate = array();
			

			foreach ($listSale as $keySale => $sale ) {
				
				$query = "SELECT DISTINCT(c.name), c.lastName, c.nit, p.name as product, b.totalProduct, p.userPrice
					FROM fc_bill as b
					INNER JOIN fc_sale as s
					ON s.id = b.id_sale
					INNER JOIN fc_stock as sk
					ON sk.id = b.id_stock
					INNER JOIN fc_product as p
					ON p.id = sk.id_product
					INNER JOIN fc_client as c
					ON c.id = s.id_client 
					WHERE b.id_sale = {$sale['id']} ";
				
				$bill = $this->app['dbs']['mysql_silex']->fetchAll($query);

					foreach ($bill as $key => $value) {

						if($key == 0){
							$listClient[] = array(
								"id" => $value['name'],
								"name" => $value['name'],
								"lastName" => $value['lastName'],
								"nit" => $value['nit']
							);
						}
					}

					foreach ($bill as $key => $product) {
						$listProduct[$keySale][] = array(
							"product" => $product['product']." Q.".$product['userPrice']." c/u",
							"totalProduct" => $product['totalProduct'],
							"price" => ($product['totalProduct'] * $product['userPrice'] )
						);
					}
				
			}

			foreach ($listSale as $key => $valueDate) {
				$listDate[] = array( "registerDate" => $valueDate['registerDate'] );
			}

			


			foreach ($listClient as $key => $valClient) {
				$listBill[] = array_merge( $valClient, array( "registerDate" => $listDate[$key]['registerDate'] ), array( "productoList" => $listProduct[$key]) );
			}

			$table = '';
			foreach ($listBill as $key => $bill) {

				$table.= '<center><table role="grid" width="80%"><caption>RECIBO DE COMPRA #'.($key+1).'</caption>';

				$table.= '<tr>';
				$table.= '<th colspan="3" >Fecha Registro: '.$bill['registerDate'].'</th>';
				$table.= '</tr>';

				$table.= '<tr>';
				$table.= '<th>Cliente: '.$bill['name'].' '.$bill['lastName'].'</th>';
				$table.= '<th colspan="2">NIT: '.$bill['nit'].'</th>';
				$table.= '</tr>';
				$table.= '<tr>';
				
				$table.= '<th><center>Producto</center></th>';
				$table.= '<th><center>Cantidad</center></th>';
				$table.= '<th><center>Precio</center></th>';
				$table.= '</tr>';


				$totalPrice = 0;
				foreach ($bill['productoList'] as $k => $value) {
					$table.= '<tr>';
					$table.= '<td><center>'.$value['product'].'</center></td>';
					$table.= '<td><center>'.$value['totalProduct'].'</center></td>';
					$table.= '<td><center>'.$value['price'].'</center></td>';
					$table.= '</tr>';
					$totalPrice = $totalPrice + $value['price'];
				}

				$table.= '<tr>';
				$table.= '<th><center>Total</center></th>';
				$table.= '<th></th>';
				$table.= '<th><center>'.$totalPrice.'</center></th>';
				$table.= '</tr>';
				
				$table.= '</table></center>';
			}
				$table.= '';

			return $table;

		}else{

			$query = "SELECT DISTINCT(c.name), c.lastName, c.nit, s.registerDate
						FROM fc_bill as b
						INNER JOIN fc_sale as s
						ON s.id = b.id_sale
						INNER JOIN fc_stock as sk
						ON sk.id = b.id_stock
						INNER JOIN fc_product as p
						ON p.id = sk.id_product
						INNER JOIN fc_client as c
						ON c.id = s.id_client
						WHERE b.id_sale = {$id}";
			$client = $this->app['dbs']['mysql_silex']->fetchAll($query);
			$table = '<center><table role="grid" width="80%"><caption>RECIBO DE COMPRA</caption>';
			
			foreach ($client as $key => $value) {
				$table.= '<tr>';
				$table.= '<th colspan="3" >Fecha Registro: '.$value['registerDate'].'</th>';
				$table.= '</tr>';
				$table.= '<tr>';
				$table.= '<th>Cliente: '.$value['name'].' '.$value['lastName'].'</th>';
				$table.= '<th colspan="2" >NIT: '.$value['nit'].'</th>';
				$table.= '</tr>';
			}
			
			#query regisgtro de facturación
			$query = "SELECT p.name, b.totalProduct , p.userPrice as price
						FROM fc_bill as b
						INNER JOIN fc_sale as s
						ON s.id = b.id_sale
						INNER JOIN fc_stock as sk
						ON sk.id = b.id_stock
						INNER JOIN fc_product as p
						ON p.id = sk.id_product
						WHERE b.id_sale = {$id}";
			$bill = $this->app['dbs']['mysql_silex']->fetchAll($query);
			$table.= '<tr>';
			$table.= '<th><center>Producto</center></th>';
			$table.= '<th><center>Cantidad</center></th>';
			$table.= '<th><center>Precio</center></th>';
			$table.= '</tr>';
			
			$totalPrice = 0;
			foreach ($bill as $key => $value) {
				$table.= '<tr>';
				$table.= '<td><center>'.$value['name'].' '.$value['price'].' c/u </center></td>';
				$table.= '<td><center>'.$value['totalProduct'].'</center></td>';
				$table.= '<td><center>'.$value['price'].'</center></td>';
				$table.= '</tr>';
				$totalPrice = $totalPrice + $value['price'];
			}
			$table.= '<tr>';
			$table.= '<th><center>Total</center></th>';
			$table.= '<th></th>';
			$table.= '<th><center>'.$totalPrice.'</center></th>';
			$table.= '</tr>';

			$table.= '</table></center>';
			return $table;
		}


	}

	public function getReport()
	{
		$query = "SELECT id FROM  fc_stock";
		$stock = $this->app['dbs']['mysql_silex']->fetchAll($query);

		$table = '<table id="report-list" role="grid" width="80%"><caption>Reporteria</caption>';
		$table.= '<tr>';
		$table.= '<th><center>Producto</center></th>';
		$table.= '<th><center>Total Vendidos</center></th>';
		$table.= '<th><center>Precio Unitario</center></th>';
		$table.= '<th><center>Costo Total</center></th>';
		$table.= '<th><center>En Stock</center></th>';
		$table.= '</tr>';
		foreach ($stock as $key => $valStock) {

			$query = "SELECT p.name as nameProduct, SUM(b.totalProduct) as totalProduct, p.userPrice as unitPrice, (p.userPrice*SUM(b.totalProduct)) as totalPrice, s.totalStock 
					FROM fc_bill as b
					INNER JOIN fc_stock as s
					ON s.id = b.id_stock
					INNER JOIN fc_product as p
					ON p.id = s.id_product
					where b.id_stock = {$valStock['id']}";
			$list = $this->app['dbs']['mysql_silex']->fetchAll($query);

			foreach ($list as $key => $value) {
				$table.= '<tr>';
				$table.= '<th><center>'.$value['nameProduct'].'</center></th>';
				$table.= '<th><center>'.$value['totalProduct'].'</center></th>';
				$table.= '<th><center>'.$value['unitPrice'].'</center></th>';
				$table.= '<th><center>'.$value['totalPrice'].'</center></th>';
				$table.= '<th><center>'.$value['totalStock'].'</center></th>';
				$table.= '</tr>';
			}


		}
		$table.="</table>";

		return $table;
		
	}

}
?>
