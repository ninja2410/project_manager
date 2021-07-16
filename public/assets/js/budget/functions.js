/**
 * INICIALIZA LOS COMPONENTES DRAGABLES Y DROPABLES
 */
function initialDragandDrop(){
    $('.header').draggable({
        revert: "invalid",
        stack: ".draggable",
        helper: 'clone'
    });

    $('.detail').draggable({
        revert: "invalid",
        stack: ".draggable",
        helper: 'clone'
    });

    $('#lServices > tbody > tr').draggable({
        revert: "invalid",
        stack: ".draggable",
        helper: 'clone'
    });
    $('#lProducts > tbody > tr').draggable({
        revert: "invalid",
        stack: ".draggable",
        helper: 'clone'
    });
    $('#lWildcards > tbody > tr').draggable({
        revert: "invalid",
        stack: ".draggable",
        helper: 'clone'
    });
}

/**
 * configurar el body del card principal para recibir dragables de tipo header
 */
var setDropableHeader = function () {
    $('.droppable').droppable({
        accept: ".header",
        drop: function (event, ui) {
            var droppable = $(this);
            var draggable = ui.draggable;
            // Move draggable into droppable
            let clon = draggable.clone();
            let title = clon[0].getElementsByClassName('header_title');
            clon[0].setAttribute('header_counter', counter_headers);
            clon[0].getElementsByClassName('btnDelete')[0].style.display='inline';
            clon[0].className += ' header_container header_container'+counter_headers;
            title[0].id = "h_"+counter_headers;
            clon.appendTo(droppable);

            budget.addHeader(new Header(counter_headers));

            $("#h_"+counter_headers).editable({
                type: 'text',
                pk: 1,
                title: 'Nombre del encabezado',
                validate: function(value) {
                    if($.trim(value) == '') {
                        return 'Debe ingresaar un valor.';
                    }
                }
            });
            //draggable.css({top: '5px', left: '5px'});
            $('.droppable').css({height: 'auto'});
            setDropableDetails();
            counter_headers++;
        }
    });
};

/**
 * Concifugar los bodys de los headers agregados para permitir dragables
 */
var setDropableDetails = function () {
    $('.detail_header').droppable({
        accept: ".detail",
        drop: function (event, ui) {
            var droppable = $(this);
            var draggable = ui.draggable;
            // Move draggable into droppable
            let newClon = draggable.clone();

            //BUSCANDO ENCABEZADO
            let header_container = $(this).parent();
            let id_container = header_container[0].getAttribute('header_counter');

            //SETEANDO NUEVOS VALORES
            let enc = newClon[0].getElementsByTagName('p')[0].getElementsByTagName('a')[0];
            enc.setAttribute('href', '#h'+id_container+'_d'+counter_details);
            enc.setAttribute('header_counter', id_container);
            enc.setAttribute('detail_counter', counter_details);
            let divBody = newClon[0].getElementsByClassName('collapse')[0];
            let rows = newClon[0].getElementsByClassName('btnDelete');
            for (let row of rows){
                row.removeAttribute("style");
            }
            // newClon[0].getElementsByClassName('btnDelete')[0].className = 'col-lg-4 btn-primary rmvDiv';
            // newClon[0].getElementsByClassName('name_line_template')[0].className = 'col-lg-8 name_line_template';
            divBody.id = 'h'+id_container+'_d'+counter_details;
            divBody.setAttribute('header_counter', id_container);
            divBody.setAttribute('detail_counter', counter_details);
            newClon.appendTo(droppable);
            //draggable.css({top: '5px', left: '5px'});
            setDetailLineTemplate('allowServices', ['lServices']);
            setDetailLineTemplate('allowProducts', ['lProducts', 'lWildcards']);
            $('.detail_header').css({height: 'auto'});
            setxEditableInputs();
            counter_details++;
        }
    });
};
/**
 * Actualiza el precio de todos los productos agregados al presupuesto
 * @param class_item clase de articulo a actualizar
 */
var updateCostItems = function (class_item, value) {
    let elements = document.getElementsByClassName(class_item);
    for (let element of elements){
        let x = $(element).parents("tr");
        let span = $(element).parents("span")[0];
        $(span).removeClass("label-danger").addClass("label-info");
        element.textContent = cleanNumber(value).format(2);
        updateTotalRow(x[0], value);
    }
};

/**
 * Inicializa las etiquetas a como xeditables
 */
var setxEditableInputs = function(){
    $('.unit_cost').editable({
        type: 'number',
        url: APP_URL + '/update_budget_cost',
        title: 'Ingrese nuevo precio',
        params:function(params){
            var data = {};
            data['id'] = params.pk;
            data['value'] = params.value;
            data['change_all'] = $('#only_this').is(':checked');
            return data;
        },
        success: function(response, newValue) {
            //userModel.set('username', newValue); //update backbone model
            let x = $(this).parents("tr");
            let classupdate = 'item_'+($(this).attr("item_id"));
            updateCostItems(classupdate, newValue);
            // updateTotalRow(x[0], response);
        },
        validate: function(value) {
            if($.trim(value) == '') {
                return 'Debe ingresar un monto';
            }
            if (cleanNumber(value)<=0){
                return 'Debe ingresar un valor mayor que 0.';
            }
        }
    });

    $('.quantity_item').editable({
        type: 'number',
        //url: APP_URL + '/item/update_budget_cost',
        title: 'Ingrese cantidad',
        _token: $('#token_').val(),
        //pk: $(this).attr('item_id'),
        success: function(response, newValue) {
            //userModel.set('username', newValue); //update backbone model
            let x = $(this).parents("tr");
            updateTotalRow(x[0], null,newValue);
        },
        validate: function(value) {
            if($.trim(value) == '') {
                return 'Debe ingresar una cantidad.';
            }
            if (cleanNumber(value)<=0){
                return 'Debe ingresar un valor mayor que 0.';
            }
        }
    });

    $('.Quantity_line').editable({
        type: 'number',
        //url: APP_URL + '/item/update_budget_cost',
        title: 'Ingrese cantidad',
        _token: $('#token_').val(),
        //pk: $(this).attr('item_id'),
        success: function(response, newValue) {
            //userModel.set('username', newValue); //update backbone model
            let x = $(this).parents("tr");
            let nv = cleanNumber(newValue);
            // updateTotalRow(x[0], null,newValue);
            let total = updateQuantityLine(x[0], nv);
            x[0].parentNode.getElementsByClassName("Subtotal_line")[0].textContent = "Q "+cleanNumber(total).format(2);
            x[0].parentNode.getElementsByClassName("Total_line")[0].textContent = "Q "+cleanNumber(total/nv).format(2);


        },
        validate: function(value) {
            if($.trim(value) == '') {
                return 'Debe ingresar una cantidad.';
            }
            if (cleanNumber(value)<=0){
                return 'Debe ingresar un valor mayor que 0.';
            }
        }
    }).on('hidden', function (e, params) {
        // let x = $(this).parents("tr");
        // let tabla = $(x).parents("table")[0];

    });
    $('.example-popover').popover({
        trigger: 'focus'
    })
};

/**
 * Actualiza el subtotal de item y recalcula el total de tabla
 * @param row linea de la tabla a actualizar
 * @param cost parametro opcional para configurar el nuevo costo
 * @param qty parametro opcional para configuar la nueva cantidad
 * @param updtRef determinar si se modifica la referencia de la cantidad de la linea
 */
var updateTotalRow = function (row, cost = null, qty=null, updtRef=true) {
    let quantity = (qty == null)? cleanNumber(row.getElementsByClassName('quantity_item')[0].textContent) : cleanNumber(qty);
    let price = (cost == null)? cleanNumber(row.getElementsByClassName('unit_cost')[0].textContent) : cleanNumber(cost);
    if (updtRef){
        row.getElementsByClassName('refer_quantity_item')[0].value = cleanNumber(qty);
    }
    row.getElementsByClassName('sut_total')[0].textContent = 'Q '+cleanNumber(quantity*price).format(2);
    /**
     * ACTUALIZANDO EL TOTAL DE LA TABLA
     */
    let tabla = $(row).parents("table")[0];
    return updateTotalTable(tabla);
};

var evalLineTemplates = function () {
    budget.hasError = false;
    let renglones = document.getElementById('budgetDetailContainer').getElementsByClassName("lt_detail");
    for (let renglon of renglones){
        evalTotal(renglon);
    }
};

/**
 * Evalua miltiplo de total y precio unitario
 * @param container
 */
var evalTotal = function (container) {
    let btnError = container.getElementsByClassName("total_error")[0];
    let btnSuccess = container.getElementsByClassName("total_success")[0];
    btnError.classList.remove("hidden");
    btnSuccess.classList.remove("hidden");
    let total = cleanNumber(container.getElementsByClassName("Subtotal_line")[0].textContent);
    let quantity = cleanNumber(container.getElementsByClassName("Quantity_line")[0].textContent);
    let pUnit = cleanNumber(total/quantity);
    let eval = pUnit * quantity;

    // console.log(`eval: ${eval} - ${total}`);
    if (eval == total){
        btnError.classList.add("hidden");
        btnSuccess.classList.remove("hidden");
    }
    else{
        let flag = false;
        let comodin = 0;
        while (!flag){
            comodin += parseFloat(0.05);
            let tmpPU = cleanNumber((total+comodin) / quantity).toFixed(2);
            if (cleanNumber(total + comodin).toFixed(2) == cleanNumber(tmpPU * quantity).toFixed(2)){
                flag = true;
                let messaage = `Debe agregar un comodín para cuadrar el total del renglón, 
                Cantidad de comodín sugerido: ${comodin.toFixed(2)}`;
                btnError.setAttribute("data-content", messaage);
            }
        }
        budget.hasError = true;
        btnError.classList.remove("hidden");
        btnSuccess.classList.add("hidden");
    }
}

/**
 * ACTUALIZAR EL TOTAL DE LA TABLA Y EL TOTAL DEL RENGLON
 * @param table tabla a actualizar
 */
var updateTotalTable = function (table) {
    let total_rows = table.rows;
    let _tmp_total = 0;
    for (let row of total_rows){
        let tds = (row.getElementsByClassName('sut_total'));
        if (tds.length > 0){
            // console.log(((tds[0].textContent)));
            _tmp_total += (cleanNumber(tds[0].textContent));
        }
    }
    if (table.getElementsByClassName("subtotal_table").length > 0){
        table.getElementsByClassName("subtotal_table")[0].textContent = 'Q '+_tmp_total.format(2);
    }

    /**
     * ACTUALIZAR EL TOTAL DEL RENGLON
     */
    let colapse = $(table).parents(".collapse");
    let subtotals = colapse[0].getElementsByClassName("subtotal_table");
    let newTotal = 0;
    let QLIne = 0;
    if (subtotals.length > 0){
        for (let sub of subtotals){
            newTotal += cleanNumber(sub.textContent);
        }
        if (colapse[0].parentNode.getElementsByClassName("Quantity_line").length >0){
            QLIne = cleanNumber(colapse[0].parentNode.getElementsByClassName("Quantity_line")[0].textContent);
            colapse[0].parentNode.getElementsByClassName("Total_line")[0].textContent = "Q "+cleanNumber(newTotal / QLIne).format(2);
            colapse[0].parentNode.getElementsByClassName("Subtotal_line")[0].textContent = "Q "+cleanNumber(newTotal).format(2);
        }
    }
    return newTotal;
}

var updateQuantityLine = function (row, qty) {
    let container = $(row).parents(".detail");
    let alltds = container[0].getElementsByClassName("row_qty");
    let total = 0;
    for (let td of alltds){
        let row = $(td).parents("tr")[0];
        let approacyType = row.getElementsByClassName("quantity_item")[0].getAttribute('approach_type');

        // console.log(row.getElementsByClassName("refer_quantity_item"));
        let refer = cleanNumber(row.getElementsByClassName("refer_quantity_item")[0].value);
        let newQty = approach((qty*refer), approacyType);
        row.getElementsByClassName("quantity_item")[0].textContent = newQty.format(2);
        total = updateTotalRow(row, null, newQty, false);
    }
    return total;
};

/**
 * CONFIGURA LAS TBODYS DE LOS DETALLES DE RENGLONES COMO DROPABLES
 * @param table_class  clase de la tabla a configurar como dropable
 * @param acept id del tipo de tabla que permite
 */
var setDetailLineTemplate = function (table_class, acept) {
    var acepts='';
    acept.forEach(function (val) {
        if (acepts.length > 0){
            acepts += ',';
        }
        acepts += `#${val} > tbody > tr`;
    });
    $('.'+table_class).droppable({
        accept: acepts,
        tolerance: 'pointer',
        greedy: true,
        hoverClass: 'highlight',
        drop: function(ev, ui) {
            let nDrag = $(ui.draggable).clone(false).detach().css({
                position: 'relative',
                top: 'auto',
                left: 'auto'
            });
            let tds = nDrag[0].getElementsByTagName('td');
            for (let i=0; i<tds.length; i++){
                nDrag[0].getElementsByTagName('td')[i].classList.remove("hidden-xs");
                nDrag[0].getElementsByTagName('td')[i].classList.remove("hidden");
                nDrag[0].getElementsByTagName('td')[i].classList.remove("item_name_filter");
            }
            nDrag[0].removeAttribute('style');
            nDrag[0].removeAttribute('class');
            nDrag[0].className += "_item_row";
            nDrag.appendTo(this.tBodies);
            setxEditableInputs();
            updateTotalTable(this);
        }
    });
};

/**
 * PERMITE FILTRAR ELEMENTOS DE TIPO RENGLON
 * @param element
 * @param list
 */
function filter(element, list) {
    var value = $(element).val();
    $("#"+list+" > li").each(function() {
        if ($(this).text().toLowerCase().search(value.toLowerCase()) > -1) {
            $(this).show();
        }
        else {
            $(this).hide();
        }
    });
}

/**
 * Permite filtrar los elementos de las listas de articulos y servicios individuales
 * @param element input para buscar
 * @param table id de tabla
 */
function filterTable(element, table) {
    var value = $(element).val();
    $("#"+table+" > tbody > tr > .item_name_filter").each(function() {
        if ($(this).text().toLowerCase().search(value.toLowerCase()) > -1) {
            $(this).show();
        }
        else {
            $(this).hide();
        }
    });
}

/**
 * Elimina un elemento dentro del presupuesto
 * @param class_element Clase de elemento a eliminar
 * @param ref_element Elemento interno dentro del objeto a eliminar
 */
let deleteElement = function (class_element, ref_element) {
    if (class_element == "panel_header"){
        let header_id = $(ref_element).parents('.'+class_element)[0].getAttribute("header_counter");
        budget.deleteHeader(header_id);
    }

    if (class_element == "_item_row"){
        let row = $(ref_element).parents('.'+class_element)[0];
        let table = $(row).parents('table')[0];
        $(ref_element).parents('.'+class_element)[0].remove();
        updateTotalTable(table);
        return;
    }

    $(ref_element).parents('.'+class_element)[0].remove();
    if (class_element == "detail"){
        budget.updateFooter();
    }
}

let approach = function (val, type) {
    if(typeof val == "string"){
        val = val.replace(" ", "");
        val = val.replace("Q", "");
        val = val.replace(new RegExp(",", "g"), '');
    }
    if (Number.isNaN(Number.parseFloat(val))) {
        return 0;
    }
    let x;
    if (type==1){
        x = Math.ceil(parseFloat(val));
    }
    else{
        x = Math.ceil10(parseFloat(val), -1);
    }
    return parseFloat(x);
}


function decimalAdjust(type, value, exp) {
    // Si exp es undefined o cero...
    if (typeof exp === 'undefined' || +exp === 0) {
        return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // Si el valor no es un número o exp no es un entero...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
        return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
}

// Decimal round
if (!Math.round10) {
    Math.round10 = function(value, exp) {
        return decimalAdjust('round', value, exp);
    };
}
// Decimal floor
if (!Math.floor10) {
    Math.floor10 = function(value, exp) {
        return decimalAdjust('floor', value, exp);
    };
}
// Decimal ceil
if (!Math.ceil10) {
    Math.ceil10 = function(value, exp) {
        return decimalAdjust('ceil', value, exp);
    };
}
