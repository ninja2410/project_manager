var contador=0;
 document.getElementById("item_quantity").value=contador;
  var total_temp=0;
var rol_user = document.getElementById('role_user');
  function add(button)
  {
      var id_array=button.id;
      var id_item_separed = id_array.split("_");
      var id_item = id_item_separed[1];

      var row=button.parentNode.parentNode;
      var cells = row.querySelectorAll('td:not(:last-of-type)');
      var quantity=document.getElementById("quantity_" + id_item);
      var existencias = document.getElementById('existencias_'+id_item).value;
      var tipo = document.getElementById('type_'+id_item).value;
      var stock_action = document.getElementById('stock_action'+id_item).value;
      // console.log(precio);
      if (quantity)
      {
          if (parseInt(existencias)>0 || tipo == 2 || stock_action=='=')
          {
              getDetailsKit(id_item, 1, 1, cells, id_item, 0, false);
          }
          else
          {
              toastr.error("No hay suficientes existencias.");
          }
      }
      else
      {
          contador++;
          if(contador<= 9)
          {
            if (parseInt(existencias)>0 || tipo == 2 || stock_action=='=') {
              getDetailsKit(id_item, 1, 1, cells, id_item, 1, false);
            }
            else{
              toastr.error("No hay suficientes existencias.");
            }
          }
          else
          {
              toastr.error("No se pueden agregar más de 9 artículos.");
              contador--;
          }
      }

  }
  function addItemLine(cells, id_item){
    var precio = document.getElementById('precio_'+id_item);
    addToCartTable(cells, id_item);
    document.getElementById("item_quantity").value=contador;
    var totalpago = document.getElementById('total_pago');
    var totalSinFormato = replaceAll(totalpago.value,",","");
  //   console.log("Precio total "precio.value);
  //   var newtotal = monedaChange(parseFloat(totalpago.value) + (parseFloat(precio.value) * 1));
    totalpago.value = monedaChange(parseFloat(totalSinFormato)+parseFloat(precio.value)*1);
  }
  function getDetailsKit(kit, action, quantity, cells, id_item, add, updt){
    var x=0;
    var tmp=0;
    $.ajax({
      method:'get',
      url: APP_URL+'/getDetails/'+kit,
      success:function(data){
        if (data==-1) {
          if (action==1) {
            tmp=$('#existencias_'+kit).val();
            $('#existencias_'+kit).val(parseInt(tmp)-parseInt(quantity));
          }
          if (action==2) {
            tmp=$('#existencias_'+kit).val();
            $('#existencias_'+kit).val(parseInt(tmp)+parseInt(quantity));
          }
          $('#canAdd').val(1);
          if (add==1) {
            addItemLine(cells, id_item);
          }
          else{
            if (action==1) {
              var q=document.getElementById("quantity_" + id_item);
              if (!updt) {
                  q.value = parseInt(q.value)+1;
              }
              q.defaultValue=q.value;
              changeSubInputQuantity(id_item, q.value);
            }
          }
        }
        else{
          $.each(data,function(i,v){
            //VERIFICAR SI SE CUENTAN CON EXISTENCIAS SUFICIENTES
            tmp=$('#existencias_'+v.item_id).val();
            if (!tmp) {
              x++;
            }
            if (tmp<(v.quantity*quantity)&&action==1) {
              x++;
            }
          });
          if (x==0) {
            $('#canAdd').val(1);
            $.each(data,function(i,v){
              //SE SABE CUANTO NECESITA DE CADA ITEM
              tmp=$('#existencias_'+v.item_id).val();
              if (action==1) {
                $('#existencias_'+v.item_id).val(tmp-(v.quantity*quantity));
              }
              if(action==2){
                $('#existencias_'+v.item_id).val(parseInt(tmp)+(parseInt(v.quantity)*quantity));
              }
              $('#ex_'+v.item_id).html('');
              $('#ex_'+v.item_id).html(' [ '+$('#existencias_'+v.item_id).val()+' ]');
            });
            if (action==1) {
              tmp=$('#existencias_'+kit).val();
              $('#existencias_'+kit).val(parseInt(tmp)-parseInt(quantity));
            }
            if (action==2) {
              tmp=$('#existencias_'+kit).val();
              $('#existencias_'+kit).val(parseInt(tmp)+parseInt(quantity));
            }

            if (add==1) {
              addItemLine(cells, id_item);
            }
            else{
              if (action==1) {
                var q=document.getElementById("quantity_" + id_item);
                if (!updt) {
                  q.value = parseInt(q.value)+1;
                }
                q.defaultValue=q.value;
                changeSubInputQuantity(id_item, q.value);
              }
            }
          }
          else{
            toastr.error("Exisstencias insuficientes para completar el combo.");
            $('#canAdd').val(0);
          }
        }
        $('#ex_'+kit).html('');
        $('#ex_'+kit).html(' [ '+$('#existencias_'+kit).val()+' ]');
      },
      error:function(error){
        console.log(error);
        return error;
      }
    });
  }
  function addToCartTable(cells, id_item)
  {
      var newRow=document.createElement('tr');

      var nombre = document.getElementById("nombre_" + id_item).value;
      var precio = document.getElementById("precio_" + id_item).value;
    //Texto unicamente
    newRow.appendChild(createCell(id_item));
    var cellName = createCell();
      cellName.appendChild(createLabelName(nombre));
      newRow.appendChild(cellName);
    //   newRow.appendChild(createCell(nombre));
      newRow.appendChild(createCell(precio));
    //fin textos
    //inicio inputs
        //nombre
        //Precio
    var cellInputPrice=createCell();
      cellInputPrice.appendChild(createInputPrice(precio,id_item));
      newRow.appendChild(cellInputPrice);
        //cantidad
    var cellInputQuantity = createCell();
      cellInputQuantity.appendChild(createInputQty(id_item));
      newRow.appendChild(cellInputQuantity);
        //subtotal
    var labelTotal = createCell();
      labelTotal.appendChild(createLabelTotal(precio,id_item));
      newRow.appendChild(labelTotal);
        //borrar
    var btnBorrar = createCell();
      btnBorrar.appendChild(createRemoveBtn(id_item));
      newRow.appendChild(btnBorrar);
    var hidden=createCell();
      hidden.appendChild(createHidden(id_item));
      newRow.appendChild(hidden);

      document.querySelector('#target tbody').appendChild(newRow);

  }

  function createInputPrice(price,id_item)
  {
      var inputPrice= document.createElement("input");
      var low_price = document.getElementById('precioBajo_'+id_item).value;
      inputPrice.type="text";
      inputPrice.id="selling_"+id_item;
      inputPrice.name="precio_"+id_item;
      inputPrice.required = 'true';
      inputPrice.style.cssText = 'text-align:center;';
      inputPrice.value = price;
      inputPrice.className = 'form-control';
      inputPrice.step = "any";
      inputPrice.style.width = '50%';
      new Cleave(inputPrice, {
          numeral: true,
          numeralPositiveOnly: true,
          numeralThousandsGroupStyle: 'none'
      });
      inputPrice.addEventListener('change',function(){
        var id=this.id;
            array_id=id.split("_");
            id_item=array_id[1];
            var precio_cambiado=document.getElementById(id);
            var price_real = document.getElementById('precio_' + id_item);
          if (precio_cambiado.value=="")
          {
              precio_cambiado.value = price_real.value;
              var cantidad = document.getElementById('quantity_' + id_item);
              changeSubInputQuantity(id_item, cantidad.value);
          }
          else
          {
            /*VERIFICANDO PRECIO MAS BAJO*/
            if (precio_cambiado.value<low_price) {
              toastr.error("Precio mínimo","No puede vender el artículo a un precio menor al fijado como mínimo: Q "+parseFloat(low_price).toFixed(2)+".");
              precio_cambiado.value=low_price;
              return;
            }
            /*FIN VERIFICACION*/

            //   if (rol_user.value!=1)
            //   {
            //       if (parseFloat(precio_cambiado.value) < parseFloat(price_real.value))
            //       {
            //           console.log("precio no permitido");
            //           precio_cambiado.value = price_real.value;
            //           var cantidad = document.getElementById('quantity_' + id_item);
            //           changeSubInputQuantity(id_item, cantidad.value);
            //       }
            //       else
            //       {
            //           var cantidad = document.getElementById('quantity_' + id_item);
            //           changeSubInputQuantity(id_item, cantidad.value);
            //       }
            //   }
            //   else
            //   {
              var cantidad = document.getElementById('quantity_' + id_item);
              changeSubInputQuantity(id_item, cantidad.value);
            //   }
            //   console.log(cantidad.value);

          }
     });
      return inputPrice;
  }
  function createInputQty(id_item)
  {
      var inputQty = document.createElement("input");
      inputQty.type = "number";
      inputQty.id = "quantity_" + id_item;
      inputQty.name = "cantidad_" + id_item;
      inputQty.required = 'true';
      inputQty.style.cssText = 'text-align:center;';
      inputQty.value = 1;
      inputQty.defaultValue=1;
      inputQty.min = 1;
      inputQty.step = "any";
      inputQty.style.width = '50%';
      inputQty.addEventListener('change',function(){
        var id=this.id;
        array_id=id.split("_");
        id_item=array_id[1];
        var cantidad_cambiada=document.getElementById(id);
          var existencias = document.getElementById('existencias_' + id_item);
          if (cantidad_cambiada.value==""){
              cantidad_cambiada.value=1;
              changeSubInputQuantity(id_item,1);
          }
          else {
            if(parseFloat(cantidad_cambiada.value)==0){
              cantidad_cambiada.value=this.defaultValue;
            }else {
              if (parseFloat(cantidad_cambiada.value) > (parseFloat(existencias.value)+parseFloat(this.defaultValue))){
                // alert("no se puede vender mas");
                toastr.error("No hay existencias suficientes update");
                cantidad_cambiada.value=this.defaultValue;
                changeSubInputQuantity(id_item, this.defaultValue);
              }else {
                var tmpQ=parseInt(cantidad_cambiada.value)-parseInt(this.defaultValue);
                getDetailsKit(id_item, 1, tmpQ, null, id_item, 0, true);
              }
            }
          }
      });
      return inputQty;
  }
function changeSubInputQuantity(id_item,cantidad)
{
    var subTotal = document.getElementById('sutTotal_' + id_item);
            var subTotalSinFormato = replaceAll(subTotal.innerText,",","");
    var inputPrice = document.getElementById('selling_' + id_item);
    var totalTemp = 0;
    var totalAnterior = subTotalSinFormato;
    totalTemp = (parseFloat(inputPrice.value) * parseFloat(cantidad));
    subTotal.innerText = monedaChange(totalTemp);

        var totalAnteriorGeneral = document.getElementById('total_pago');
        var sinComa = replaceAll(totalAnteriorGeneral.value, ",", "");
        var totalGeneral = parseFloat(sinComa) - parseFloat(totalAnterior) + totalTemp;
        totalAnteriorGeneral.value = monedaChange(totalGeneral);
}
function createHidden(id_item)
{
    var hidden=document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = "product_" + id_item;
    hidden.value = id_item;
    return hidden;
}
function createLabelTotal(price, id_item)
{
    var labelTotal = document.createElement("label");
    labelTotal.id = "sutTotal_" + id_item;
    // labelTotal.name = "sutTotal_" + contador;
    // labelTotal.append(monedaChange(price));
    labelTotal.innerHTML=monedaChange(price);

    labelTotal.style.cssText = 'text-align:right;';
    return labelTotal;
}

function createLabelName(name)
{
    var labelTotal = document.createElement("label");
    // labelTotal.append(name);
    labelTotal.innerHTML=name;
    labelTotal.style.cssText = 'text-align:left;font-size:10px;';
    return labelTotal;
}
function createRemoveBtn(id_item)
{
    var btnRemove = document.createElement('button');
    btnRemove.className = 'btn btn-danger btn-xs';
    btnRemove.onclick = remove;
    btnRemove.id = "remove_" + id_item;
    // btnRemove.innerText = 'X';
    btnRemove.appendChild(document.createElement('span'));
    var span=document.createElement("span");
    span.className ="glyphicon glyphicon-trash";
    btnRemove.appendChild(span);
    return btnRemove;
}


  function createCell(text)
  {
    var td = document.createElement('td');
    if (text) {
        td.innerText = text;
    }
    return td;
  }

    function remove()
  {
        var id = this.id;
        var string_no_id = id.split("_");
        var id_item = string_no_id[1];

        var quantity=document.getElementById('quantity_'+id_item).value;
        var quantityAct=document.getElementById('existencias_'+id_item).value;

        var quantity_item = document.getElementById('sutTotal_' + id_item);
        var sutTotalSinFormato = replaceAll(quantity_item.innerText,",","");
        document.getElementById("item_quantity").value = contador;
        var totalpago = document.getElementById('total_pago');
        var totalSinFormato = replaceAll(totalpago.value,",","");
        getDetailsKit(id_item, 2, quantity, null, id_item, 0, false);
        totalpago.value = monedaChange(parseFloat(totalSinFormato) - parseFloat(sutTotalSinFormato));
        var row = this.parentNode.parentNode;
        document.querySelector('#target tbody').removeChild(row);
        contador--;
        document.getElementById('item_quantity').value = contador;
  }


//quitar comas
var replaceAll = function (text, busca, reemplaza) {
    while (text.toString().indexOf(busca) != -1)
        text = text.toString().replace(busca, reemplaza);
    return text;
}


//formatear un numero a moneda
function monedaChange(cantidad, cif = 3, dec = 2) {
    // tomamos el valor que tiene el input
    let inputNum = cantidad;
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
    return valorTotalFormateado = separados.join(',') + '.' + inputNum[1];
}
