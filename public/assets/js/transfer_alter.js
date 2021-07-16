var contador = 0;
var total_cost = 0;
var total_item_quantity = 0;
var item_quantity = document.getElementById('item_quantity');
item_quantity.value = contador;
var listado_items = [];
function get_cart_items () {
    let detalles = document.getElementsByClassName("_rowItem");
    for (let row of detalles){
        let producto = row.getElementsByClassName("_itemId")[0].value;
        let cantidad = row.getElementsByClassName("_itemQuantity")[0].value;
        listado_items.push({"item_id":producto, "quantity":cantidad});
    }
    $('#last_items').val(JSON.stringify(listado_items));
}


/**
 * VERIFICA Y RESTAURA LOS PRODUCTOS AGREGADOS AL CARRO DE COMPRAS
 * SIEMPRE QUE SE HAYA CAMBIADO DE TIPO DE PRECIO A APLICAR
 */
function update_cart_items(){
    if ($('#type_change').val()=="1"){
        let items = JSON.parse($('#last_items').val());
        let price_id = $('#price_id').val();
        let bodega = $('#id_storage_origins').val();
        items.forEach(function (item) {
            $.get( APP_URL+'/api/transfers/search_id?id='+item.item_id+'&price='+price_id+'&bodega='+bodega, function( data ) {
                if (data!=""){
                    agregar(data, item.quantity);
                    $("#codigo").val('');
                    $("#codigo").focus();
                    //agregar(data);/*Llamamos la funcion para agregar a la tabla de productos*/
                }
                hideLoading();
            });
        });
    }
    else{
        console.log("lo que se cambió fue la bodega");
    }
}

function agregar(item_json, qty = 1){
    var id_item = item_json.id;
    if (document.getElementById('cantidad_' + id_item)) {
        var cantidad_existente = document.getElementById('cantidad_' + id_item);
        var cant = (+cantidad_existente.value);
        cantidad_existente.value = cant + 1;
        const e = new Event("change");
        cantidad_existente.dispatchEvent(e);
    } else {
        addToCartTotable(item_json, item_json.id, 'json', qty);
        contador++;
        var item_quantity = document.getElementById('item_quantity');
        item_quantity.value = contador;
    }
}

function add(button) {
    var row = button.parentNode.parentNode;
    var cells = row.querySelectorAll('td:not(:last-of-type)');
    var id_item = button.id;
    if (document.getElementById('cantidad_' + id_item)) {
        var cantidad_existente = document.getElementById('cantidad_' + id_item);
        var cant = (+cantidad_existente.value);
        cantidad_existente.value = cant + 1;
        const e = new Event("change");
        cantidad_existente.dispatchEvent(e);
    } else {
        addToCartTotable(cells, id_item);
        contador++;
        var item_quantity = document.getElementById('item_quantity');
        item_quantity.value = contador;
    }
}

$('#add_item_btn').click(function () {
    if ($('#id_storage_origins').val()==''){
        toastr.error('Debe seleccionar una bodega de origen.');
        $('#id_storage_origins').focus();
    }
});

function addToCartTotable(cells, id_item, type = 'row', qty=1) {
    if (type == 'row'){
        var code = cells[0].innerText;
        var name = cells[1].innerText;
        var stock = cells[2].innerText;
        var cost = cells[3].innerText;
    }
    else{
        var code = cells.code;
        var name = cells.item_name;
        var stock = cells.quantity;
        var cost = cells.cost_price;
    }
    max_quantity = stock;
    if (max_quantity < 1){
        total_cost += (cleanNumber(cost * max_quantity));
        total_item_quantity += (cleanNumber(max_quantity));
    }
    else{
        total_cost += (cleanNumber(cost * qty));
        total_item_quantity += (cleanNumber(qty));
    }
    var newRow = document.createElement('tr');
    newRow.className = "_rowItem";
    newRow.appendChild(createCell(code));
    newRow.appendChild(createCell(name));
    var cellInputQty = createCell();
    cellInputQty.appendChild(createInputQty(max_quantity, id_item, +cost, qty));
    var cellRemoveBtn = createCell();
    cellRemoveBtn.appendChild(createRemoveBtn(id_item));
    var cellHidden = createCell();
    cellHidden.appendChild(createInputHidden(id_item));
    var cellHiddenCost = createCell();
    cellHidden.appendChild(createInputHiddenCost(id_item, cost));
    var cellHiddenSubTotal = createCell();
    cellHiddenSubTotal.appendChild(createInputSub_total(cost, id_item, qty, max_quantity));

    newRow.appendChild(cellInputQty);
    newRow.appendChild(cellHidden);
    newRow.appendChild(cellHiddenSubTotal);
    newRow.appendChild(cellRemoveBtn);
    newRow.appendChild(cellHiddenCost);
    //newRow.appendChild(createCell(stock));
    document.querySelector('#target tbody').appendChild(newRow);
}

function createCell(text) {
    var td = document.createElement('td');
    if (text) {
        td.innerText = text;
    }
    return td;
}

/**
 * CREAR INPUT PARA SUBTOTAL DE LINEA
 * @param cost
 * @param id_item
 * @returns {HTMLInputElement}
 */
function createInputSub_total(cost, id_item, qty, max_qty) {
    var inputQty = document.createElement('input');

    inputQty.type = 'text';
    inputQty.id ='subtotal_id_item_' + id_item;
    inputQty.name = 'total_' + contador;
    // inputQty.required = 'true';
    inputQty.style.cssText = 'text-align:center;';
    if (max_qty < 1){
        inputQty.value = cleanNumber(cost * max_qty).toFixed(2);
    }
    else{
        inputQty.value = cleanNumber(cost * qty).toFixed(2);
    }
    inputQty.disabled = 'true';
    inputQty.className = 'form-control';
    inputQty.style.width = '100%';
    new Cleave(inputQty, {
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'none'
    });
    return inputQty;
}

function updateCost() {
    if (total_cost < 0) {
        total_cost = 0;
    }
    $('#total_amount').val(cleanNumber(total_cost).toFixed(2));
    $('#total_cost_lbl').text('');
    $('#total_cost_lbl').text("Q " + cleanNumber(total_cost).toFixed(2));
    $('#total_items_lbl').text('');
    $('#total_items_lbl').text(cleanNumber(total_item_quantity).toFixed(2));
    $('#totalCost').val(cleanNumber(total_cost).toFixed(2));
}


function createInputQty(max_quantity, id_item, cost, qty) {
    var inputQty = document.createElement('input');
    inputQty.type = 'text';
    inputQty.id = 'cantidad_' + id_item;
    inputQty.name = 'cantidad_' + contador;
    inputQty.required = 'true';
    inputQty.style.cssText = 'text-align:center;';
    inputQty.setAttribute('cost', cost);
    inputQty.min = 0.01;
    new Cleave(inputQty, {
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'none'
    });
    inputQty.addEventListener('change', function () {
        var id = this.id;
        var cantidad_cambio = document.getElementById(id);
        if (cantidad_cambio.value == "" || (cleanNumber(cantidad_cambio.value) <= 0)) {
            cantidad_cambio.value = cantidad_cambio.defaultValue;
        } else {
            if (cleanNumber(cantidad_cambio.value) > cleanNumber(this.max)) {
                this.value = this.defaultValue;
                toastr.error("Existencias insuficientes.", "Error");
            } else {
                let cost = document.getElementById('id_productcost_'+id_item).value;
                let calc = cleanNumber((+cost * +cantidad_cambio.value));
                total_cost -= cleanNumber((+cost * +cantidad_cambio.defaultValue));
                total_cost += cleanNumber(calc);
                total_item_quantity -= cleanNumber(cantidad_cambio.defaultValue);
                total_item_quantity += cleanNumber(cantidad_cambio.value);
                cantidad_cambio.defaultValue = +cantidad_cambio.value;
                $('#subtotal_id_item_'+id_item).val(calc.toFixed(2));
                updateCost();
            }
        }
    });
    if (max_quantity < 1){
        inputQty.value = max_quantity;
        inputQty.defaultValue = max_quantity;
    }
    else{
        inputQty.value = qty;
        inputQty.defaultValue = qty;
    }

    inputQty.max = max_quantity;
    inputQty.className = 'form-control _itemQuantity';
    inputQty.style.width = '50%';
    updateCost();
    return inputQty;
}

function createInputHidden(id_item) {
    var inputHidden = document.createElement('input');

    inputHidden.type = 'hidden';
    inputHidden.name = 'id_product_' + contador;
    inputHidden.required = 'true';
    inputHidden.style.cssText = 'text-align:center;';
    inputHidden.min = 1;
    inputHidden.value = id_item;
    inputHidden.className = 'form-control _itemId';
    return inputHidden;
}

function createInputHiddenCost(id_item, cost) {
    var inputHidden = document.createElement('input');
    inputHidden.type = 'text';
    inputHidden.id = 'id_productcost_'+id_item;
    inputHidden.name = 'id_productcost_' + contador;
    inputHidden.min = 1;
    inputHidden.value = cost;
    inputHidden.className = 'form-control';
    inputHidden.setAttribute('defaultvalue', cost);
    inputHidden.setAttribute('contador', contador);
    new Cleave(inputHidden, {
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'none'
    });
    inputHidden.addEventListener('change', function () {
        var id = this.id;
        var costo_cambio = document.getElementById(id);
        if (costo_cambio.value == "" || (cleanNumber(costo_cambio.value) < 0)) {
            costo_cambio.value = costo_cambio.getAttribute('defaultvalue');
        } else {
            let cantidad = cleanNumber(document.getElementById('cantidad_'+id_item).value);
            let calc = cleanNumber((+cantidad * +costo_cambio.value));
            total_cost -= cleanNumber((+cantidad * +costo_cambio.getAttribute('defaultvalue')));
            total_cost += calc;
            costo_cambio.setAttribute('defaultvalue',cleanNumber(costo_cambio.value));
            $('#subtotal_id_item_'+id_item).val(calc.toFixed(2));
            updateCost();
        }
    });
    return inputHidden;
}

function createRemoveBtn(item_id) {
    var btnRemove = document.createElement('button');
    btnRemove.className = 'btn btn-xs btn-danger';
    btnRemove.onclick = remove;
    btnRemove.setAttribute('id_item', item_id);
    btnRemove.innerText = 'Eliminar';
    return btnRemove;
}

function remove() {
    /*
    DESCONTAR CANTIDAD
    * */
    var input = document.getElementById('cantidad_' + $(this).attr('id_item'));
    var qty = input.value;
    var cost = document.getElementById('id_productcost_'+$(this).attr('id_item')).value;
    var total = qty * cost;
    total_cost -= cleanNumber(total).toFixed(2);
    total_item_quantity -= cleanNumber(qty);
    updateCost();
    var row = this.parentNode.parentNode
    document.querySelector('#target tbody')
        .removeChild(row);
    contador--;
    var item_quantity = document.getElementById('item_quantity');
    item_quantity.value = contador;
}

function sendForm() {
    showLoading("Guardando traslado de bodega...");
    $('select').select2({
        disabled: false
    });
    $('#price_id').attr('form', 'addCustomerPayment');
    $('#confirmSave').modal('hide');
    document.getElementById('addCustomerPayment').submit();
}

btn_save_transfer = document.getElementById('btn_save_transfer');
btn_save_transfer.addEventListener('click', function () {
    var id_storage_destination = document.getElementById('id_storage_destination');
    var id_document = document.getElementById('id_document');
    var contador = document.getElementById('item_quantity').value;
    // alert(contador);
    selected = id_storage_destination.value;
    selected2 = id_document.value;
    if (selected2 == 0) {
        id_document.focus();
        toastr.error("Debe seleccionar un documento para la transacción.");
    }
    else if($('#price_id').val()==null){
        toastr.error("Debe seleccionar un tipo de precio a aplicar");
        $('#tabTransfer').click();
        $('#price_id').focus();
    } else if($('#id_storage_origins').val()==''){
        toastr.error("Debe seleccionar una bodega de origen.");
        $('#tabTransfer').click();
        $('#id_storage_origins').focus();
    }else if (selected == 0) {
        id_storage_destination.focus();
        toastr.error("Debe seleccionar una bodega destino para su transacción");
        $('#tabTransfer').click();
    }else if($('#account_origin').val()==''){
        $('#account_origin').focus();
        toastr.error("Debe seleccionar una cuenta a acreditar el monto del traslado");
        $('#tabPayment').click();
    } else if(cleanNumber($('#_total_debit').val())!=cleanNumber($('#total_amount').val())){
        $('#tabPayment').click();
        toastr.error("La sumatoria de pagos no coincide con el monto total del traslado.");
    } else if ((contador == 0) || (contador < 0)) {
        toastr.error("Debe agregar productos al traslado...");
        //  document.getElementById("message_div").style.display = 'inline';
        //  var mensaje_error = document.getElementById("error_message");
        //  mensaje_error.innerHTML = "Debe agregar productos para continuar!";
        //  setTimeout(function(){
        //  document.getElementById("message_div").style.display = "none";
        // }, 3000);
        // alert('no hay articulos');
        // window.setTimeout(document.getElementById("message_div").style.display = 'none', 3000);
    } else {
        if ($('#json_payments').val() != undefined){
            $('#json_payments').val(JSON.stringify(payments));
        }
        $('#confirmSave').modal('show');
        // document.getElementById('id_form_save').submit();
    }
});


$('#id_document').change(function () {
    $('#documento').val($(this).val());
});

$('#id_storage_destination').change(function () {
    $('#bodega_destino').val($(this).val());
});

$('#comment').change(function () {
    $('#tsComment').val($(this).val());
});
