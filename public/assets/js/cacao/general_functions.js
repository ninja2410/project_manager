/**
 * FUNCION PARA CONVERTIR TEXTO EN FORMATO 4,500 A NUMERO DECIMAL
 * @param amount
 * @param decimals
 * @returns {string}
 */
function number_format(amount, decimals) {
    amount += ""; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.-]/g, "")); // elimino cualquier cosa que no sea numero o punto

    decimals = decimals || 0; // por si la variable no fue fue pasada

    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0) return parseFloat(0).toFixed(decimals);

    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = "" + amount.toFixed(decimals);

    var amount_parts = amount.split("."),
        regexp = /(\d+)(\d{100})/;

    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, "$1" + "," + "$2");

    return amount_parts.join(".");
}

Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

/**
 * Configura un objeto tipo table utilizando el plugin datatables a un formato estándar
 * @param table_id : ID de la tabla a configurar
 * @param summary_columns : Array de indices de columna a los cuales calcularle la sumatoria
 * @param path_language : Path de json lenguaje espa;ol para datatable
 * @param print_last_column : Parametro opcional, con el valor true se imprimira la ultima columna del datatable
 * @param mensajeHeader : Mensaje customizado para imprimir en pdf
 * @param page_length : cantidad de registros mostrados por pagina
 *
 * Example:
 * setDataTable("table1", [6,7], "{{asset('assets/json/Spanish.json')}}");
 */
function setDataTable(table_id, summary_columns, path_language,mensajeHeader='',page_length=10, print_last_column = false){
    /**
     * LEER EL LOGO POR DEFECTO EN EL SISTEMA
     * @type {HTMLElement}
     */
    if (!print_last_column){
        var configColumns = 'th:not(:last-child)';
    }
    else{
        var configColumns = '';
    }

    const logo = APP_URL + '/images/system/printable_logo.png';
    toDataURL(logo, function(dataURL) {
        system_image = dataURL;
    });

    $('#'+table_id).DataTable({
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,Q,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            summary_columns.forEach(function (value) {
                pageTotal = api
                    .column(value, {filter:'applied'})
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                $(api.column(value).footer()).html(
                    'Q' + cleanNumber(pageTotal).format(2)
                );
            });

        },
        "language": {"url": path_language},
        dom: 'Bfrtip',
        "pageLength": page_length,
        responsive: {
            details: {
                type: 'column'
            }
        },
        columnDefs: [{
            className: 'control',
            orderable: false,
            targets: 0
        }],
        buttons: [
            {
                extend: 'collection',
                text: 'Exportar/Imprimir',
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Copiar',
                        footer: 'true',
                        title: document.title,
                        exportOptions: {
                            columns: configColumns
                        }
                    },
                    {
                        extend: 'excel',
                        title: document.title,
                        footer: 'true',
                        messageTop:mensajeHeader,
                        exportOptions: {
                            columns: configColumns
                        }
                    },
                    {
                        extend: 'pdf',
                        title: document.title,
                        footer: 'true',
                        messageTop:mensajeHeader,
                        exportOptions: {
                            columns: configColumns
                        },
                        customize: function(doc) {
                            doc.styles.tableHeader.fontSize = 8;
                            doc.defaultStyle.fontSize = 6;
                            doc.styles.tableFooter.fontSize = 8;
                            doc.content.splice(1,0, {
                                margin: [ 0, 0, 0, 12 ],
                                alignment: 'left',
                                image:system_image

                            });
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        title: document.title,
                        footer: 'true',
                        messageTop:mensajeHeader,
                        exportOptions: {
                            columns: configColumns
                        },
                        customize: function ( doc ) {
                            $(doc.document.body).find('h1').css('font-size', '11pt');
                            $(doc.document.body).find('h1').css('text-align', 'center');
                            $(doc.document.body).find( 'table' ).css( 'font-size', '8pt' );
                            $(doc.document.body).css( 'font-size', '8pt' );
                        }
                    }
                ]
            },
        ],
    });
}

function toDataURL(src, callback) {
    var xhttp = new XMLHttpRequest();

    xhttp.onload = function() {
        var fileReader = new FileReader();
        fileReader.onloadend = function() {
            callback(fileReader.result);
        }
        fileReader.readAsDataURL(xhttp.response);
    };

    xhttp.responseType = 'blob';
    xhttp.open('GET', src, true);
    xhttp.send();
}


/**
 * Verifica la validez de un NIT
 * @param nit
 * @returns {boolean}
 */
function nitValid(nit){
    if (!nit) {
        return true;
    }
    if (nit == 'C/F'){
        return true;
    }
    if (nit == 'c/f'){
        return true;
    }
    var nitRegExp = new RegExp('^[0-9]+(-?[0-9kK])?$');
    if (!nitRegExp.test(nit)) {
        return false;
    }
    nit = nit.replace(/-/, '');
    var lastChar = nit.length - 1;
    var number = nit.substring(0, lastChar);
    var expectedCheker = nit.substring(lastChar, lastChar + 1).toLowerCase();
    var factor = number.length + 1;
    var total = 0;
    for (var i = 0; i < number.length; i++) {
        var character = number.substring(i, i + 1);
        var digit = parseInt(character, 10);

        total += (digit * factor);
        factor = factor - 1;
    }
    var modulus = (11 - (total % 11)) % 11;
    var computedChecker = (modulus == 10 ? "k" : modulus.toString());
    return expectedCheker === computedChecker;
}
