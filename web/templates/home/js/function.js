function registerProduct()
{
	//var params = $("#register-product").serializeArray();
	jQuery.ajax({
		url: basepath+'/index.php/producto/create',
		type: 'POST',
		async: true,
		data: {
			params: $("#register-product").serializeArray()
		},
		dataType: 'json',
		beforeSend: function(xhr){
			//jQuery.fancybox.showLoading();
		},
		success: function(json){
		},
		complete: function(xhr, textStatus){
			//jQuery.fancybox.hideLoading();
		}
	});


}