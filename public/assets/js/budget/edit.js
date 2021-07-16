$(document).ready(function () {
    setHeaders();
    budget.updateFooter();
});
let setHeaders = function () {
    let cnt = cleanNumber($('#header_count').val());
    for (let i =0; i<cnt; i++){
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
        $('.droppable').css({height: 'auto'});
        $('.detail_header').css({height: 'auto'});
        setDropableDetails();
        setxEditableInputs();
        setDetailLineTemplate('allowServices', ['lServices']);
        setDetailLineTemplate('allowProducts', ['lProducts', 'lWildcards']);
        counter_headers++;
    }
    counter_details = cleanNumber($('#details_counter').val());
};
