var pm = null;
$(document).ready(function(){
  $("#frmAddPrice").submit(function(e){
    return false;
  });
  pm = new PricesManager();
  setInputs();
  $('#btnAddPrice').click(function(){
    if (validate()) {
      pm.addPrice();
      clearInputs();
      $('#newItem').data('bootstrapValidator')
        .updateStatus($('#cost_price'), 'NOT_VALIDATED')
        .validateField($('#cost_price'));
    }
  });

  $('#btnEditPrice').click(function(){
    if (validate()) {
      pm.updatePrice();
      clearInputs();
      $('#newItem').data('bootstrapValidator')
        .updateStatus($('#cost_price'), 'NOT_VALIDATED')
        .validateField($('#cost_price'));
    }
  });
  $('#btnCleanInputs').click(function(){
    clearInputs();
  });

  $("#price_id").change(function (){
    var profit = cleanNumber($('select[name=price_id] option:selected').attr("profit"));
    var cost = cleanNumber(cost_price.value);
    var qty = cleanNumber(quantity.value);
    $("#profit").val(profit).trigger("input");
    selling_price.value = cleanNumber((cost*qty) * (1+(profit/100))).format(2);
  });

  profit.addEventListener('input', function (e){
    var prof = cleanNumber(e.target.value);
    var cost = cleanNumber(cost_price.value);
    var qty = cleanNumber(quantity.value);
    selling_price.value = cleanNumber((cost*qty) * (1+(prof/100))).format(2);
  });
  quantity.addEventListener('input', function (e){
    var prof = cleanNumber(profit.value);
    var cost = cleanNumber(cost_price.value);
    var qty = cleanNumber(e.target.value);
    selling_price.value = cleanNumber((cost*qty) * (1+(prof/100))).format(2);
  });
  selling_price.addEventListener('input', function (e){
    var selprice = cleanNumber(e.target.value);
    var qty = cleanNumber(quantity.value);
    var cost = cleanNumber(cost_price.value);
    profit.value = cleanNumber((((selprice/qty)/cost)-1)*100);
  });
  cost_price.addEventListener('input', function (e){
    var cost = cleanNumber(e.target.value);
    var qty = cleanNumber(quantity.value);
    if (cleanNumber(profit.value)>0){
      var prof = cleanNumber(e.target.value);
      selling_price.value = cleanNumber((cost*qty) * (1+(prof/100))).format(2);
    }
    else{
      var selprice = cleanNumber(e.target.value);
      profit.value = cleanNumber((((selprice/qty)/cost)-1)*100);
    }

    //RECALCULAR COSTOS CON NUEVO COSTO EN LISTADO DE cuantos_precios
    pm.details.forEach((item, i) => {
      item.selling_price = cleanNumber(item.quantity * (cleanNumber(1+(item.profit/100)) * cleanNumber(e.target.value))).toFixed(2);
    });
    pm.render();
  });
});

function setInputs(){
  new Cleave(quantity, {
    numeral: true,
    numeralPositiveOnly: true,
    numeralThousandsGroupStyle: 'none'
  });

  new Cleave(profit, {
    prefix: '% ',
    numeralThousandsGroupStyle: 'none',
    numeral: true
  });

  new Cleave(selling_price, {
    prefix: 'Q ',
    tailPrefix: true,
    numeral: true
  });
}

function clearInputs(){
  $("#unit_id").val(null).trigger("change");
  $("#price_id").val(null).trigger("change");
  quantity.value = null;
  quantity.value = null;
  profit.value = null;
  selling_price.value = null;
  default_price.checked = false;
  detail_id.value = null;
  btnAddPrice.style.display = 'inline';
  btnEditPrice.style.display = 'none';
  new Cleave(profit, {
    prefix: '% ',
    numeralThousandsGroupStyle: 'none',
    numeral: true
  });

  new Cleave(selling_price, {
    prefix: 'Q ',
    tailPrefix: true,
    numeral: true
  });
  $("#unit_id").focus();
}

function validate(){
  if (unit_id.value=="") {
    toastr.error("Debe seleccionar unidad de medida.")
    unit_id.focus();
    return false;
  }
  if (price_id.value=="") {
    toastr.error("Debe seleccionar un tipo de precio.")
    price_id.focus();
    return false;
  }
  if (cleanNumber(quantity.value)<=0) {
    toastr.error("Debe ingresar la cantidad de unidades.")
    quantity.focus();
    return false;
  }
  if (cleanNumber(profit.value)<=0) {
    toastr.error("Debe ingresar porcentaje de utilidad.")
    profit.focus();
    return false;
  }
  if (cleanNumber(selling_price.value)<=0) {
    toastr.error("Debe ingresar precio de venta.")
    selling_price.focus();
    return false;
  }

  /*
  RECORRER EL ARRAY DE ELEMENTOS AGREGADOS Y VER SI LA LLAVE COMPUESTA UNIDAD-PRECIO YA HA SIDO AGREGADA
  * */
  var counter = 0;
  var counterDefault = 0;
  pm.details.forEach(function (detail){
    if (unit_id.value == detail.unit_id && price_id.value == detail.price_id && (detail_id.value.length == 0 || detail.id != detail_id.value)){
      counter++;
    }
    if (default_price.checked == true && default_price.checked == detail.default && price_id.value == detail.price_id && (detail_id.value.length == 0 || detail.id != detail_id.value)){
      counterDefault++;
    }
  });

  if (counter>0){
    toastr.error("Ya se ha agregado una unidad con el mismo precio seleccionado.");
    return false;
  }
  if (counterDefault > 0){
    toastr.error("Ya existe otro precio seleccionado por defecto para el tipo de precio seleccionado.");
    return false;
  }
  return true;
}
