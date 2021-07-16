jQuery(document).ready(function() {
    UINestable.init();
});
var calculateCost;
var UINestable = function () {

    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    calculateCost = function() {
        let cost = 0;
        let total_items = 0;
        $(".list_selected li").each(function(){
            var idT = $(this).attr('id').split('_');
            var id=idT[1];
            let tmpq = cleanNumber($('#q_'+id).val());
            let tmpc = cleanNumber($('#c_'+id).val());
            total_items += tmpq;
            cost += (tmpq * tmpc);
            $('#total_cost').val(cost.toFixed(2));
            $('#items_quantity').val(total_items.toFixed(2));
        });
    };

    return {
        //main function to initiate the module
        init: function () {

            // activate Nestable for list 1
            $('#nestable_list_1').nestable({
                group: 1
            })
                .on('change', updateOutput);
            $('#nestable_list_0').nestable({
                group: 1
            })
                .on('change', updateOutput);

            // activate Nestable for list 2
            $('#nestable_list_2').nestable({
                group: 1
            })
                .on('change', updateOutput, calculateCost);
            $('#nestable_list_4').nestable({
                group: 1
            })
                .on('change', updateOutput, calculateCost);

            // output initial serialised data
            updateOutput($('#nestable_list_1').data('output', $('#nestable_list_1_output')));
            updateOutput($('#nestable_list_0').data('output', $('#nestable_list_0_output')));
            updateOutput($('#nestable_list_2').data('output', $('#nestable_list_2_output')));
            updateOutput($('#nestable_list_4').data('output', $('#nestable_list_4_output')));

            $('#nestable_list_menu').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

            // $('#nestable_list_3').nestable();
            // $('#nestable_list_4').nestable();
            // $('#sortable1').sortable();
            // $('#sortable2,#sortable3').sortable({
            //     connectWith:'.connected'
            // })


        }

    };

}();
