
// document.getElementById('total_pago').value=0;
// document.getElementById('name_customer').value="";
//cambio de vendedor

var result=document.getElementById('id_bodega');
var id_correlativo=document.getElementById('id_correlativo');

function addDays(date, days) {
    const copy = new Date(Number(date))
    copy.setDate(date.getDate() + days)
    return copy
}

function appendLeadingZeroes(n){
    if(n <= 9){
        return "0" + n;
    }
    return n
}



$('#id_serie').change(function(){
    var id_serie=$(this).val();
    if(id_serie!=0){
        $.ajax({
            method:'get',
            url:APP_URL+'/getCorrelativeSale/'+id_serie,
            success:function(data){
                $('#id_correlativo').val(data);
                /**
                * Cuando cambie de serie/documento
                * Modificar la descripción del pago
                * */
                var doc_texto = $( "#id_serie option:selected" ).text();
                $('#description').val('Pago: '+doc_texto+' '+data);
            },
            error:function(error){
                console.log(error);
            }
        });
    }else {
        $('#id_correlativo').val(0);
    }
});

$( "#customer_id" ).change(function() {
    var customer_credit = cleanNumber($(this).children(':selected').attr('max_credit_amount'));
    var days_credit = cleanNumber($(this).children(':selected').attr('days_credit'));
    console.log('credito '+customer_credit);
    $( "#max_credit_amount").val(customer_credit);
    $( "#days_credit").val(days_credit);
    console.log('Cust Change - dias credito '+days_credit);



    $( "#customer_credit").val(customer_credit);
    $( "#customer_balance").val(customer_credit);
    // $("#id_pago").val(0);
    // $("#id_pago").trigger('change');
    // console.log(' credito2 '+this.options[this.selectedIndex].getAttribute('max_credit_amount'));
});

function valida(e){
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8){
        return true;
    }
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

//boton de verificación de vender
var idVenta=document.getElementById('idVenta');
idVenta.addEventListener('click',function(){
    this.style.display='none';
    /* Mostrar modal */
    $('body').loadingModal({
        text: 'Procesando...'
    });
    $('body').loadingModal('show');
    var select_serie = document.getElementById("id_serie");
    var customer_id=document.getElementById('customer_id');
    var item_quantity=document.getElementById('item_quantity');
    var user_relation=document.getElementById('user_relation');
    // var cmbPago=document.getElementById('id_pago');
    // console.log('cliente '+cleanNumber(customer_id.value));

    selected = select_serie.value;
    if(selected==0)
    {
        select_serie.focus();
        toastr.error("Seleccione serie de documento");
        this.style.display='inline';
        $('body').loadingModal('hide');
    }
    else if( (cleanNumber(customer_id.value)==0) || (customer_id.value=="")) {
        customer_id.focus();
        toastr.error("Seleccione o cree un cliente para la venta");
        this.style.display='inline';
        $('body').loadingModal('hide');
    }
    else if(cleanNumber(user_relation.value)==0){
        toastr.error("Seleccione vendedor");
        user_relation.focus();
        this.style.display='inline';
        $('body').loadingModal('hide');
    }
    else if(cleanNumber(item_quantity.value)==0)
    {
        toastr.error("Debe agregar productos a la venta");
        $('#codigo').focus();
        this.style.display='inline';
        $('body').loadingModal('hide');
        // document.getElementById('id_input_search').focus();
    }
    else if (cleanNumber($( "#max_desc_vta").val())<cleanNumber($( "#discount_pct").val()))
    {
        toastr.error("% de descuento máximo: "+$( "#max_desc_vta").val());
        this.style.display='inline';
        $('body').loadingModal('hide');
        $( "#discount_pct").focus();
    }
    else
    {
        $.get(APP_URL+'/verifyCorrelative?_token='+$('#token_').val()+"&id_serie="+$('#id_serie').val()+"&correlative="+$('#id_correlativo').val(), function(data) {
                if(data==0){
                    /* Si paso las validaciones del tab de venta y
                    * si el correlativo no ha sido utilizado
                    * mostramos la sección de pagos */
                    $('#tab_pago').show(); /*Hacemos visible el tab de pagos, que estaba oculto */
                    $('#link_pago').click(); /*Activamos el tab de pagos */

                    if(form_is_valid())
                    {
                        $('body').loadingModal('hide');
                        $('#idVenta').show()
                        // $("#modal-2").hide();
                        console.log('Levantar modal');
                        //CONSTRUIR EL DETALLE json
                        buildJsonDetails();
                        $('#confirmSale').modal('show');
                        // alert('guardar');
                    }
                    else {
                        $('body').loadingModal('hide');
                        $('#idVenta').show();/*Hacemos visible el boton*/
                        console.log('formulario INValido');
                    }
                } else {
                    console.log(data);
                    toastr.error("El correlativo ya ha sido utilizado en otra factura", "Error");
                    document.getElementById('idVenta').style.display='inline';
                    hideLoading();
                    $('#id_correlativo').focus();
                }
            });
        //
        // $.post(APP_URL+'/existCorrelative',
        //     {
        //         _token: $('#token_').val(),
        //         'id_serie':$('#id_serie').val(),
        //         'correlative':$('#id_correlativo').val()
        //     })
        //     .done( function(data) {
        //         if(data==0){
        //             /* Si paso las validaciones del tab de venta y
        //             * si el correlativo no ha sido utilizado
        //             * mostramos la sección de pagos */
        //             $('#tab_pago').show(); /*Hacemos visible el tab de pagos, que estaba oculto */
        //             $('#link_pago').click(); /*Activamos el tab de pagos */

        //             if(form_is_valid())
        //             {
        //                 $('body').loadingModal('hide');
        //                 $('#idVenta').show()
        //                 // $("#modal-2").hide();
        //                 console.log('Levantar modal')
        //                 $('#confirmSale').modal('show');
        //                 // alert('guardar');
        //             }
        //             else {
        //                 $('body').loadingModal('hide');
        //                 $('#idVenta').show();/*Hacemos visible el boton*/
        //                 console.log('formulario INValido');
        //             }
        //         } else {
        //             toastr.error("El correlativo ya ha sido utilizado en otra factura", "Error");
        //             document.getElementById('idVenta').style.display='inline';
        //             hideLoading();
        //             $('#id_correlativo').focus();
        //         }
        //     })
        //     .fail( function (error) {
        //         console.log(error);
        //     });

        // $.ajax({
        //     type:"post",
        //     url:APP_URL+'/existCorrelative',
        //     data:{
        //         _token: $('#token_').val(),
        //         'id_serie':$('#id_serie').val(),
        //         'correlative':$('#id_correlativo').val()
        //     },
        //     success:function(data){
        //         if(data==0){
        //             /* Si paso las validaciones del tab de venta y
        //             * si el correlativo no ha sido utilizado
        //             * mostramos la sección de pagos */
        //             $('#tab_pago').show(); /*Hacemos visible el tab de pagos, que estaba oculto */
        //             $('#link_pago').click(); /*Activamos el tab de pagos */

        //             if(form_is_valid())
        //             {
        //                 $('body').loadingModal('hide');
        //                 $('#idVenta').show()
        //                 // $("#modal-2").hide();
        //                 console.log('Levantar modal')
        //                 $('#confirmSale').modal('show');
        //                 // alert('guardar');
        //             }
        //             else {
        //                 $('body').loadingModal('hide');
        //                 $('#idVenta').show();/*Hacemos visible el boton*/
        //                 console.log('formulario INValido');
        //             }
        //         } else {
        //             toastr.error("El correlativo ya ha sido utilizado en otra factura", "Error");
        //             document.getElementById('idVenta').style.display='inline';
        //             hideLoading();
        //             $('#id_correlativo').focus();
        //         }
        //     },
        //     error:function(error){
        //         console.log(error);
        //     }
        // });
    }
});

function buildJsonDetails(){
  let detalles = document.getElementsByClassName("_rowItem");
  let tmpItems = [];
  for (let row of detalles){
      let producto = row.getElementsByClassName("_itemId")[0].value;
      let cantidad = cleanNumber(row.getElementsByClassName("_itemQuantity")[0].value);
      let unidad = row.getElementsByClassName("unit_option")[0].value;
      let precio = cleanNumber(row.getElementsByClassName("_itemPrice")[0].value);
      tmpItems.push({
        "item_id":producto,
        "quantity":cantidad,
        "unit_id":unidad,
        "price":precio
      });
  }
  $('#new_details').val(JSON.stringify(tmpItems));
}

function sendForm(){
    $("#confirmSale").modal('hide');
    $('body').loadingModal({
        text: 'Guardando factura...'
    });
    // $('body').loadingModal('show');
    // sleep(1000);
    // sendForm();
    $.ajax({
        type:"post",
        url:APP_URL+'/sales',
        data:{
            _token: $('#token_').val(),
            'venta':$('#id_save_sales').serialize(),
            'pago': $('#frm_payment').serialize()
        },
        success:function(data_){
            var json = JSON.parse(data_);
            if (json.flag!=1) {
                $('body').loadingModal('destroy');
                toastr.error(json.custMessage);
                console.log("Mensaje dev: "+json.mensaje)
            }
            else{
                toastr.success("Venta realizada con exito!");
                location.href = APP_URL+'/'+json.url;
            }
        },
        error:function(error){
            console.log(error);
        }
    });
}

function form_is_valid(){

    var cmbPago=document.getElementById('id_pago');
    if(cleanNumber(cmbPago.value)==0)
    {
        toastr.error("Debe seleccionar una forma de pago");
        cmbPago.focus();
        return false;
        // this.style.display='inline';
        // $('body').loadingModal('hide');
        // document.getElementById('id_input_search').focus();
    }

    var paymen_type = cleanNumber($('#id_pago').children(':selected').attr('type'));
    if ((cleanNumber($( "#max_credit_amount").val())<cleanNumber($( "#total_general").val())) && (paymen_type==6))
    {

        toastr.error("Crédito insuficiente: Q "+$( "#max_credit_amount").val()+" / Monto: Q "+$( "#total_general").val());
        cmbPago.focus();
        return false;
        // this.style.display='inline';
        // $('body').loadingModal('hide');
    }


    if($('#account_id').hasClass('validation-cacao') && !$('#account_id').val())
    {
        account = document.getElementById('account_id')
        toastr.error("Debe seleccionar una cuenta");
        account.focus();
        return false;
    }
    if($('#paid').hasClass('validation-cacao'))
    {
        if (!$('#paid').val()) {
            $('#paid').focus();
            toastr.error("Debe ingresar el monto pagado");
            return false;
        }
        // console.log(' paid '+ parseFloat($('#paid').val()));
        console.log(' amount '+ parseFloat($('#amount').val()));

        if(cleanNumber($('#paid').val())<cleanNumber($('#amount').val()))
        {
            $('#paid').focus();
            toastr.error("Monto pagado debe ser mayor o igual al monto a pagar.");
            return false;
        }

    }

    if($('#description').hasClass('validation-cacao') && !$('#description').val())
    {
        $('#description').focus();
        toastr.error("Debe ingresar una descripciǿn");
        return false;
    }

    if($('#reference').hasClass('validation-cacao'))
    {
        var largo =$('#reference').val().length;
        if(!$('#reference').val() || largo<1){
            $('#reference').focus();
            toastr.error("Debe ingresar el número de cheque/transacción");
            return false;
        }
    }
    if($('#bank_name').hasClass('validation-cacao') && !$('#bank_name').val())
    {
        $('#bank_name').focus();
        toastr.error("Debe ingresar el nombre del banco");
        return false;
    }
    if($('#same_bank').hasClass('validation-cacao') && !$('#same_bank').val())
    {
        $('#same_bank').focus();
        toastr.error("Debe indicar si el cheque es del mismo banco o no ");
        return false;
    }


    if($('#card_name').hasClass('validation-cacao') )
    {
        var largo =$('#card_name').val().length;
        if (!$('#card_name').val() || largo<4) {
            $('#card_name').focus();
            toastr.error("Debe ingresar el nombre en la tarjeta");
            return false;
        }
    }
    if($('#card_number').hasClass('validation-cacao'))
    {
        var card_number = $('#card_number').val();
        var largo =card_number.length;
        console.log(' validation_news.js - card_number '+card_number+' largo '+largo);
        if(!$('#card_number').val() || largo<4 || largo>4){
            $('#card_number').focus();
            toastr.error("Debe ingresar los últimos 4 dígitos de la tarjeta");
            return false;
        }
    }
    if($('#date_payments').hasClass('validation-cacao') )
    {

        var fecha_pago = $('#date_payments').val();
        var arrStartDate = fecha_pago.split("/");
        var date_pago = new Date(arrStartDate[2], arrStartDate[1]-1, arrStartDate[0]);

        var fecha_factura =$('#date_tx').val();
        var arrEndDate = fecha_factura.split("/");
        var date_factura = new Date(arrEndDate[2], arrEndDate[1]-1, arrEndDate[0]);

        var days_credit = cleanNumber($('#days_credit').val());

        var tmp1 = new Date(arrEndDate[2], arrEndDate[1]-1, arrEndDate[0]);
        const reference_date = addDays(tmp1,cleanNumber(days_credit));

        // console.log(' fecha sumada otra '+reference_date);
        // console.log(' fecha pago '+date_pago+' fecha factura '+date_factura+' fecha deberia pagar '+reference_date);
        if (fecha_pago=="") {
            toastr.error("Debe ingresar la fecha de pago");
            $('#date_payments').focus();
            return false;
        } else if (date_factura>=date_pago) {
            // $('#date_payments').focus();
            toastr.error("La fecha de pago debe ser mayor a la fecha de la factura");
            $('#date_payments').focus();
            return false;
        } else if (date_pago>reference_date){
            let formatted_date = appendLeadingZeroes(reference_date.getDate())+"/"+appendLeadingZeroes(reference_date.getMonth() + 1) + "/" +reference_date.getFullYear() ;
            toastr.error("La fecha de pago no debe exceder el límite de días de crédito ("+days_credit+") para el cliente seleccionado ["+formatted_date+"].");
            return false;
        }

    }

    if($('#customer_credit').hasClass('validation-cacao'))
    {
        if (!$('#customer_credit').val()) {
            toastr.error("Cliente debe tener crédito autorizado");
            return false;
        }
        console.log(' customer_credit '+ parseFloat($('#customer_credit').val()));
        console.log(' total_general '+ parseFloat($('#total_general').val()));

        if(cleanNumber($('#customer_credit').val())<parseFloat($('#total_general').val()))
        {
            $('#customer_credit').focus();
            toastr.error("Crédito autorizado ( Q. "+cleanNumber($('#customer_credit').val())+") debe ser mayor o igual al monto a pagar (Q. "+parseFloat($('#total_general').val())+").");
            return false;
        }

    }


    return true;
}



function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
