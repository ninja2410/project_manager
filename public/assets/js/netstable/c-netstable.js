$(document).ready(function(){

  /*CLEAVE*/
    $('.quantity_item').toArray().forEach(function(field){
        new Cleave(field, {
            numeral: true,
            numeralPositiveOnly: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    });

    $('select').select2({
        allowClear: true,
        theme: "bootstrap",
        placeholder: "Buscar"
    });


    $('#frmNew').bootstrapValidator({
      feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
      },
      message: 'Valor no valido',
      fields:{
          name:{
              validators:{
                  notEmpty:{
                      message:'Debe ingresar nombre de elemento.'
                  }
              }
          },
          price:{
              validators:{
                  notEmpty:{
                      message:'Debe ingresar precio de elemento.'
                  }
              }
          },
          categorie_id:{
              validators:{
                  notEmpty:{
                      message:'Debe seleccionar una categoría.'
                  }
              }
          },
          description:{
              validators:{
                  notEmpty:{
                      message:'Debe ingresar descripción de elemento.'
                  }
              }
          }
      }
  });
    setDraggables();
    calculateCost();
});

function send(){
  var count=0;
  $(".list_selected li").each(function(){
	    count++;
	});
  if (count>0) {
     let val = buildJson();
      var validator = $('#frmNew').data('bootstrapValidator').validate();
      if (val){
          toastr.error("Debe ingresar un valor valido en la cantidad de los elementos seleccionados");
          $('#modalConf').modal('hide');
          return;
      }
      if (validator.isValid() ) {
          document.getElementById('frmNew').submit();
      }
      else{
          $('#modalConf').modal('hide');
      }
  }
  else {
    $('#modalConf').modal('hide');
    toastr.error("Debe ingresar al menos un articulo en el detalle del renglon.");
  }

}

function buildJson(){
  var data = [];
  var json = {};
  var flag = false;
  $(".list_selected li").each(function(){
	    var idT = $(this).attr('id').split('_');
      var id=idT[1];
      if(cleanNumber($('#q_'+id).val())==0){
          flag = true;
      }
      data.push({
        "item_id": id,
        "quantity": $('#q_'+id).val()
      });
	});
  json = data;
  $('#itemDetail').val(JSON.stringify(json));
  return flag;
}

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

function setQuantitys(control){
  if (control.checked) {
    showInputs();
  }
  else{
    noneInputs();
  }
}

function showInputs(){
  $(".list_selected li").each(function(){
	    var idT = $(this).attr('id').split('_');
      var id=idT[1];
      var texto=$('#handle_'+id);
      var input=
      texto.addClass("col-lg-8");
      document.getElementById('dQuantity'+id).style.display="inline";
	});
    $('#nestable_list_0').addClass('drag_disabled');
    $('#nestable_list_1').addClass('drag_disabled');
}

function noneInputs(){
  $(".list_selected li").each(function(){
	    var idT = $(this).attr('id').split('_');
      var id=idT[1];
      var texto=$('#handle_'+id);
      var input=
      texto.removeClass("col-lg-8");
      document.getElementById('dQuantity'+id).style.display="none";
	});
  setDraggables();
}

function setDraggables(){
    $('#nestable_list_0').removeClass('drag_disabled');
    $('#nestable_list_1').addClass('drag_disabled');

    $('#faq-cat-1-sub-1').removeClass('in');
    $('#faq-cat-1-sub-2').removeClass('in');

    $('#acItems').click(function () {
        $('#nestable_list_1').removeClass('drag_disabled');
        $('#nestable_list_0').addClass('drag_disabled');
    });

    $('#acServices').click(function () {
        $('#nestable_list_1').addClass('drag_disabled');
        $('#nestable_list_0').removeClass('drag_disabled');
    });
}