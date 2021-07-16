var fecha= new Date();
var diaMes=fecha.getDate();
var mes=fecha.getMonth();
var mesReal=parseInt(mes)+1;
var anio=fecha.getFullYear();
var visualizarFecha=document.getElementById('admited_at');
visualizarFecha.value=diaMes+'/'+mesReal+'/'+anio;
var totalFactura=document.getElementById('totalFactura');
var totalInteres=document.getElementById('totalInteres');
var totalCredito=document.getElementById('totalCredito');
totalCredito.value=totalFactura.value;
var montoTotal=document.getElementById('montoCredito');
var nombreCliente=document.getElementById('nombreCliente');
var totalEganche=document.getElementById('totalEganche');
var loor=document.getElementById('generarPagos');
var fechaNueva=document.getElementById('admited_at');
totalEganche.value=0;
var validarMontoCredito=function(e){
    var totalFactura2=document.getElementById('totalFactura');
    totalCredito.value=((totalFactura.value*totalInteres.value)/100) + parseFloat(totalFactura.value);
    e.preventDefault();
}
var agregarTexto=document.getElementById('tablaId');

var opcionElegida=function(e)
{

    /*LIMPIAR LA TABLA*/
    EliminarFilas();
    /*----------------*/
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'GTQ',
        minimumFractionDigits: 2,
    });

    var pago=totalCredito.value/montoTotal.value;
    var pagoMensual=parseFloat(pago);
    var pagoMensualRedondeado=parseFloat((pagoMensual).toFixed(2));
    var total_redondeado=Math.round(pagoMensualRedondeado);
    // var pagoMensualRedondeado1=formatter.format(total_redondeado);

            //obtenemos la fecha que el usuario tiene
            var fechaNueva=document.getElementById('admited_at');
            var arregloFecha2=fechaNueva.value.split("/");
            var diaMes_nuevo=arregloFecha2[0];
            var mes_nuevo=arregloFecha2[1];
            var anio_nuevo=arregloFecha2[2];
            //Insertamos los valores del crédito en la tabla.
            var mes_insertar=mes_nuevo;
            var mes_insertar2;
            var ni=0;
            for (var i=0; i< montoTotal.value; i++) {
                mes_insertar2=parseInt(mes_insertar)+i;
                var stringHTML='';
                stringHTML+='<tr>';
                stringHTML+='<td style="text-align: center;">'+(i+1)+'</td>';
                stringHTML+='<td style="text-align:right">Q '+monedaChange(total_redondeado)+'</td>';
                var mesReal=0;
                 if((parseInt(mes_insertar2))>=13){
                     mes_insertar2=ni+1;
                     mesReal = diaMes_nuevo+'/'+mes_insertar2+'/'+(parseInt(anio_nuevo)+1);
                     ni++;
                 }else {
                   mesReal = diaMes_nuevo+'/'+mes_insertar2+'/'+anio_nuevo
                 }
                stringHTML+='<td style="text-align: center;">'+mesReal+'</td>';
                stringHTML+='</tr>';
                $('#tablaId').append(stringHTML);
            }
            e.preventDefault();
        }//termina el agregar las fechas de pagos

        var EliminarFilas=function(e)
        {
                var quantity_rows=$("#tablaId tr").length;
                if(quantity_rows>1){
                    for(var i=quantity_rows ;i>1 ;i--){
                      $("#tablaId tr:last").remove();
                  }
              }
              loor.disabled=false;
          }
          var alertaDeFecha=function(){
            var fechaNueva=document.getElementById('admited_at');
            var arregloFecha2=fechaNueva.value.split("/");
            var diaMes_nuevo=arregloFecha2[0];
            var mes_nuevo=arregloFecha2[1];
            var anio_nuevo=arregloFecha2[2];
        }
        var restarEnganche=function(e){
            var nuevoMontoCredito=totalCredito.value=((totalFactura.value*totalInteres.value)/100) + parseFloat(totalFactura.value);
            totalCredito.value=parseFloat(nuevoMontoCredito) - totalEganche.value;
            e.preventDefault();
        }


        loor.addEventListener('click',opcionElegida);
        montoTotal.addEventListener('click',EliminarFilas);
        fechaNueva.addEventListener('click',EliminarFilas);
        totalInteres.addEventListener('blur',validarMontoCredito);
        totalInteres.addEventListener('click',EliminarFilas);
        totalCredito.addEventListener('click',EliminarFilas);
        totalEganche.addEventListener('blur',restarEnganche);
        totalInteres.addEventListener('change',function(){
            totalEganche.value=0;
        });
    ///boton de guardar
    document.getElementById('btnSaveForms').addEventListener('click',function() {
        var formulario = document.getElementById('formSaveCredit');
        formulario.addEventListener("submit", functionValidate, true);
    });
    ///boton de guardar
    ///validaciones
    var functionValidate= function (event){
        if(parseInt(document.getElementById('montoCredito').value)==0){
            document.getElementById('montoCredito').focus();
            event.preventDefault();
        }
        var rows=document.getElementById('tablaId').rows.length;
        // console.log(rows);
        if(rows<2){
            alert("Debe de generar los pagos");
            event.preventDefault();
        }

    }


    function monedaChange(cantidad,cif = 3, dec = 2) {
    // tomamos el valor que tiene el input
    let inputNum =cantidad;
    inputNum = inputNum.toString()
    inputNum = inputNum.split('.')
    if (!inputNum[1]) {
        inputNum[1] = '00'
    }
    let separados
    if (inputNum[0].length > cif) {
        let uno = inputNum[0].length % cif
        if (uno === 0) {
            separados = []
        } else {
            separados = [inputNum[0].substring(0, uno)]
        }
        let posiciones = parseInt(inputNum[0].length / cif)
        for (let i = 0; i < posiciones; i++) {
            let pos = ((i * cif) + uno)
            separados.push(inputNum[0].substring(pos, (pos + 3)))
        }
    } else {
        separados = [inputNum[0]]
    }
    return valorTotalFormateado =  separados.join(',') + '.' + inputNum[1];
}
