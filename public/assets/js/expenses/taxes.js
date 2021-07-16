$('#category_id').change(function () {
    if ($(this).val() == '') {
        // $('#frmSup').bootstrapValidator('enableFieldValidators', 'taxe_category', false, null);
        // $('#frmSup').bootstrapValidator('enableFieldValidators', 'units', false, null);
        // $('#frmSup').bootstrapValidator('enableFieldValidators', 'total_cost', false, null);
        document.getElementById('taxes_div').style.display = "none";
        return;
    }

    $.ajax({
        method: 'get',
        url: APP_URL + '/taxes_category/' + $(this).val(),
        success: function (data) {
            var json = JSON.parse(data);
            var categoria = JSON.parse(json.categorie);
            if (categoria.taxes > 0) {
                $('#frmSup').bootstrapValidator('enableFieldValidators', 'taxe_category', true, null);
                $('#frmSup').bootstrapValidator('enableFieldValidators', 'units', true, null);
                $('#frmSup').bootstrapValidator('enableFieldValidators', 'total_cost', true, null);
                document.getElementById('taxes_div').style.display = "inline";
                var taxes = JSON.parse(json.taxes);
                $('#taxe_category option').remove();
                taxes.forEach(function (data, index) {
                    $('#taxe_category').append('<option units="' + data.units + '" val="' + data.value + '" desc="' + data.description + '" value="' + data.id + '">' + data.name + ' - (Q ' + data.value + '/' + data.units + ')</option>');
                });
                $('#taxe_category').val(null).trigger('change');
            } else {
                document.getElementById('taxes_div').style.display = "none";
                $('#units').val('');
                $('#unit_cost').val('');
                $('#total_cost').val('');
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
});

$('#taxe_category').change(function () {
    var tax_selected = $("#taxe_category option:selected");
    $('#units').attr('placeholder', tax_selected.attr('units'));
    var tax_amount = +(tax_selected.attr('val'));
    if (isNaN(tax_amount)) {
        return;
    }
    $('#units').keyup();
});

$('#units').keyup(function () {
    var tax_selected = $("#taxe_category option:selected");
    var tax_amount = +(tax_selected.attr('val'));
    if (isNaN(tax_amount)) {
        $('#total_cost').val(0);
        $('#unit_cost').val(0);
        $('#taxe_category').focus();
        return;
    }
    $('#total_cost').val(+(tax_amount * $(this).val()));
    $('#total_cost').change();
    $('#unit_cost').val(parseFloat($('#expense_amount').val() / $(this).val()).toFixed(2));
});

$('#unit_cost').change(function () {
   var units = $('#units').val();
   if (isNaN(units)){
       $('#total_cost').val(0);
       $('#unit_cost').val(0);
       $('#taxe_category').focus();
       return;
   }
   if ($('#expense_amount').val()==''){
       $('#expense_amount').val(parseFloat(units*$(this).val()).toFixed(2));
       $('#units').keyup();
   }


});
