$(function(){
    $('body').on("keypress keyup blur","input.inputQt",function(){
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)  && event.which != 8 && (event.which < 37 || event.which > 40 )) {
            event.preventDefault();
        }
	});
	//para obtener el correlativo
    $('#id_serie').change(function(){
    	if($(this).val()=="" || $(this).val()==0){
    		$('#id_correlativo').val(0);
    	}else{
    		var url='../getCorrelativeSale/'+$(this).val();
    		$.ajax({
    			type:'get',
    			url:url,
    			success:function(data){
    				$('#id_correlativo').val(data);
    			},
    			error:function(error){
    				console.log(error);
    			}
    		});
    	}
	});
	//para agregar clientes
	$('body').on('click','.addCustomer',function(){
		var stringId=$(this)[0].id;
		var arrayCustomer=stringId.split('_');
		$('#customer_id').val(arrayCustomer[2]);
		$('#name_customer').val(arrayCustomer[1]);
	});
	//id del formulario id_save_sales
	$('#idVenta').click(function(){
		if($(this).val()==0){
			$('#correlativo_num').val(0);
		}else{
			var url='../getCorrelativeSale/'+$(this).val();
			$.ajax({
				type:'get',
				url:url,
				success:function(data){
					$('#correlativo_num').val(parseInt(data));
				},
				error:function(err){
					console.log(err);
				}
			});
		}
	});
	//Agregando el nombre del proveedor 
	$('body').on('click','.addCustomer',function(){
		var stringId=$(this)[0].id;
		var arrayCustomer=stringId.split('_');
		$('#customer_id').val(arrayCustomer[2]);
		$('#customer_name').val(arrayCustomer[1]);
	});
	//buton de guardar
	//validaciones 
	$('#btnVenta').click(function(){
		alert('asdfasdf');
		// var divButton=document.getElementById('divButton');
		// var divLoading=document.getElementById('divLoading');
		// alert('estamos haciendo click');
		if($('#id_correlativo').val()==0){
			alertify.error('Seleccione una serie');
			$('#id_serie').focus();
		// }else if($('#qt_items').val()==0){
		// 	alertify.error('Seleccione productos');

		// 	$('#id_input_search').focus();
		// }else if($('#comentario').val()==0){
		// 	$('#comentario').focus();
		// 	alertify.error('Escriba un comentario');
		} else {
			alert('asdfasdf');
			// // document.getElementById('add_supplier_btsn').click();
			// divButton.style.display='none';
			// divLoading.style.display='block';
			// $.ajax({
			// 	type:'get',
			// 	url:'../existCorrelative/?id_serie='+$('#serie_id').val()+'&correlative='+$('#correlativo_num').val(),
			// 	success:function(data){
			// 		console.log(data);
			// 		if(data==0){
			// 			document.getElementById('save_receivings').submit();
			// 		}else{
			// 			alertify.error('Ya se utilizo ese correlativo');
			// 			$('#correlativo_num').focus();
			// 			divButton.style.display='block';
			// 			divLoading.style.display='none';
			// 		}
			// 	},
			// 	error:function(err){
			// 		console.log(err);
			// 	}
			// });
		}
	});
});