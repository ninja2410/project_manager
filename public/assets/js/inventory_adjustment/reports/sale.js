$(function(){
	//
	$('#documentos').change(function(){
		clearDataTable();
		if($(this).val()>0){
			var url='../getSeriesAdd/'+$(this).val();
			$('#id_serie').empty();
			$('#id_serie').append('<option value="">Seleccione serie</option>');      
			$.ajax({
				type:'get',
				url:url,
				success:function(data){
					$.each(data,function(index,serie){
						$('#id_serie').append($('<option>', { value : serie.id_serie }).text(serie.serie));                        
					});
				},
				error:function(error){
					console.log(error);
				}
			});              
		}
	});
	$('#generate').click(function(){
		clearDataTable();
		var form=$('#formData').serialize();
		$.ajax({
			type:'get',
			url:'../getReportSale/?'+form,
			success:function(data){
				$.each(data,function(index,response){
					var row='';
					row+='<td style="text-align:center;">'+(index+1)+'</td>';
					row+='<td>'+response.document_and_correlative+'</td>';
					var date=response.creation_date;
					var arrayDate=date.split('-');
					var time=arrayDate[2].split(' ');
					row+='<td>'+time[0]+'/'+arrayDate[1]+'/'+arrayDate[0]+' '+time[1]+'</td>';
					row+='<td>'+response.user_name+'</td>';
					row+='<td>';
					var customerName='';
					if(response.customer_name){
						customerName=response.customer_name;
					}else {
						customerName='N/A';
					}
					row+=customerName;
					row+='</td>';
					row+='<td style="text-align:center;">';
					row+='<a href="detailsSale/'+response.id_sales+'/?return=true"  class="btn btn-info" title="Re-imprimir">';
					row+='<span class="glyphicon glyphicon-print"></span> </a>';
					row+='<br /><br />';
					row+='<button title="Detalle" id="'+response.id_sales+'"  class="btn btn-primary reportBtn"  data-toggle="modal" data-target="#modalDetails" >';
					row+='<span class="glyphicon glyphicon-th-list"></span>';
					row+='</button>';
					row+='</td>';
					$('<tr>').html(row).appendTo('#table1 tbody');
				});
			},
			error:function(error){
				alert(error);
			}
		});
	});
     //prevenimos el form 
     $('#formData').submit(function(event){
     	event.preventDefault();
     });
        //seleccionar series
        $('#id_serie').change(function(){
        	clearDataTable();
        });
        function clearDataTable(){
        	var quantity_rows=$("#table1 tr").length;
            //se borrar la tabla si tiene contenido
            if(quantity_rows>1){
            	for(var i=quantity_rows ;i>1 ;i--){
            		$("#table1 tr:last").remove();
            	}
            }
          }
    //para obtener el detalle 
    $("body").on('click','.reportBtn',function(){
    	var id=$(this)[0].id;
    	clearModal();
    	$.ajax({
    		type:'get',
    		url:'../saleHeaderAndDetails/'+id,
    		success:function(data){
    			var date=data[0][0].creation_date;
    			var arrayDate=date.split('-');
    			var time=arrayDate[2].split(' ');
    			$('#lblDate').text(time[0]+'/'+arrayDate[1]+'/'+arrayDate[0]+' '+time[1]);
    			$('#lblUser').text(data[0][0].user_name);
    			$('#lblStorage').text(data[1][0].storage_name);
    			$('#lblDocument').text(data[0][0].document_and_correlative);
    			if(data[0][0].customer_name){
    				$('#lblCustomer').text(data[0][0].customer_name);
    			}else{
    				$('#lblCustomer').text("N/A");
    			}
    			$('#idComment').val(data[0][0].comments);
    			var arrayDetails=data[1];
    			$.each(arrayDetails,function(index,items){
    				var row='';
    				row+='<td style="text-align:center;">';
    				row+=(index+1);
    				row+='</td>';
    				row+='<td>';
    				row+=items.item_name;
    				row+='</td>';
    				row+='<td style="text-align:center;">';
    				row+=items.quantity;
    				row+='</td>';
    				$('<tr>').html(row).appendTo('#tableDetails_table tbody');
    			});
    		},
    		error:function(error){
    			console.log(error);
    		}
    	});
    });
    function clearModal(){
    	$('#lblDate').text("");
    	$('#lblUser').text("");
    	$('#lblStorage').text("");
    	$('#lblDocument').text("");
    	$('#lblCustomer').text("");
    	$('#idComment').text("");
    	var quantity_rows=$("#tableDetails_table tr").length;
    	if(quantity_rows>1){
    		for(var i=quantity_rows ;i>1 ;i--){
    			$("#tableDetails_table tr:last").remove();
    		}
    	}
    }
});