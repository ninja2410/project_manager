var contador = 0;
$('#employee').change(function() {
  var valueEmployee = $(this).val();
  if (valueEmployee != 0) {
    $('#user_relation').val(valueEmployee);
  } else {
    $('#user_relation').val(0);
  }
});
$('#item_quantity').val(contador);

$('#id_bodega').change(function() {
  $("#target > tbody").empty();
  $("#records_table > tbody").empty();
  contador = 0;
  $('#item_quantity').val(contador);
  if ($('#id_bodega').val() >= 1) {
    var id_storage = $('#id_bodega').val();
    $.get(APP_URL + '/api/item/' + id_storage, function(data) {
      var quantity_rows = $("#records_table tr").length;
      //se borrar la tabla si tiene contenido
      if (quantity_rows > 1) {
        for (var i = quantity_rows; i > 1; i--) {
          $("#records_table tr:last").remove();
        }
      }
      //reinicializacion de la tabla
      if ($.fn.DataTable.isDataTable("#records_table")) {
        $('#records_table').DataTable().clear().destroy();
      } //reinicializacion de la tabla

      $.each(data, function(i, item) {
        if (data[i].type == 2) {
          var fila = "<td> <input type='hidden' id='refer_" + data[i].id + "' value=" + -1 + ">";
        } else if(data[i].stock_action=='='){
          var fila = "<td> <input type='hidden' id='refer_" + data[i].id + "' value=" + data[i].stock_action + ">";
        }
        else {
          var fila = "<td> <input type='hidden' id='refer_" + data[i].id + "' value=" + data[i].quantity + " name=" + data[i].quantity + ">";
        }
        fila += " <td> <input type='hidden' id='minprice_" + data[i].id + "' value=" + data[i].low_price + ">";
        fila += "</td>";
        $('<tr>').html(fila).appendTo('#table_reference tbody');
      });

      //llenamos la nueva tabla
      $.each(data, function(i, item) {
        var fila = "<td style='font-size:9px;'>" + data[i].barcode + "</td>";
        fila += "<td style='font-size:10px;'>" + data[i].item_name + "</td>";
        fila += "<td style='display: none;''>" + data[i].selling_price + "</td>";
        // if (data[i].type == 2) {
        //   fila += "<td style='font-size:10px; text-align:center;'>Kit</td>";
        // }else 
        if(data[i].stock_action=='='){
          fila += "<td style='font-size:10px; text-align:center;'>Servicio</td>";
        }
        else {
          fila += "<td style='font-size:10px; text-align:center;'>" + data[i].quantity + "</td>";
        }

        fila += "<td style='display: none;''>" + data[i].barcode + "</td>";
        fila += "<td width='15%;' style='font-size:12px; text-align:center;'>";
        if (data[i].quantity > 0 || data[i].type == 2 || data[i].stock_action=='=') {
          if (data[i].type == 2) {
            fila += "<button type='button' name='button'  onclick='add(this);' value='" + -1 + "' class='btn btn-primary btn-xs' id='" + data[i].id + "'>";
          } else if(data[i].stock_action=='=') {
            fila += "<button type='button' name='button'  onclick='add(this);' value='" + data[i].stock_action + "' class='btn btn-primary btn-xs' id='" + data[i].id + "'>";
          }
          else{
            fila += "<button type='button' name='button'  onclick='add(this);' value='" + data[i].quantity + "' class='btn btn-primary btn-xs' id='" + data[i].id + "'>";
          }
          fila += "<span class='glyphicon glyphicon-share-alt' aria-hidden='true'></span>";
          fila += "</button>";
        }
        fila += "</td>";
        $('<tr>').html(fila).appendTo('#records_table tbody');
      }); //fin de la nueva tabla

      $('#records_table').DataTable({
        retrieve: true,
        // searching: false,
        "bLengthChange": false,
        "bInfo": false,
        "bAutoWidth": false,
        language: {
          "url": APP_URL + '/assets/json/nwSpanish.json'
        }
      });

    });
  }
}); //fin del change

function updateQuantity(id_item, cantidad_actual, id_storage){
  $('#id_storage_' + id_storage + '_id_item_' + id_item).val(parseInt(cantidad_actual) + 1);
  var cantidad_cambio = $('#id_storage_' + id_storage + '_id_item_' + id_item).val();
  //hacemos que se refleje el cambio en el input del subtotal
  var anterior_precio = $('#total_storage_' + id_storage + '_id_item_' + id_item).val();
  //obtener el valor del input del precio
  var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + id_item).val();
  //obtenemos el nuevo precio
  var total_temp = parseFloat(selling_price) * parseFloat(cantidad_cambio);
  $('#total_storage_' + id_storage + '_id_item_' + id_item).val(total_temp.toFixed(2));

  //cambiar el precio total
  var general_temp = $('#total_general').val();
  general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
  $('#total_general').val(general_temp.toFixed(2));

  var totalFormateado = monedaChange();
  $('#total_general2').val(totalFormateado);
}

function add(button) {
  var row = button.parentNode.parentNode;
  var cells = row.querySelectorAll('td:not(:first-of-type),td:not(:last-of-type)')
  var id_item = button.id;
  var quantity = $('#refer_' + id_item).val();
  //comparamos para ver si ya esta el producto de la bodega
  var data_storage = $('#id_bodega').val();
  var id_storage = $('#id_bodega').val();
  if ($('#id_storage_' + id_storage + '_id_item_' + id_item).length) {
    var cantidad_actual = parseFloat($('#id_storage_' + id_storage + '_id_item_' + id_item).val());
    var valor_default = document.getElementById('id_storage_' + id_storage + '_id_item_' + id_item).defaultValue;
    if ((quantity<0 && setItemKit(id_item, (cantidad_actual+1), id_storage))) {
      updateQuantity(id_item, cantidad_actual, id_storage);
      updateReference(id_item, (cantidad_actual+1), id_storage, valor_default);
      document.getElementById('id_storage_' + id_storage + '_id_item_' + id_item).defaultValue = cantidad_actual+1;
    } else if(((quantity>0) && (parseInt(cantidad_actual) < parseInt(quantity))) || quantity=='=') {
      updateQuantity(id_item, cantidad_actual, id_storage);
    }
    else{
      toastr.error("Existencias insuficientes", "Error");
    }
  } else {
    if (quantity<0 && setItemKit(id_item, 1, id_storage)) {
      addFirstRow(cells, id_item, id_storage);
      updateReference(id_item, 1, id_storage, 0);
    }
    if(quantity>0 || quantity=='='){
      addFirstRow(cells, id_item, id_storage);
    }
    if (quantity==0) {
      toastr.error("Existencias insuficientes", "Error");
    }
  }
  $("#search").val("");

}

function addFirstRow(cells, id_item, id_storage){
  contador++;
  addToCartTable(cells, id_item, id_storage);
  $('#item_quantity').val(contador);
  //hacemos la suma para el nuevo total
  var total_id_item = $('#total_storage_' + id_storage + '_id_item_' + id_item).val();
  total_temp = parseFloat($('#total_general').val()) + parseFloat(total_id_item);
  $('#total_general').val(total_temp.toFixed(2));
  var totalFormateado = monedaChange();
  $('#total_general2').val(totalFormateado);
}

function remove() {
  // console.log(this);
  // btnRemove.id='btndelete_storage_'+id_storage+'_id_item_'+id_item;
  var id = this.id;
  var string_no_id = id.split("_");
  no_storage = string_no_id[2];
  no_id = string_no_id[5];
  if (this.name=='Kit') {
    removeReference(no_id, no_storage,parseFloat($('#id_storage_' + no_storage + '_id_item_' + no_id).val()));
  }
  // removeReference(no_id);
  //sacamos el subtotal del elemento que vamos a borrar
  var sub_total = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
  //borramos el subtotal del total general
  var general_temp = $('#total_general').val();
  general_temp = parseFloat(general_temp) - parseFloat(sub_total);
  //ponermos el valor en el text
  $('#total_general').val(general_temp.toFixed(2));
  var totalFormateado = monedaChange();
  $('#total_general2').val(totalFormateado);
  //eliminamos el elemento y agregamos restamos el contador nuevo
  var row = this.parentNode.parentNode;
  document.querySelector('#target tbody').removeChild(row);
  contador--;
  $('#item_quantity').val(contador);
}

function addToCartTable(cells, id_item, bodega_id) {

  //  var name = cells[0].innerText;
  var name = cells[1].innerText;
  //cantidad existencias
  //  var selling_price=cells[1].innerText
  var selling_price = cells[2].innerText
  //  var max_quantity=cells[2].innerText;
  var max_quantity = cells[3].innerText;
  //sacamos el nombre de la bodega
  var name_storage = $('#bodega_id').text();
  var id_storage = bodega_id;
  var newRow = document.createElement('tr');
  newRow.appendChild(createCell(name, "10px"));
  // newRow.appendChild(createCell(name_storage));
  //input cantidad
  var cellInputQty = createCell();
  cellInputQty.appendChild(createInputQty(max_quantity, id_item, id_storage));
  newRow.appendChild(cellInputQty);
  //Precio
  var cellSellingPrice = createCell();
  cellSellingPrice.appendChild(createInputSelling_price(selling_price, id_item, id_storage));
  newRow.appendChild(cellSellingPrice);
  //subtotal
  var cellSubTotal = createCell();
  cellSubTotal.appendChild(createInputSub_total(selling_price, id_item, id_storage));
  newRow.appendChild(cellSubTotal);
  //creamos el id del producto
  var cellHiddenIdProduct = createCell();
  cellHiddenIdProduct.appendChild(createInputHiddenIdProduct(id_item));
  newRow.appendChild(cellHiddenIdProduct);
  //creamos el id de la bodega
  var cellHiddenIdStorage = createCell();
  cellHiddenIdStorage.appendChild(createInputHiddenIdStorage(id_storage, id_item));
  newRow.appendChild(cellHiddenIdStorage);
  //boton remove
  var cellRemoveBtn = createCell();
  cellRemoveBtn.appendChild(createRemoveBtn(id_item, id_storage, max_quantity))
  newRow.appendChild(cellRemoveBtn);
  //existencias de bodega
  var cellExistencias = createCell();
  cellExistencias.appendChild(createInputHiddenQuantityExist(max_quantity, id_storage, id_item));
  newRow.appendChild(cellExistencias);
  document.querySelector('#target tbody').prepend(newRow);
}



function createInputSelling_price(selling_price, id_item, id_storage) {
  var inputQty = document.createElement('input');
  inputQty.type = 'number';
  inputQty.id = 'selling_storage_' + id_storage + '_id_item_' + id_item;
  inputQty.name = 'selling_price_' + contador;
  inputQty.required = 'true';
  inputQty.style.cssText = 'text-align:center;';
  inputQty.value = selling_price;
  inputQty.step = "any";
  inputQty.style.width = '100%';
  // inputQty.className='form-control';
  inputQty.addEventListener('change', function() {

    var id = this.id;
    string_no_id = id.split("_");
    no_storage = string_no_id[2];
    no_id = string_no_id[5];
    var low_price = parseFloat($('#minprice_'+no_id).val());
    var precio_cambio = $('#' + id).val();;
    if (precio_cambio == "" || parseFloat(precio_cambio)< low_price) {
      if (parseFloat(precio_cambio)<low_price) {
        toastr.warning("El precio ingresado no debe ser menor al precio mínimo de venta: Q"+low_price);
      }
      $('#' + id).val(selling_price);
      var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
      var cantidad_total = $('#id_storage_' + id_storage + '_id_item_' + no_id).val();
      var total_temp = parseFloat(cantidad_total) * parseFloat(selling_price);
      $('#total_storage_' + no_storage + '_id_item_' + no_id).val(parseFloat(cantidad_total) * parseFloat(selling_price));
      var general_temp = $('#total_general').val();
      general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
      $('#total_general').val(general_temp.toFixed(2));
      var totalFormateado = monedaChange();
      $('#total_general2').val(totalFormateado);
    } else {
      var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
      var cantidad_total = $('#id_storage_' + id_storage + '_id_item_' + no_id).val();
      var total_temp = parseFloat(cantidad_total) * parseFloat(precio_cambio);
      $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));

      var general_temp = $('#total_general').val();
      general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);

      $('#total_general').val(general_temp.toFixed(2));
      var totalFormateado = monedaChange();
      $('#total_general2').val(totalFormateado);
    }

  });
  return inputQty;
}

function createInputQty(max_quantity, id_item, id_storage) {
  var inputQty = document.createElement('input');

  inputQty.type = 'number';
  // inputQty.id='cantidad_'+id_item;
  inputQty.id = 'id_storage_' + id_storage + '_id_item_' + id_item;
  inputQty.name = 'cantidad_' + contador;
  inputQty.required = 'true';
  inputQty.defaultValue = "1";
  inputQty.style.cssText = 'text-align:center;';
  inputQty.min = 1;
  inputQty.addEventListener('change', function() {
    var id = this.id;
    string_no_id = id.split("_");
    no_storage = string_no_id[2];
    no_id = string_no_id[5];

    var cantidad_cambio = $('#' + id).val();
    if (cantidad_cambio == "") {
      $('#' + id).val(1);
      var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
      //obtener el valor del input del precio
      var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + no_id).val();
      //obtenemos el nuevo precio
      var total_temp = parseFloat(selling_price) * parseFloat(1);
      $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));

      //cambiar el precio total
      var general_temp = $('#total_general').val();
      general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
      $('#total_general').val(general_temp.toFixed(2));

      var totalFormateado = monedaChange();
      $('#total_general2').val(totalFormateado);
    } else {

      //obtenos el total anterior del input sub_total
      var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
      //obtener el valor del input del precio
      var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + no_id).val();
      //obtenemos el nuevo precio
      var total_temp = parseFloat(selling_price) * parseFloat(cantidad_cambio);
      $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));

      //cambiar el precio total
      var general_temp = $('#total_general').val();
      general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
      $('#total_general').val(general_temp.toFixed(2));

      var totalFormateado = monedaChange();
      $('#total_general2').val(totalFormateado);
    }
  });
  inputQty.addEventListener('blur', function() {
    var id = this.id;
    var string_no_id = id.split("_");
    var no_storage = string_no_id[2];
    var no_id = string_no_id[5];
    var cantidad_cambiada = $('#id_storage_' + no_storage + '_id_item_' + no_id).val();
    var valor_default = document.getElementById('id_storage_' + no_storage + '_id_item_' + no_id).defaultValue;
    var existencias = $('#refer_'+ no_id).val();
    if (existencias!='='&&existencias>0) {
      if (parseInt(cantidad_cambiada) > parseInt(existencias)) {
        toastr.error("Existencias insuficientes", "Error");
        $('#ajax-modal2').modal('show');
        $('#id_storage_' + no_storage + '_id_item_' + no_id).val(existencias);

        var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
        //obtener el valor del input del precio
        var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + no_id).val();
        //obtenemos el nuevo precio
        var total_temp = parseFloat(selling_price) * parseFloat(existencias);
        $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));

        //cambiar el precio total
        var general_temp = $('#total_general').val();
        general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
        $('#total_general').val(general_temp.toFixed(2));

        var totalFormateado = monedaChange();
        $('#total_general2').val(totalFormateado);
      }
    }
    else{
      if (existencias!='=' && !setItemKit(no_id, cantidad_cambiada, no_storage)) {
        toastr.error("Existencias insuficientes", "Error");
        $('#ajax-modal2').modal('show');
        $('#id_storage_' + no_storage + '_id_item_' + no_id).val(0);

        var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
        //obtener el valor del input del precio
        var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + no_id).val();
        //obtenemos el nuevo precio
        var total_temp = parseFloat(selling_price) * parseFloat(0);
        $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));

        //cambiar el precio total
        var general_temp = $('#total_general').val();
        general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
        $('#total_general').val(general_temp.toFixed(2));

        var totalFormateado = monedaChange();
        $('#total_general2').val(totalFormateado);
        inputQty.focus();
      }
      else if(existencias!='='){
        updateReference(no_id, cantidad_cambiada, no_storage, valor_default);
        document.getElementById('id_storage_' + no_storage + '_id_item_' + no_id).defaultValue = cantidad_cambiada;
      }
    }
  });
  inputQty.value = 1;
  inputQty.max = max_quantity;
  inputQty.className = 'form-control';
  inputQty.style.width = '100%';
  return inputQty;
}

function createCell(text, tamanoletra) {
  var td = document.createElement('td');
  if (tamanoletra) {
    td.style.fontsize = tamanoletra;
  }
  if (text) {
    td.innerText = text;
  }
  return td;
}

function createRemoveBtn(id_item, id_storage, max_quantity) {
  var btnRemove = document.createElement('button');
  btnRemove.className = 'btn btn-xs btn-danger';
  btnRemove.id = 'btndelete_storage_' + id_storage + '_id_item_' + id_item;
  btnRemove.onclick = remove;
  btnRemove.name = max_quantity;
  btnRemove.innerText = 'X';
  return btnRemove;
}

function createInputHiddenIdProduct(id_item) {
  var inputHidden = document.createElement('input');
  inputHidden.type = 'hidden';
  inputHidden.name = 'id_product_' + contador;
  inputHidden.required = 'true';
  inputHidden.style.cssText = 'text-align:center;';
  inputHidden.min = 1;
  inputHidden.value = id_item;
  inputHidden.className = 'form-control'
  return inputHidden;
}

function createInputHiddenIdStorage(id_storage, id_item) {
  var inputHidden = document.createElement('input');
  inputHidden.type = 'hidden';
  // inputHidden.id='id_storage_'+id_storage+'_id_item_'+id_item;
  inputHidden.name = 'id_storage_' + contador;
  inputHidden.required = 'true';
  inputHidden.style.cssText = 'text-align:center;';
  inputHidden.min = 1;
  inputHidden.value = id_storage;
  inputHidden.className = 'form-control'
  return inputHidden;
}

function createInputHiddenQuantityExist(max_quantity, id_storage, id_item) {
  var inputHidden = document.createElement('input');
  inputHidden.type = 'hidden';
  inputHidden.id = 'quantityExist_storage_' + id_storage + '_id_item_' + id_item;
  inputHidden.required = 'true';
  inputHidden.style.cssText = 'text-align:center;';
  inputHidden.min = 1;
  inputHidden.value = max_quantity;
  inputHidden.className = 'form-control'
  return inputHidden;
}

function createInputSub_total(selling_price, id_item, id_storage) {
  var inputQty = document.createElement('input');

  inputQty.type = 'input';
  inputQty.id = 'total_storage_' + id_storage + '_id_item_' + id_item;
  inputQty.name = 'total_' + contador;
  // inputQty.required = 'true';
  inputQty.style.cssText = 'text-align:center;';
  inputQty.value = selling_price;
  inputQty.disabled = 'true';
  inputQty.style.width = '100%';
  return inputQty;
}

//formato de moneda
function monedaChange(cif = 3, dec = 2) {
  // tomamos el valor que tiene el input
  let inputNum = document.getElementById('total_general').value
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
      // console.log(uno, pos)
      separados.push(inputNum[0].substring(pos, (pos + 3)))
    }
  } else {
    separados = [inputNum[0]]
  }
  return valorTotalFormateado = 'Q ' + separados.join(',') + '.' + inputNum[1];
}

function setItemKit(id_item, quantity, id_storage) {
  var x = true;
  $.ajax({
    method:'get',
        async: false,
        url:APP_URL+'/getDetails/' + id_item,
        success:function(data){
          x=true;
          $.each(data, function(i, item) {
            var cant_max = parseFloat($('#refer_' + data[i].item_id).attr("name"));
            if ($('#id_storage_' + id_storage + '_id_item_' + data[i].item_id).length) {
              var cant_sel = $('#id_storage_' + id_storage + '_id_item_' + data[i].item_id).val();
            } else {
              var cant_sel = 0;
            }
            if (parseFloat(quantity * data[i].quantity) > parseFloat(cant_max - cant_sel)) {
              toastr.error("Existencias insuficientes para completar el combo");
              x = false;
              return;
            }
          });
        },
        error:function(error){
          console.log(error);
        }
  });
  return x;
}

function updateReference(id_item, quantity, id_storage, defaultValue) {
  console.log(defaultValue);
  if (defaultValue>0) {
    removeReference(id_item, id_storage, defaultValue);
  }
  var x = true;
  $.ajax({
    method:'get',
        async: false,
        url:APP_URL+'/getDetails/' + id_item,
        success:function(data){
          x=true;
          $.each(data, function(i, item) {
            var cant_max = parseFloat($('#refer_' + data[i].item_id).val());
            var newRefer = parseFloat(cant_max-data[i].quantity*quantity);
            $('#refer_'+data[i].item_id).val(newRefer);
          });
        },
        error:function(error){
          console.log(error);
        }
  });
}

function removeReference(id_item, id_storage, quantity) {
  var x = true;
  $.ajax({
    method:'get',
        async: false,
        url:APP_URL+'/getDetails/' + id_item,
        success:function(data){
          x=true;
          $.each(data, function(i, item) {
            var cant_max = parseFloat($('#refer_' + data[i].item_id).val());
            $('#refer_'+data[i].item_id).val(cant_max+(quantity*data[i].quantity));
          });
        },
        error:function(error){
          console.log(error);
        }
  });
}
