var addItem = 0;
function newItemSale()
{
	addItem++;

	jQuery.ajax({
		url: basepath+'/index.php/itemSale',
		type: 'POST',
		async: true,
		data: {
			number:addItem
		},
		dataType: 'json',
		beforeSend: function(xhr){
			$.fancybox.showLoading();
		},
		success: function(json){
			$(".item-sale").append(json.item);
		},	
		complete: function(xhr, textStatus){
			jQuery.fancybox.hideLoading();
		}
	});
}

function validateInStock(idInput,id)
{	

	var total = $("#"+idInput).val();
	var idProduct = idInput.split('input-item-');

	jQuery.ajax({
		url: basepath+'/index.php/crud/stock/exist',
		type: 'POST',
		async: true,
		data: {
			params:[id, total ]
		},
		dataType: 'json',
		beforeSend: function(xhr){
			$.fancybox.showLoading();
		},
		success: function(json){
			if(!json.status){
				$.fancybox(json.message);
				$("#"+idInput).attr('disabled','disabled');
				$("#select-item-"+idProduct[1]+" option[value=0]").prop("selected", true);
				$("#"+idInput).val(' ');
			}
		},	
		complete: function(xhr, textStatus){
			jQuery.fancybox.hideLoading();
		}
	});	
}

function saveSale()
{

	var client 	= $( "select#select_client option:selected").val();
	var product = $( "select.select_product option:selected").val();
	var lista 	= $('select.select_product option:selected').toArray();

	$.each( lista, function( key, value ) {
		alert( key + ": " + value );
	});

	if(client==0)
	{
		$.fancybox('Selecciona un cliente');
		return false;	
	}

	return false;

	/*jQuery.ajax({
		url: basepath+'/index.php/registersale',
		type: 'POST',
		async: true,
		data: {
			params: $("#register-sale").serialize()
		},
		dataType: 'json',
		beforeSend: function(xhr){
			$.fancybox.showLoading();
		},
		success: function(json){
			$(".item-sale").append(json.item);
		},	
		complete: function(xhr, textStatus){
			jQuery.fancybox.hideLoading();
		}
	});
	*/

}


function registerProduct()
{
	jQuery.ajax({
		url: basepath+'/index.php/crud/product/create',
		type: 'POST',
		async: true,
		data: {
			params: $("#register-product").serializeArray()
		},
		dataType: 'json',
		beforeSend: function(xhr){
			$.fancybox.showLoading();
		},
		success: function(json){

			$( "input" ).removeClass( "error-alert" );
			$( "textarea" ).removeClass( "error-alert" );
			
			if ( !json.status ){
				$.fancybox( '<span>'+json.message+'</span>' );
				$.each( json.empty, function( key, value ) {
					$("#"+value).addClass('error-alert');
				});
			}else{
				$.fancybox( '<span>'+json.message+'</span>' );				
				top.location=basepath;
			}
		},	
		complete: function(xhr, textStatus){
			jQuery.fancybox.hideLoading();
		}
	});
}

function registerProvider()
{
	jQuery.ajax({
		url: basepath+'/index.php/crud/provider/create',
		type: 'POST',
		async: true,
		data: {
			params: $("#register-product").serializeArray()
		},
		dataType: 'json',
		beforeSend: function(xhr){
			$.fancybox.showLoading();
		},
		success: function(json){

			$( "input" ).removeClass( "error-alert" );
			$( "textarea" ).removeClass( "error-alert" );
			
			if ( !json.status ){
				$.fancybox( '<span>'+json.message+'</span>' );
				$.each( json.empty, function( key, value ) {
					$("#"+value).addClass('error-alert');
				});
			}else{
				$.fancybox( '<span>'+json.message+'</span>' );
				top.location=basepath+"/index.php/providerList";

			}
		},	
		complete: function(xhr, textStatus){
			jQuery.fancybox.hideLoading();
		}
	});
}

function crudClient(id,action)
{

	if( action == "edit"){
		top.location=basepath+'/index.php/clientEdit/'+id;
		return false;
	}

	if( action == "update" ){
		var params = $("#update-client").serializeArray();
	}else if( action == "create" ){
		var params = $("#register-client").serializeArray();
	}else{
		var params = id;
	}

	jQuery.ajax({
		url: basepath+'/index.php/crud/client/'+action,
		type: 'POST',
		async: true,
		data: {
			params: params
		},
		dataType: 'json',
		beforeSend: function(xhr){
			$.fancybox.showLoading();
		},
		success: function(json){

			if ( !json.status ){
				$.fancybox( '<span>'+json.message+'</span>' );
				$.each( json.empty, function( key, value ) {
					$("#"+value).addClass('error-alert');
				});

			}else{

				if(json.reloadPage == true ){
					jQuery.fancybox({
						modal : true,
						content : "<div style=\"margin:1px;width:240px;\">"+json.message+"<div style=\"text-align:right;margin-top:10px;\"><input onclick=\"jQuery.fancybox.close()\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Cancel\"><input onclick=\"top.location=basepath+'/index.php/clientList';\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Ok\"></div></div>"
					});
				}else{

					$("#main-tab").html(json.content);
					$.fancybox( '<span>'+json.message+'</span>' );
					top.location=basepath+"/index.php/clientList";
				}


			}
		},	
		complete: function(xhr, textStatus){
			jQuery.fancybox.hideLoading();
		}
	});

	return false;
}


function crudStock(id,action)
{

	if( action == "edit"){
		top.location=basepath+'/index.php/stockEdit/'+id;
		return false;
	}

	if( action == "update" ){
		var params = $("#update-stock").serializeArray();
	}else if( action == "create" ){
		var params = $("#register-stock").serializeArray();
	}else{
		var params = id;
	}

	jQuery.ajax({
		url: basepath+'/index.php/crud/stock/'+action,
		type: 'POST',
		async: true,
		data: {
			params: params
		},
		dataType: 'json',
		beforeSend: function(xhr){
			$.fancybox.showLoading();
		},
		success: function(json){

			if ( !json.status ){
				$.fancybox( '<span>'+json.message+'</span>' );
				$.each( json.empty, function( key, value ) {
					$("#"+value).addClass('error-alert');
				});

			}else{

				if(json.reloadPage == true ){
					jQuery.fancybox({
						modal : true,
						content : "<div style=\"margin:1px;width:240px;\">"+json.message+"<div style=\"text-align:right;margin-top:10px;\"><input onclick=\"jQuery.fancybox.close()\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Cancel\"><input onclick=\"top.location=basepath+'/index.php/stockList';\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Ok\"></div></div>"
					});
				}else{

					$("#main-tab").html(json.content);
					$.fancybox( '<span>'+json.message+'</span>' );
					top.location=basepath+"/index.php/stockList";
				}


			}
		},	
		complete: function(xhr, textStatus){
			jQuery.fancybox.hideLoading();
		}
	});

	return false;
}


function newProducto()
{
	top.location=basepath+'/index.php/product';
	return false
}

function newPrivider()
{
	top.location=basepath+'/index.php/provider';
	return false
}

function newStock()
{
	top.location=basepath+'/index.php/stock';
	return false
}

function newClient()
{
	top.location=basepath+'/index.php/client';
	return false
}


function crudProduct(id,action)
{

	if( action == "edit"){
		top.location=basepath+'/index.php/productEdit/'+id;
		return false;
	}

	if( action == "update" ){
		var params = $("#update-product").serializeArray();
	}else{
		var params = id;
	}


	jQuery.ajax({
		url: basepath+'/index.php/crud/product/'+action,
		type: 'POST',
		async: true,
		data: {
			params: params
		},
		dataType: 'json',
		beforeSend: function(xhr){
			$.fancybox.showLoading();
		},
		success: function(json){

			if ( !json.status ){
				$.fancybox( '<span>'+json.message+'</span>' );
			}else{

				if(json.reloadPage == true ){
					jQuery.fancybox({
						modal : true,
						content : "<div style=\"margin:1px;width:240px;\">"+json.message+"<div style=\"text-align:right;margin-top:10px;\"><input onclick=\"jQuery.fancybox.close()\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Cancel\"><input onclick=\"top.location=basepath;\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Ok\"></div></div>"
					});
				}else{
					$("#main-tab").html(json.content);
					$.fancybox( '<span>'+json.message+'</span>' );
				}


			}
		},	
		complete: function(xhr, textStatus){
			jQuery.fancybox.hideLoading();
		}
	});

		return false;
}

function crudProvider(id,action)
{

	if( action == "edit"){
		top.location=basepath+'/index.php/providerEdit/'+id;
		return false;
	}

	if( action == "update" ){
		var params = $("#update-provider").serializeArray();
	}else{
		var params = id;
	}

	jQuery.ajax({
		url: basepath+'/index.php/crud/provider/'+action,
		type: 'POST',
		async: true,
		data: {
			params: params
		},
		dataType: 'json',
		beforeSend: function(xhr){
			$.fancybox.showLoading();
		},
		success: function(json){

			if ( !json.status ){
				$.fancybox( '<span>'+json.message+'</span>' );
			}else{

				if(json.reloadPage == true ){
					jQuery.fancybox({
						modal : true,
						content : "<div style=\"margin:1px;width:240px;\">"+json.message+"<div style=\"text-align:right;margin-top:10px;\"><input onclick=\"jQuery.fancybox.close()\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Cancel\"><input onclick=\"top.location=basepath+'/index.php/providerList';\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Ok\"></div></div>"
					});
				}else{
					$("#main-tab").html(json.content);
					$.fancybox( '<span>'+json.message+'</span>' );
				}


			}
		},	
		complete: function(xhr, textStatus){
			jQuery.fancybox.hideLoading();
		}
	});

		return false;
}