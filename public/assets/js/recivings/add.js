var contador = 0;

function add(button) {
    var row = button.parentNode.parentNode;
    var cells = row.querySelectorAll('td:not(:first-of-type),td:not(:last-of-type)');
    var id_item = button.id;
    var lista = {
        id: button.id,
        upc_ean_isbn: cells[0].innerText,
        item_name: cells[1].innerText,
        cost_price: cells[4].innerText.replace(",", "").replace("Q", "").replace(" ", ""),
        quantity: cells[3].innerText,
        low_price: document.getElementById("low_price_" + id_item).value,
        is_kit: document.getElementById("is_kit_" + id_item).value
    };

    // alert(cells[0].innerText + ' ' + cells[1].innerText+ ' ' +cells[2].innerText+ ' ' +cells[3].innerText+ ' ' +cells[4].innerText+ ' ' +cells[5].innerText+ ' 6 ' +cells[6].innerText);
    // return;

    agregar(lista);
    // $("#modal-products").modal('hide');
    // $(".modal-backdrop").remove();

}


function agregar(lista) {
    id_storage = 1;
    var id_item = lista.id;
    var datos_fila = [];
    datos_fila[0] = id_item;
    datos_fila[1] = lista.upc_ean_isbn;
    datos_fila[2] = lista.item_name;
    datos_fila[3] = lista.cost_price;
    var quantity = datos_fila[4] = lista.quantity;
    datos_fila[5] = lista.low_price;
    datos_fila[6] = lista.is_kit;


    if ($('#id_storage_' + id_storage + '_id_item_' + id_item).length) {
        var cantidad_actual = parseFloat($('#id_storage_' + id_storage + '_id_item_' + id_item).val());

        updateQuantity(id_item, cantidad_actual, id_storage);


    } else {
        addFirstRow(datos_fila, id_item, 1);
    }
    /* Borramos campo de busqueda y le enviamos el foco */
    document.getElementById('autocom').value = '';
    document.getElementById('codigo').value = '';
    // $( "#txtHint").html("");
    $("#codigo").focus();
}

function addFirstRow(cells, id_item, id_storage) {
    contador++;
    // console.log(' datos fila antes de agregar '+cells);
    addToCartTable(cells, id_item, id_storage);
    $('#item_quantity').val(contador);
    //hacemos la suma para el nuevo total
    var total_id_item = $('#total_storage_' + id_storage + '_id_item_' + id_item).val();
    total_temp = parseFloat($('#total_general').val()) + parseFloat(total_id_item);
    $('#total_general').val(total_temp.toFixed(2));
    var totalFormateado = monedaChange();
    $('#total_general2').val(totalFormateado);

    setPaymentValues(totalFormateado);
}

function remove() {
    // console.log(this);
    // btnRemove.id='btndelete_storage_'+id_storage+'_id_item_'+id_item;
    var id = this.id;
    var string_no_id = id.split("_");
    no_storage = string_no_id[2];
    no_id = string_no_id[5];
    if (this.name == 'Kit') {
        removeReference(no_id, no_storage, parseFloat($('#id_storage_' + no_storage + '_id_item_' + no_id).val()));
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
    setPaymentValues(totalFormateado);
    //eliminamos el elemento y agregamos restamos el contador nuevo
    var row = this.parentNode.parentNode;
    document.querySelector('#target tbody').removeChild(row);
    contador--;
    $('#item_quantity').val(contador);
}

function addToCartTable(cells, id_item, bodega_id) {
    // console.log(' addtocarttable '+cells);
    //  var name = cells[0].innerText;
    var codigo = cells[1];
    var name = cells[2];
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
    newRow.appendChild(createCell(codigo, "10px"));
    newRow.appendChild(createCell(name, "10px"));

    var cellSellingPrice = createCell();
    cellSellingPrice.appendChild(createInputSelling_price(selling_price, min_price, id_item, id_storage));
    newRow.appendChild(cellSellingPrice);
    // newRow.appendChild(createCell(name_storage));
    // createInput_cost

    //CREAR INPUT DE COSTO PARA REALIZAR EL PRORRATEO
    var cellCostnNew = createCell();
    cellCostnNew.appendChild(createInput_cost(selling_price, id_item, id_storage));
    newRow.appendChild(cellCostnNew);

    //input cantidad
    var cellInputQty = createCell();
    cellInputQty.appendChild(createInputQty(max_quantity, id_item, id_storage));
    newRow.appendChild(cellInputQty);
    //Precio

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


function createInputSelling_price(selling_price, low_price, id_item, id_storage) {
    var inputQty = document.createElement('input');
    inputQty.type = 'text';
    inputQty.id = 'selling_storage_' + id_storage + '_id_item_' + id_item;
    inputQty.name = 'selling_price_' + contador;
    inputQty.required = 'true';
    inputQty.className = 'form-control';
    // inputQty.style.cssText = 'text-align:center;';
    inputQty.value = selling_price;
    inputQty.step = "0.000001";
    inputQty.style.width = '100%';
    new Cleave(inputQty, {
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'none'
    });
    // inputQty.className='form-control';
    inputQty.addEventListener('change', function () {

        var id = this.id;
        string_no_id = id.split("_");
        no_storage = string_no_id[2];
        no_id = string_no_id[5];
        // var low_price = parseFloat($('#minprice_'+no_id).val());
        var costo_unitario = $('#total_cost_' + no_storage + '_id_item_' + no_id);
        var precio_cambio = $('#' + id).val();
        if (precio_cambio == "" || parseFloat(precio_cambio) <= 0) {
            $('#' + id).val(selling_price);
            costo_unitario.val(selling_price);
            var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();

            var cantidad_total = $('#id_storage_' + id_storage + '_id_item_' + no_id).val();
            var total_temp = parseFloat(cantidad_total) * parseFloat(selling_price);
            $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));
            $('#total_storage_' + no_storage + '_id_item_' + no_id).attr("oldPrice",total_temp.toFixed(2));
            var general_temp = $('#total_general').val();
            general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
            // $('#total_general').val(general_temp.toFixed(2));
            // var totalFormateado = monedaChange();
            // $('#total_general2').val(totalFormateado);
        } else {
            var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
            var cantidad_total = $('#id_storage_' + id_storage + '_id_item_' + no_id).val();
            var total_temp = parseFloat(cantidad_total) * parseFloat(precio_cambio);
            $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));
            $('#total_storage_' + no_storage + '_id_item_' + no_id).attr("oldPrice",total_temp.toFixed(2));
            var general_temp = $('#total_general').val();
            general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
            costo_unitario.val(precio_cambio);

            // $('#total_general').val(general_temp.toFixed(2));
            // var totalFormateado = monedaChange();
            // $('#total_general2').val(totalFormateado);
        }
        $('#total_general').val(general_temp.toFixed(2));
        var totalFormateado = monedaChange();
        $('#total_general2').val(totalFormateado);
        setPaymentValues(totalFormateado);

    });
    return inputQty;
}

function createInputQty(max_quantity, id_item, id_storage) {
    var inputQty = document.createElement('input');
    inputQty.type = 'text';
    // inputQty.id='cantidad_'+id_item;
    inputQty.id = 'id_storage_' + id_storage + '_id_item_' + id_item;
    inputQty.name = 'cantidad_' + contador;
    inputQty.required = 'true';
    inputQty.defaultValue = "1";
    inputQty.style.cssText = 'text-align:center;';
    new Cleave(inputQty, {
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'none'
    });
    inputQty.addEventListener('change', function () {
        var id = this.id;
        string_no_id = id.split("_");
        no_storage = string_no_id[2];
        no_id = string_no_id[5];

        var cantidad_cambio = $('#' + id).val();
        if (cantidad_cambio == "" || cantidad_cambio == 0) {
            $('#' + id).val(1);
            var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
            //obtener el valor del input del precio
            var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + no_id).val();
            //obtenemos el nuevo precio
            var total_temp = parseFloat(selling_price) * parseFloat(1);
            $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));
            $('#total_storage_' + no_storage + '_id_item_' + no_id).attr("oldPrice",total_temp.toFixed(2));

            //cambiar el precio total
            var general_temp = $('#total_general').val();
            general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
            // $('#total_general').val(general_temp.toFixed(2));

            // var totalFormateado = monedaChange();
            // $('#total_general2').val(totalFormateado);
        } else {

            //obtenos el total anterior del input sub_total
            var anterior_precio = $('#total_storage_' + no_storage + '_id_item_' + no_id).val();
            //obtener el valor del input del precio
            var selling_price = $('#selling_storage_' + id_storage + '_id_item_' + no_id).val();
            //obtenemos el nuevo precio
            var total_temp = parseFloat(selling_price) * parseFloat(cantidad_cambio);
            $('#total_storage_' + no_storage + '_id_item_' + no_id).val(total_temp.toFixed(2));
            $('#total_storage_' + no_storage + '_id_item_' + no_id).attr("oldPrice",total_temp.toFixed(2));
            //cambiar el precio total
            var general_temp = $('#total_general').val();
            general_temp = parseFloat(general_temp) - anterior_precio + parseFloat(total_temp);
            // $('#total_general').val(general_temp.toFixed(2));

            // var totalFormateado = monedaChange();
            // $('#total_general2').val(totalFormateado);
        }
        $('#total_general').val(general_temp.toFixed(2));

        var totalFormateado = monedaChange();
        $('#total_general2').val(totalFormateado);
        setPaymentValues(totalFormateado);
    });

    inputQty.addEventListener('blur', function () {
        var id = this.id;
        var string_no_id = id.split("_");
        var no_storage = string_no_id[2];
        var no_id = string_no_id[5];
        var cantidad_cambiada = $('#id_storage_' + no_storage + '_id_item_' + no_id).val();
        var valor_default = document.getElementById('id_storage_' + no_storage + '_id_item_' + no_id).defaultValue;
        // var existencias = $('#refer_'+ no_id).val();
        var existencias = $('#quantityExist_storage_' + no_storage + '_id_item_' + no_id).val();
        document.getElementById('id_storage_' + no_storage + '_id_item_' + no_id).defaultValue = cantidad_cambiada;
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
    inputHidden.className = 'form-control id_products';
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
    // inputQty.disabled = 'true';
    inputQty.className = 'form-control';
    inputQty.style.width = '100%';
    inputQty.setAttribute("oldPrice", selling_price);
    new Cleave(inputQty, {
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'none'
    });
    inputQty.addEventListener('change', function () {
        var oldPrice = cleanNumber($(this).attr("oldPrice"));
        var general_temp = cleanNumber($('#total_general').val());
        var total_temp = cleanNumber($(this).val());
        var cantidad = cleanNumber($('#id_storage_' + id_storage + '_id_item_' + id_item).val());
        //CAMBIANDO COSTO UNITARIO
        //ID COSTO UNITARIO: 'selling_storage_' + id_storage + '_id_item_' + id_item
        //ID DE CANTIDAD:
        if (total_temp <= 0){
            $(this).val(oldPrice.toFixed(2));
            toastr.error("El precio del producto no puede ser 0.");
            return;
        }
        var unit_cost = parseFloat(total_temp / cantidad);
        $('#selling_storage_' + id_storage + '_id_item_' + id_item).val(unit_cost.toFixed(2));
        $('#total_cost_' + id_storage + '_id_item_' + id_item).val(unit_cost.toFixed(2));
        general_temp = cleanNumber((parseFloat(general_temp) - oldPrice + parseFloat(total_temp)));
        $('#total_general').val(general_temp.toFixed(2));
        var totalFormateado = monedaChange();
        $('#total_general2').val(totalFormateado);
        $(this).attr("oldPrice", total_temp.toFixed(2));
        setPaymentValues(totalFormateado);
    });
    return inputQty;
}

function createInput_cost(selling_price, id_item, id_storage) {
    var inputQty = document.createElement('input');
    inputQty.type = 'input';
    inputQty.id = 'total_cost_' + id_storage + '_id_item_' + id_item;
    inputQty.name = 'newcosto_' + contador;
    // inputQty.required = 'true';
    inputQty.style.cssText = 'text-align:center;';
    inputQty.value = selling_price;
    // inputQty.disabled = 'true';
    inputQty.style.width = '100%';
    inputQty.readOnly = true;
    inputQty.className = 'form-control';
    return inputQty;
}

function monedaChange(cif = 3, dec = 2) {
    // tomamos el valor que tiene el input
    let inputNum = cleanNumber(document.getElementById('total_general').value);
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

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
        console.log(e)
    }
};

function updateQuantity(id_item, cantidad_actual, id_storage) {
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
    setPaymentValues(totalFormateado);


}

function valid_correlative() {
    var id_serie = $('#id_serie').val();
    if (id_serie != 0) {
        $.ajax({
            method: 'get',
            url: APP_URL + '/existCorrelative/receivings/' + id_serie,
            success: function (data) {
                $('#id_correlativo').val(data);
                /**
                 * Cuando cambie de serie/documento
                 * Modificar la descripción del pago
                 * */
                var doc_texto = $( "#id_serie option:selected" ).text();
                $('#description').val('Pago: '+doc_texto+' '+data);
            },
            error: function (error) {
                console.log(error);
            }
        });
    } else {
        $('#id_correlativo').val(0);
    }
}

function setPaymentValues(monto) {
    monto = monto.replace(/[\$,Q,]/g, '');
    $('#amount').val(monto);
    $('#paid').val(monto);

    $('#change').val('0.00');
}
