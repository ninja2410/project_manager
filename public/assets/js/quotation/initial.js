$(function(){
    $("body").on("keypress keyup blur","input.inputQt",function(event){
		$(this).val($(this).val().replace(/[^\d].+/, ""));
		if ((event.which < 48 || event.which > 57)  && event.which != 8 && (event.which < 37 || event.which > 40 )) {
			event.preventDefault();
		}
    });
    //agregando proveedor
    $('body').on('click','.addSupplier',function(){
		var element=$(this)[0].id;
		var array1=element.split('_');
		var array2=array1[1].split('/');
		$('#supplier_id').val(array2[1]);
		$('#supplier_name').val(array2[0]);
	});
    //submit
    $('#btnVenta').click(function(){
        if($('#id_serie').val()==0){
            alertify.error('Seleccione una serie');
            $('#id_serie').focus();
        }else if($('#itemsQuantity').val()==0){
            alertify.error('Agregue productos');
            $('#id_input_search').focus();
        }else if($('#id_bodega').val()==0){
            alertify.error('Seleccione una bodega');
            $('#id_bodega').focus();
        }else if($('#supplier_id').val()==0){
            alertify.error('Seleccione un proveedor');
            $('#add_supplier_btn').click();
        }else if($('#id_correlativo').val()==0 || $('#id_correlativo').val()==''){
            alertify.error('Escriba un correlativo');
            $('#id_correlativo').focus();
        }else{
            document.getElementById('save_receivings').submit();
        }
    });
    //correlativo 
    $('#id_serie').change(function(){
        if($(this).val()==0){
            $('#id_correlativo').val(0);
        }else{
            var url='../api/1/'+$(this).val()+'/item/';
			$.ajax({
				type:'get',
				url:url,
				success:function(data){
					if(data==""){
						$('#id_correlativo').val(1);
					}else{
						$('#id_correlativo').val(parseInt(data)+1);
					}
				},
				error:function(err){
					console.log(err);
				}
			});
        }
    });
    //evitamos el submit
    $("#save_receivings").submit(function(ev){ev.preventDefault();});

});