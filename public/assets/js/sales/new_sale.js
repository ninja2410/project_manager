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


function updateQuantity(id_item, cantidad_actual, id_storage,counter){
  $('#id_storage_' + id_storage + '_id_item_' + id_item+'_'+counter).val(cleanNumber(cantidad_actual) + 1);
  var cantidad_cambio = $('#id_storage_' + id_storage + '_id_item_' + id_item+'_'+counter).val();
  //hacemos que se refleje el cambio en el input del subtotal
  var anterior_precio = $('#total_storage_' + id_storage + '_id_item_' + id_item+'_'+counter).val();
  //obtener el valor del input del precio
  var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + id_item+'_'+counter).val();
  //obtenemos el nuevo precio
  var total_temp = cleanNumber(selling_price) * cleanNumber(cantidad_cambio);
  $('#total_storage_' + id_storage + '_id_item_' + id_item+'_'+counter).val(total_temp.toFixed(2));

  //cambiar el precio total
  var general_temp = $('#total_general').val();
  general_temp = cleanNumber(general_temp) - anterior_precio + cleanNumber(total_temp);
  $('#total_general').val(general_temp.toFixed(2));

  var descuento = cleanNumber($('#discount_amount').val());
  setTotales(general_temp,descuento);

  // var totalFormateado = monedaChangeParam(general_temp-descuento);
  // $('#total_general2').val(totalFormateado);



}

function add(button) {
  var row = button.parentNode.parentNode;
  var cells = row.querySelectorAll('td:not(:first-of-type),td:not(:last-of-type)')
  var id_item = button.id;
  var lista = {
    id: button.id,
    upc_ean_isbn : cells[0].innerText,
    item_name : cells[1].innerText,
    selling_price: cells[5].innerText.replace(",","").replace("Q","").replace(" ",""),
    quantity :cells[4].innerText,
    low_price: document.getElementById("low_price_" + id_item).value,
    is_kit:document.getElementById("is_kit_" + id_item).value
  };
  // console.log(lista);
  // alert(cells[0].innerText + ' ' + cells[1].innerText+ ' ' +cells[2].innerText+ ' ' +cells[3].innerText+ ' ' +cells[4].innerText+ ' ' +cells[5].innerText+ ' 6 ' +cells[6].innerText);
  // return;

  agregar(lista);


}


function agregar(lista, quotPrice=0, quotQuantity=0) {
  var id_item = lista.id;
  var datos_fila =[];
  datos_fila[0] = id_item;
  datos_fila[1]  = lista.upc_ean_isbn;
  datos_fila[2]  = lista.item_name;
  datos_fila[3] = lista.selling_price;
  var quantity = datos_fila[4] = lista.quantity;

  datos_fila[5]  = lista.low_price;
  datos_fila[6]  = lista.is_kit;
  // console.log('agregar '+datos_fila);
  if((typeof $("#id_bodega").val() === 'undefined') || ($("#id_bodega").val() === null))
  {
    toastr.error("Seleccione una bodega.");
    $("#id_bodega").focus();
    return;
  }
  else {
    var id_storage = $('#id_bodega').val();
  }

  var permitir_varios_items = cleanNumber($('#permitir_varios_items').val());

  var nuevo_id='item_' + id_item;
  var largo_id = cleanNumber($('#' + nuevo_id).length);


  if ( (largo_id>0) && (permitir_varios_items===0))
  {
    var partir = $('#'+nuevo_id).attr('name').split("_");
    // console.log(' nombre id elemento '+$('#'+nuevo_id).attr('name'));
    var counter = partir[2];
    // console.log('contador '+counter);
    var cantidad_actual = cleanNumber($('#id_storage_' + id_storage + '_id_item_' + id_item+'_'+counter).val());
    // console.log('candidad actual '+cantidad_actual);

    /* Si es Kit y las existencias de sus dependencias son suficientes */
    if ((datos_fila[6]==1 && setItemKit(id_item, (cantidad_actual+1), id_storage))) {
      // console.log('es kit modificar cantidad');
      updateQuantity(id_item, cantidad_actual, id_storage,counter);

      document.getElementById('id_storage_' + id_storage + '_id_item_' + id_item+'_'+counter).defaultValue = cantidad_actual+1;
    } else if(((quantity>0) && (parseInt(cantidad_actual) < parseInt(quantity))) || quantity=='=') {
      // console.log('́existencia suficiente o servicio modificar cantidad');
      updateQuantity(id_item, cantidad_actual, id_storage,counter);
    }
    else{
      toastr.error("Existencias insuficientes", "Error");
    }

  } else {
    var max_lineas = cleanNumber($('#max_lineas').val());
    // console.log('new_sale.js max lineas '+max_lineas+' contador '+contador);
    if ((max_lineas>0) && (max_lineas<=cleanNumber(contador)))
    {
      toastr.error("Solo se pueden agregar "+max_lineas+" items para la venta", "Error");
      return;
    }
    // console.log('no entro al if');
    /* Si es Kit y las existencias de sus dependencias son suficientes */
    if (datos_fila[6]==1 && setItemKit(id_item, 1, id_storage)) {
      // console.log('́Es kit, agregar ');
      addFirstRow(datos_fila, id_item, id_storage, quotPrice, quotQuantity);
    }
    /* Servicio */
    if(datos_fila[6]!=1 && (quantity>0 || quantity=='=' || lista.stock_action=='=')){
      // console.log('́Hay existencia o es servicio, agregar ');
      addFirstRow(datos_fila, id_item, id_storage, quotPrice, quotQuantity);
    }
    /* No hay existencia */
    if (quantity==0 && lista.stock_action !='=') {
      toastr.error("Existencias insuficientes", "Error");
    }
  }
  /* Borramos campo de busqueda y le enviamos el foco */
  document.getElementById('autocom').value = '';
  document.getElementById('codigo').value = '';
  // $( "#txtHint").html("");
  $( "#codigo").focus();
}

function addFirstRow(cells, id_item, id_storage, quotPrice, quotQuantity){
  contador++;
  // console.log(' datos fila antes de agregar '+quotQuantity);
  addToCartTable(cells, id_item, id_storage, quotPrice, quotQuantity);
  $('#item_quantity').val(contador);
  //hacemos la suma para el nuevo total
  var total_id_item = $('#total_storage_' + id_storage + '_id_item_' + id_item+'_'+contador).val();
  total_temp = cleanNumber($('#total_general').val()) + cleanNumber(total_id_item);
  $('#total_general').val(total_temp.toFixed(2));

  var descuento = cleanNumber($('#discount_amount').val());


  setTotales(total_temp,descuento);

  // var totalFormateado = monedaChange();
  // $('#total_general2').val(totalFormateado-descuento);

  // setPaymentValues(totalFormateado);
}

function remove() {
  // console.log(this);
  // btnRemove.id='btndelete_storage_'+id_storage+'_id_item_'+id_item;
  var id = this.id;
  var string_no_id = id.split("_");
  no_storage = string_no_id[2];
  no_id = string_no_id[5];
  count = string_no_id[6];
  if (this.name=='Kit') {
    removeReference(no_id, no_storage,cleanNumber($('#id_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val()));
  }
  // removeReference(no_id);
  //sacamos el subtotal del elemento que vamos a borrar
  var sub_total = $('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val();
  //borramos el subtotal del total general
  var general_temp = $('#total_general').val();
  general_temp = cleanNumber(general_temp) - cleanNumber(sub_total);
  //ponermos el valor en el text
  $('#total_general').val(general_temp.toFixed(2));
  var descuento = cleanNumber($('#discount_amount').val());

  setTotales(general_temp,descuento);

  // var totalFormateado = monedaChange();
  // $('#total_general2').val(totalFormateado-descuento);
  // setPaymentValues(totalFormateado);
  //eliminamos el elemento y agregamos restamos el contador nuevo
  var row = this.parentNode.parentNode;
  document.querySelector('#target tbody').removeChild(row);
  contador--;
  $('#item_quantity').val(contador);
}

function addToCartTable(cells, id_item, bodega_id, quotPrice = 0, quotQuantity = 0) {
  // console.log(' addtocarttable '+cells);
  //  var name = cells[0].innerText;
  var codigo = cells[1];
  var name = cells[2];
  var is_kit = cells[6];
  //cantidad existencias
  //  var selling_price=cells[1].innerText
  var selling_price = cells[3];
  //  var max_quantity=cells[2].innerText;
  var max_quantity = cells[4]; /* Existencia */
  var min_price = cells[5];
  //sacamos el nombre de la bodega
  var name_storage = $('#bodega_id').text();
  var id_storage = bodega_id;
  var newRow = document.createElement('tr');
  newRow.className = '_rowItem';
  newRow.appendChild(createCell(codigo, "10px"));
  newRow.appendChild(createCell(name, "10px"));

  if (quotPrice!=0){
    selling_price = quotPrice;
  }

  //input precio de venta
  var cellUnit = createCell();
  cellUnit.insertAdjacentHTML('afterbegin',createUnitMeasureDrop(id_item, id_price.value));
  newRow.appendChild(cellUnit);

  //input precio de venta
  var cellSellingPrice = createCell();
  cellSellingPrice.appendChild(createInputSelling_price(selling_price,min_price, id_item, id_storage));
  newRow.appendChild(cellSellingPrice);

  //input cantidad
  var cellInputQty = createCell();
  cellInputQty.appendChild(createInputQty(max_quantity, id_item, id_storage, is_kit,quotQuantity));
  newRow.appendChild(cellInputQty);
  //Precio

  //subtotal= cantidad * precio venta
  var cellSubTotal = createCell();
  cellSubTotal.appendChild(createInputSub_total(selling_price, id_item, id_storage, quotQuantity, max_quantity));
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
  configSelectsUnits();
}

function createUnitMeasureDrop(item_id, price_id){
  let options='';
  $.ajax({
    type: "post",
    async: false,
    url: APP_URL + "/item_prices",
    data: {
      _token: $("#token_").val(),
      item_id: item_id,
      price_id: price_id
    },
    success: function(data) {
      let prices = data;
      prices.forEach((item, i) => {
        options += `<option ${item.default==1 ? 'selected="selected"' : ''} value="${item.unit_id}" selling_price="${item.selling_price}">${item.unidad}</option>`;
      });
    },
    error: function(error) {
      console.log("existe un error revisar");
    }
  });

  let render = `<select item_id="${item_id}" line="${contador}" class="form-control unit_option" name="unit_${contador}" id="unit_${contador}">
                  ${options}
                </select>`;
  return render;
}

function configSelectsUnits(){
  $('select').select2({
    allowClear: true,
    theme: "bootstrap",
    placeholder: "Buscar"
  });
  $('.unit_option').change(function (){
    let newPrice = cleanNumber($(this).find(':selected').attr("selling_price")).format(2);
    //id_bodega
    $(`#selling_storage_${id_bodega.value}_id_item_${$(this).attr("item_id")}_${$(this).attr("line")}`).val(newPrice);
    $(`#id_storage_${id_bodega.value}_id_item_${$(this).attr("item_id")}_${$(this).attr("line")}`).trigger("input");
    const e = new Event("input");
    document.getElementById(`id_storage_${id_bodega.value}_id_item_${$(this).attr("item_id")}_${$(this).attr("line")}`).dispatchEvent(e);
  });
}


function createInputSelling_price(selling_price,low_price, id_item, id_storage) {
  var inputQty = document.createElement('input');
  inputQty.type = 'text';
  inputQty.id = 'selling_storage_' + id_storage + '_id_item_' + id_item+'_'+contador;
  inputQty.name = 'selling_price_' + contador;
  inputQty.required = 'true';
  inputQty.style.cssText = 'text-align:center;';
  inputQty.value = selling_price;
  inputQty.step = "0.01";
  inputQty.min = "0.01";
  inputQty.style.width = '100%';
  inputQty.className='form-control _itemPrice';
  inputQty.onkeypress = function(e) {
    if( event.which == 13)
    {
      event.preventDefault();
    }
  };
  new Cleave(inputQty, {
    numeral: true,
    numeralPositiveOnly: true,
    numeralThousandsGroupStyle: 'none'
  });

  inputQty.addEventListener('change', function() {

    var id = this.id;
    string_no_id = id.split("_");
    // console.log(' split '+string_no_id);
    no_storage = string_no_id[2];
    no_id = string_no_id[5];
    cont = string_no_id[6];
    // console.log('cont '+cont);

    var precio_cambio = cleanNumber($('#' + id).val());
    /**
     * Obtenemos el parámetro para saber si es necesario validar el precio mínimo.
     */
    var validar_precio_minimo = cleanNumber($('#validar_precio_minimo').val());



    if ((cleanNumber(precio_cambio)< low_price) && (validar_precio_minimo===1)) {
      // if ((cleanNumber(precio_cambio)<low_price) && (validar_precio_minimo===1)) {
        toastr.warning("El precio ingresado no debe ser menor al precio mínimo de venta: Q"+low_price);
      // }
      $('#' + id).val(selling_price);
      var anterior_precio = cleanNumber($('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+cont).val());
      var cantidad_total = cleanNumber($('#id_storage_' + id_storage + '_id_item_' + no_id+'_'+cont).val());
      var total_temp = cleanNumber(cantidad_total * selling_price);
      $('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+cont).val(cleanNumber(cleanNumber(cantidad_total) * cleanNumber(selling_price)));
      var general_temp = $('#total_general').val();
      general_temp = cleanNumber(general_temp) - anterior_precio + cleanNumber(total_temp);
      // $('#total_general').val(general_temp.toFixed(2));
      // var totalFormateado = monedaChange();
      // $('#total_general2').val(totalFormateado);
    } else {
      if (precio_cambio<0){
        precio_cambio=0;
      }

      var anterior_precio = cleanNumber($('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+cont).val());
      // var anterior_precio = this.value;
      // console.log('precio cambio'+precio_cambio);
      // console.log('anterior precio '+anterior_precio);
      // console.log('this '+this.value);
      var cantidad_total = cleanNumber($('#id_storage_' + id_storage + '_id_item_' + no_id+'_'+cont).val());
      var total_temp = cleanNumber(cantidad_total) * cleanNumber(precio_cambio);
      $('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+cont).val(total_temp.format(2));

      var general_temp = $('#total_general').val();
      general_temp = cleanNumber(general_temp) - anterior_precio + cleanNumber(total_temp);

      // $('#total_general').val(general_temp.toFixed(2));
      // var totalFormateado = monedaChange();
      // $('#total_general2').val(totalFormateado);
    }
    $('#total_general').val(general_temp.toFixed(2));
    var descuento = cleanNumber($('#discount_amount').val());
    setTotales(general_temp,descuento);
    // var totalFormateado = monedaChange();
    // $('#total_general2').val(totalFormateado);
    // setPaymentValues(totalFormateado);

  });
  return inputQty;
}

function createInputQty(max_quantity, id_item, id_storage, is_kit, quotQuantity = 0) {
  var inputQty = document.createElement('input');
  inputQty.type = 'text';
  // inputQty.id='cantidad_'+id_item;
  inputQty.id = 'id_storage_' + id_storage + '_id_item_' + id_item+'_'+contador;
  inputQty.name = 'cantidad_' + contador;
  inputQty.required = 'true';
  inputQty.style.cssText = 'text-align:center;';
  inputQty.min = 0.01;
  inputQty.step = 0.01;
  inputQty.className = "form-control _itemQuantity";
  inputQty.onkeypress = function(e) {
    if( event.which == 13)
    {
      event.preventDefault();
    }
  };
  if (is_kit == 1){
    new Cleave(inputQty, {
      numeral: true,
      numeralPositiveOnly: true,
      numeralDecimalScale:0,
      numeralThousandsGroupStyle: 'none'
    });
  }
  else{
    new Cleave(inputQty, {
      numeral: true,
      numeralPositiveOnly: true,
      numeralThousandsGroupStyle: 'none'
    });
  }



  inputQty.addEventListener('input', function() {

    var id = this.id;
    string_no_id = id.split("_");
    no_storage = string_no_id[2];
    no_id = string_no_id[5];
    count = string_no_id[6];

    var cantidad_cambio = $('#' + id).val();
    if ((cantidad_cambio == "") || (cantidad_cambio<0)) {
      $('#' + id).val(1);
      var anterior_precio = cleanNumber($('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val());
      //obtener el valor del input del precio
      var selling_price = cleanNumber($('#selling_storage_' + id_storage + '_id_item_' + no_id+'_'+count).val());
      //obtenemos el nuevo precio
      var total_temp = cleanNumber(selling_price * 1);
      $('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val(total_temp.format(2));

      //cambiar el precio total
      var general_temp = $('#total_general').val();
      general_temp = cleanNumber(general_temp) - anterior_precio + cleanNumber(total_temp);
      // $('#total_general').val(general_temp.toFixed(2));

      // var totalFormateado = monedaChange();
      // $('#total_general2').val(totalFormateado);
    } else {

      //obtenos el total anterior del input sub_total
      var anterior_precio = cleanNumber($('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val());
      //obtener el valor del input del precio
      var selling_price = cleanNumber($('#selling_storage_' + id_storage + '_id_item_' + no_id+'_'+count).val());
      //obtenemos el nuevo precio
      var total_temp = cleanNumber(selling_price * cantidad_cambio);
      $('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val(total_temp.format(2));

      //cambiar el precio total
      var general_temp = cleanNumber($('#total_general').val());
      general_temp = cleanNumber(general_temp) - anterior_precio + cleanNumber(total_temp);
      // $('#total_general').val(general_temp.toFixed(2));

      // var totalFormateado = monedaChange();
      // $('#total_general2').val(totalFormateado);
    }
    $('#total_general').val(general_temp.toFixed(2));

    var descuento = cleanNumber($('#discount_amount').val());
  setTotales(general_temp,descuento);
    // var totalFormateado = monedaChange();
    // $('#total_general2').val(totalFormateado);
    // setPaymentValues(totalFormateado);
  });

  inputQty.addEventListener('blur', function() {
    if (this.value<0)
    {
      console.log('menor a 0 '+this.value);
      this.value=1;
      return;
    }
    var id = this.id;
    var string_no_id = id.split("_");
    var no_storage = string_no_id[2];
    var no_id = string_no_id[5];
    var count = string_no_id[6];
    var cantidad_cambiada = $('#id_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val();
    var valor_default = document.getElementById('id_storage_' + no_storage + '_id_item_' + no_id+'_'+count).defaultValue;
    // var existencias = $('#refer_'+ no_id).val();
    var existencias = cleanNumber($('#quantityExist_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val());
    if (existencias!='='&&existencias>0) {
      if (cleanNumber(cantidad_cambiada) > cleanNumber(existencias)) {
        toastr.error("Existencias insuficientes", "Error");
        $('#ajax-modal2').modal('show');
        $('#id_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val(existencias);

        var anterior_precio = cleanNumber($('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val());
        //obtener el valor del input del precio
        var selling_price = cleanNumber($('#selling_storage_' + id_storage + '_id_item_' + no_id+'_'+count).val());
        //obtenemos el nuevo precio
        var total_temp = cleanNumber(selling_price) * cleanNumber(existencias);
        $('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val(total_temp.format(2));

        //cambiar el precio total
        var general_temp = $('#total_general').val();
        general_temp = cleanNumber(general_temp) - anterior_precio + cleanNumber(total_temp);
        $('#total_general').val(general_temp.toFixed(2));

        var descuento = cleanNumber($('#discount_amount').val());
        setTotales(general_temp,descuento);
        // var totalFormateado = monedaChange();
        // $('#total_general2').val(totalFormateado);
        // setPaymentValues(totalFormateado);
      }
    }
    else{
      if (existencias!='=' && !setItemKit(no_id, cantidad_cambiada, no_storage)) {
        toastr.error("Existencias insuficientes", "Error");
        $('#ajax-modal2').modal('show');
        $('#id_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val(0);

        var anterior_precio = cleanNumber($('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val());
        //obtener el valor del input del precio
        var selling_price = cleanNumber($('#selling_storage_' + id_storage + '_id_item_' + no_id+'_'+count).val());
        //obtenemos el nuevo precio
        var total_temp = cleanNumber(selling_price) * cleanNumber(0);
        $('#total_storage_' + no_storage + '_id_item_' + no_id+'_'+count).val(total_temp.toFixed(2));

        //cambiar el precio total
        var general_temp = $('#total_general').val();
        general_temp = cleanNumber(general_temp) - anterior_precio + cleanNumber(total_temp);
        $('#total_general').val(general_temp.toFixed(2));

        var descuento = cleanNumber($('#discount_amount').val());
        setTotales(general_temp,descuento);
        // var totalFormateado = monedaChange();
        // $('#total_general2').val(totalFormateado);
        // setPaymentValues(totalFormateado);
        inputQty.focus();
      }
      else if(existencias!='='){
        // updateReference(no_id, cantidad_cambiada, no_storage, valor_default);
        document.getElementById('id_storage_' + no_storage + '_id_item_' + no_id+'_'+count).defaultValue = cantidad_cambiada;
      }
    }
  });
  if (quotQuantity != 0){
    inputQty.value = quotQuantity;
    inputQty.defaultValue = quotQuantity;
  }
  else{
    if (max_quantity < 1){
      inputQty.value = max_quantity;
      inputQty.defaultValue = max_quantity;
    }
    else{
      inputQty.value = 1;
      inputQty.defaultValue = 1;
    }
  }
  inputQty.max = max_quantity;
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
  btnRemove.id = 'btndelete_storage_' + id_storage + '_id_item_' + id_item+'_'+contador;
  btnRemove.onclick = remove;
  btnRemove.name = max_quantity;
  btnRemove.innerText = 'X';
  return btnRemove;
}

function createInputHiddenIdProduct(id_item) {
  var inputHidden = document.createElement('input');
  inputHidden.type = 'hidden';
  inputHidden.name = 'id_product_' + contador;
  inputHidden.id = 'item_' + id_item;
  inputHidden.required = 'true';
  inputHidden.style.cssText = 'text-align:center;';
  inputHidden.min = 1;
  inputHidden.value = id_item;
  inputHidden.className = 'form-control _itemId';
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
  inputHidden.className = 'form-control _itemStorage';
  return inputHidden;
}

function createInputHiddenQuantityExist(max_quantity, id_storage, id_item) {
  var inputHidden = document.createElement('input');
  inputHidden.type = 'hidden';
  inputHidden.id = 'quantityExist_storage_' + id_storage + '_id_item_' + id_item+'_'+contador;
  inputHidden.required = 'true';
  inputHidden.style.cssText = 'text-align:center;';
  inputHidden.min = 1;
  inputHidden.value = max_quantity;
  inputHidden.className = 'form-control _itemExist';
  return inputHidden;
}

function createInputSub_total(selling_price, id_item, id_storage, quotQuantity, max_qty) {
  var inputQty = document.createElement('input');
  inputQty.type = 'input';
  inputQty.id = 'total_storage_' + id_storage + '_id_item_' + id_item+'_'+contador;
  inputQty.name = 'total_' + contador;
  inputQty.className = "form-control _inputSubTotal";
  // inputQty.required = 'true';
  inputQty.style.cssText = 'text-align:center;';
  if (quotQuantity!=0){
    inputQty.value = cleanNumber(selling_price*quotQuantity).toFixed(2);
  }
  else{
    if (max_qty < 1){
      inputQty.value = cleanNumber(selling_price * max_qty);
    }
    else{
      inputQty.value = selling_price;
    }
  }
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
function monedaChangeParam(inputNum,cif = 3, dec = 2) {
  // tomamos el valor que tiene el input
  // let inputNum = document.getElementById('total_general').value
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
      $.each(data, function(i, item) /* Recorremos los hijos del kit */
      {
        //  var cant_max = cleanNumber($('#refer_' + data[i].item_id).attr("name"));
        if ($('#id_storage_' + id_storage + '_id_item_' + data[i].item_id).length) {
          var cant_sel = $('#id_storage_' + id_storage + '_id_item_' + data[i].item_id).val();
        } else {
          var cant_sel = 0;
        }
        if (cleanNumber(quantity * data[i].quantity) > cleanNumber(data[i].existencia - cant_sel)) {
          x = false;
          return;
        }
      });
    },
    error:function(error){
      console.log('new_sale.js setItemKit '+error);
    }
  });
  if (!x){
    toastr.error("Existencias insuficientes para completar el combo");
  }
  return x;
}

function updateReference(id_item, quantity, id_storage, defaultValue) {
  // console.log(defaultValue);
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
        var cant_max = cleanNumber($('#refer_' + data[i].item_id).val());
        var newRefer = cleanNumber(cant_max-data[i].quantity*quantity);
        $('#refer_'+data[i].item_id).val(newRefer);
      });
    },
    error:function(error){
      console.log('new_sale.js updateReference '+error);
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
        var cant_max = cleanNumber($('#refer_' + data[i].item_id).val());
        $('#refer_'+data[i].item_id).val(cant_max+(quantity*data[i].quantity));
      });
    },
    error:function(error){
      console.log('new_sale.js removeRecerence error '+error);
    }
  });
}
function setPaymentValues(monto)
{
  monto = monto.replace(/[\$,Q,]/g, '');
  // console.log(monto);
  $('#amount').val(monto);

  if ($('#id_pago').children(':selected').attr('type')==1){
    $('#paid').val(0.00);
  }
  else {
    $('#paid').val(monto);
  }
  // $('#paid').val(monto);

  $('#change').val('0.00');
}

function setTotales(subtotal,discount)
{
  const  pct = document.getElementById('discount_pct').value;
  // if (!pct) {
  //   pct =0;
  // }
  // console.log('pct '+pct);
  const monto = cleanNumber(subtotal * (pct/100)).toFixed(2);
  // console.log('monto '+monto);
  discount_amount = document.getElementById('discount_amount');
  discount_amount.value = monto;

  $('#total_general1').val(monedaChangeParam(subtotal.toFixed(2)));
  var totalFormateado = monedaChangeParam(cleanNumber(subtotal-monto).toFixed(2));
  $('#total_general2').val(totalFormateado);
  setPaymentValues(totalFormateado);
}

function cleanValues()
{
  $('#total_general').val(0.00);
  $('#total_general1').val(0.00);
  $('#total_general2').val(0.00);
  $('#discount_pct').val(0.00);
  $('#discount_amount').val(0.00);
  $('#amount').val(0.00);
  // $('#paid').val(0.00);
  $('#change').val('0.00');
  contador=0;
  $('#item_quantity').val(contador);

}
