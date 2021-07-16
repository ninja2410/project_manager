$(function(){
	$('#table_suppliers').DataTable({
		"bLengthChange": false,
		"bInfo": false,
		"bAutoWidth": false,
		language: {
			search: "_INPUT_",
			searchPlaceholder: "Buscar...",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
		},
		"pageLength": 6,
		"columnDefs": [{
			"visible": false,
			"searchable": false
		}]
	});
	$('#date').datetimepicker({
		date:moment(),
		sideBySide:true,
		locale:'es',
		format:'DD/MM/YYYY HH:mm:ss'
	}).parent().css('position:relative');

	$("body").on("keypress keyup blur","input.inputQt",function(event){
		// $(this).val($(this).val().replace(/[^\d]+/, ""));
		if ((event.which < 45 || event.which > 57)  && event.which != 8 && (event.which < 37 || event.which > 40 )) {
			event.preventDefault();
		}
	});
	//verificacion de eliminacion o cuando se pone cero
	$('body').on('blur','input.inputQt',function(){
		if($(this).val()==0 || $(this).val()==1){
			$(this).val(1);
		}
	});
	//verificacion de correlativo
	$('#serie_id').change(function(){
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
		var divButton=document.getElementById('divButton');
		var divLoading=document.getElementById('divLoading');
		// alert('estamos haciendo click');
		if($('#correlativo_num').val()==0){
			alertify.error('Seleccione una serie');
			$('#serie_id').focus();
		// }else if($('#customer_id').val()==0){
		// 	document.getElementById('add_supplier_btn').click();
		}else if($('#id_bodega').val()==0 || $('#id_bodega').val()=='? number:0 ?'){
			alertify.error('Seleccione bodega');
			$('#id_bodega').focus();
		}else if($('#date').val()==""){
			$('#date').focus();
			alertify.error('Seleccione fecha');
		}else if($('#qt_items').val()==0){
			alertify.error('Seleccione productos');

			$('#id_input_search').focus();
		}else if($('#comentario').val()==0){
			$('#comentario').focus();
			alertify.error('Escriba un comentario');
		} else {
			// document.getElementById('add_supplier_btsn').click();
			divButton.style.display='none';
			divLoading.style.display='block';
			$.ajax({
				type:'get',
				url:APP_URL+'/verifyCorrelative/sales/'+$('#serie_id').val()+'/'+$('#correlativo_num').val(),
				success:function(data){
					console.log(data);
					if(data==0){
						document.getElementById('save_receivings').submit();
					}else{
						alertify.error('Ya se utilizo ese correlativo');
						$('#correlativo_num').focus();
						divButton.style.display='block';
						divLoading.style.display='none';
					}
				},
				error:function(err){
					console.log(err);
				}
			});
		}
	});
	//bodegas
	$('#id_bodega').change(function(){
		if($(this).val()==0){
			$('#bodega_id').val(0);
		}else{
			$('#bodega_id').val($(this).val());
		}
	});
	$("#save_receivings").submit(function(ev){ev.preventDefault();});

});
