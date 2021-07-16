$(function(){
	//correlativo de facturas
  var idSerie=document.getElementById('id_serie');
  idSerie.options[3].style.display="none";

	$('#id_serie').change(function(){
		var id_serie=$(this).val();
		if(id_serie!=0){
			$.ajax({
				method:'get',
      			url: '../getCorrelativeSale/'+id_serie,
      			success:function(data){
      				$('#id_correlativo').val(data);
      			},
      			error:function(error){
      				console.log(error);
      			}
			});
		}else {
			$('#id_correlativo').val(0);
		}
	});
	//tipo de pago
	$('#id_pago').change(function(){
		var texto=$('#id_pago option:selected').text();
    	var textoMayuscula=texto.toUpperCase();
    	if(textoMayuscula==="CRÉDITO"){
    		idSerie.options[1].style.display="none";
      		idSerie.options[2].style.display="none";
      		idSerie.options[3].style.display="block";
      		$('#id_correlativo').val(0);
      		idSerie.value=0;
    	}else {
    		idSerie.options[1].style.display="block";
      		idSerie.options[2].style.display="block";
      		idSerie.options[3].style.display="none";
      		$('#id_correlativo').val(0);
      		idSerie.value=0;
    	}
	});
	//cambio de bodegas
	$('#id_bodega').change(function(){
	    document.getElementById('id_form_bodega').submit();
	});
	// codigo de barras
	$('#id_input_search').keydown(function(event){
    	if (event.key === "Enter") {
    		var valueInput=$(this).val();
    		if(valueInput!=''){
    			var codigo_barras=document.getElementsByName('barra_'+valueInput);
	        	if(codigo_barras[0]){
	          		var id_button=codigo_barras[0].id;
	          		var button=document.getElementById(id_button);
	          		button.click();
	          		$(this).val("");
	          		$(this).focus();
	        	}else {
	          		alert("No existe el producto con ese código");
	          		$(this).val("");
	          		$(this).focus();
	        	}
    		}else {
    			alert('Ingrese el codigo de un producto');
    		}
    	}
	});

	//agregar nuevo cliente
  $("#idFormNewCustomer").submit(function(ev){ev.preventDefault();});
	$('#btnSaveCustomer').click(function(){
    var bootstrapValidator = $("#idFormNewCustomer").data('bootstrapValidator');
      bootstrapValidator.validate();
      if(bootstrapValidator.isValid()){
        $.ajax({
          type:"get",
          url:'../customers/addCustomerAjax',
          data:{
            _token: '{{csrf_token()}}',
            'nit_customer2':$('#nit_customer2').val(),
            'name_customer2':$('#name_customer2').val(),
            'dpi':$('#dpi').val(),
            'email':$('#email').val(),
            'phone':$('#phone').val(),
            'address_customer2':address_customer2.value,
          },
          success:function(data){
            if((data.errors)){
              console.log("existe un error revisar");
            }else{
              // console.log(data);
              if(data=="Ya existe un cliente con ese nombre"){
                alert("No se puede agregar ya existe un cliente con ese nombre");
                address_customer2.value="Ciudad";
              }else{
                var id=data.id;
                var name=data.name;
                document.getElementById('customer_id').value=id;
                document.getElementById('name_customer').value=name;
                $('#table_customers').append("<tr><td>" + data.id + "</td><td>" + data.nit_customer+ "</td><td>" + data.name + "</td><td></td><td></td><td><button  type='button' name='button' class='btn btn-primary btn-xs' id='name_"+data.name+"/"+data.id+"' onclick='add_customers(this);' data-dismiss='modal'><span class='glyphicon glyphicon-check' aria-hidden='true'></span></button>"+"</td></tr>");
                $("#modal-2").hide();
                $('#idFormNewCustomer').bootstrapValidator("resetForm",true);
                $('#nit_customer2').val('C/F');
                $('#address_customer2').val('Ciudad');
              }
              // $('#modal-2').hide();
            }
          }
        });
      }else {
        return;
      }
	});
  var table;
  $('#add_customer_btn').click(function(){
    // if($.fn.DataTable.isDataTable("#table_customers")){
    //   $('#table_customers').DataTable().clear().destroy();
    // }


    $.ajax({
      method:'get',
      url: '../customers/getCustomer',
      success:function(data){
        $.each(data,function(i,v){
          var row='';
          row+='<td>'+(i+1)+'</td>';
          row+='<td>'+v.nit_customer+'</td>';
          row+='<td>'+v.name+'</td>';
          row+='<td>';
          row+='<button  type="button" name="button" class="btn btn-primary btn-xs" id="name_'+v.name+'/'+v.id+'" onclick="add_customers(this);" data-dismiss="modal">';
          row+='<span class="glyphicon glyphicon-check" aria-hidden="true"></span>';
          row+='</button>';
          $('<tr>').html(row).appendTo('#table_customers tbody');
        });

      },
      error:function(error){
        console.log(error);
      }
    });
      $.extend( $.fn.dataTable.defaults, {
          responsive: true
      });

    $('#table_customers').DataTable();
    //   $('#table_customers').DataTable({
    //   responsive: true,
    //   destroy: true,
    // });
    //inicializacion de tabla

    jQuery.noConflict();
    $('#modal-1').modal('show');

  });
});//fin function principal


// agregar cliente
function add_customers(idElemento){
	var customer_id=document.getElementById('customer_id');
    var name_customer=document.getElementById('name_customer');
    var name=idElemento.id.split("_");
    var name_id=name[1].split("/");
  	customer_id.value=name_id[1];
	name_customer.value=name_id[0];
}
