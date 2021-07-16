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
 * CONFIGURA LAS TBODYS DE LOS DETALLES DE RENGLONES COMO DROPABLES
 * @param table_class  clase de la tabla a configurar como dropable
 * @param acept id del tipo de tabla que permite
 */
var setDetailLineTemplate = function (table_class, acept) {
    $('.'+table_class).droppable({
        accept: "#"+acept+" > tbody > tr",
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
                if(!nDrag[0].getElementsByTagName('td')[i].classList.contains("flotante")){
                    nDrag[0].getElementsByTagName('td')[i].removeAttribute('class');
                }
            }
            nDrag[0].removeAttribute('style');
            nDrag[0].removeAttribute('class');
            nDrag[0].className = "_item_row";
            setBtnRemove(nDrag[0]);
            nDrag.appendTo(this.tBodies);
            setxEditableInputs();
            updateTotalTable(this);
        }
    });
};

/**
 * INICIALIZA LOS COMPONENTES DRAGABLES Y DROPABLES
 */
function initialDragandDrop(){
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
}


/**
 * Inicializa las etiquetas a como xeditables
 */
var setxEditableInputs = function(){
    if ($('#change_prices').val() == true){
        $('.unit_cost').editable({
            type: 'number',
            url: APP_URL + '/update_budget_cost',
            title: 'Ingrese nuevo precio',
            params:function(params){
                var data = {};
                data['id'] = params.pk;
                data['value'] = params.value;
                data['change_all'] = true;
                return data;
            },
            success: function(response, newValue) {
                //userModel.set('username', newValue); //update backbone model
                let x = $(this).parents("tr");
                let classupdate = 'item_'+($(this).attr("item_id"));
                updateCostItems(classupdate, newValue);
                updateTotalRow(x[0], response);
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
    }


    $('.quantity_item').editable({
        type: 'number',
        //url: APP_URL + '/item/update_budget_cost',
        title: 'Ingrese cantidad',
        _token: $('#token_').val(),
        validate: function(value) {
            if($.trim(value) == '') {
                return 'Debe ingresar una cantidad.';
            }
            if (cleanNumber(value)<=0){
                return 'Debe ingresar un valor mayor que 0.';
            }
        }
    }).on('hidden', function(e, reason) {
        let x = $(this).parents("tr");
        updateTotalRow(x[0]);
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

/**
 * ACTUALIZAR EL TOTAL DE LA TABLA Y EL TOTAL DEL RENGLON
 * @param table tabla a actualizar
 */
var updateTotalTable = function (table) {
    let total_rows = table.rows;
    let _tmp_total = 0;
    let _tmp_q_total = 0;
    for (let row of total_rows) {
        let tds = (row.getElementsByClassName('sut_total'));
        let tdsq = (row.getElementsByClassName('quantity_item'));
        if (tds.length > 0) {
            // console.log(((tds[0].textContent)));
            _tmp_total += (cleanNumber(tds[0].textContent));
        }
        if (tdsq.length > 0) {
            // console.log(((tds[0].textContent)));
            _tmp_q_total += (cleanNumber(tdsq[0].textContent));
        }
    }
    if (table.getElementsByClassName("subtotal_table").length > 0) {
        table.getElementsByClassName("subtotal_table")[0].textContent = 'Q ' + _tmp_total.format(2);
    }
    if (table.getElementsByClassName("subtotal_quantity_table").length > 0) {
        table.getElementsByClassName("subtotal_quantity_table")[0].textContent = _tmp_q_total.format(2);
    }

    /**
     * ACTUALIZAR EL TOTAL DEL RENGLON
     */
    let total = 0, totalQ = 0;
    let container = document.getElementById('selected_items_accordion');

    let eTotales = container.getElementsByClassName("subtotal_table");
    for (let t of eTotales) {
        total += cleanNumber(t.textContent)
    }
    let eQty = container.getElementsByClassName("subtotal_quantity_table");
    for (let t of eQty) {
        totalQ += cleanNumber(t.textContent)
    }
    document.getElementById("total_cost_line").value = total;
    document.getElementById("total_qty_line").value = totalQ;
};

/**
 * Elimina un elemento dentro del presupuesto
 * @param class_element Clase de elemento a eliminar
 * @param ref_element Elemento interno dentro del objeto a eliminar
 */
let deleteElement = function (class_element, ref_element) {
    if (class_element == "_item_row"){
        let row = $(ref_element).parents('.'+class_element)[0];
        let table = $(row).parents('table')[0];
        $(ref_element).parents('.'+class_element)[0].remove();
        updateTotalTable(table);
    }
}

let setBtnRemove = function (element) {
    // element.addEventListener("mouseover", function () {
    //     element.getElementsByClassName("flotante")[0].classList.remove("hidden");
    // });
    // element.addEventListener("mouseout", function () {
    //     element.getElementsByClassName("flotante")[0].classList.add("hidden");
    // });
};
