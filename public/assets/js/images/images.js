var counter=0;
var x;
$(document).ready(function(){
  $('#btn_save').click(function (){
    $(".dz-complete").each(function() {
      if ($(this).find('.dz-image')) {
        x = $(this).find("img");
        $('<input>').attr({
            type: 'file',
            name: 'img'+counter,
            value:x
        }).appendTo('form');
        counter++;
      }
    });
  });
});
