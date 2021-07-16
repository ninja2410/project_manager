function saveValue(control){
  setTimeout(function(){
    var val;
    if (control.type!='checkbox') {
      val=control.value;
    }
    else{
      val=control.checked;
    }
    $.ajax({
      type: "post",
      async: false,
      url: APP_URL + "/project/valueStages",
      data: {
        value: val,
        _token: $("#token").val(),
        project_id: $("#project_id").val(),
        atribute_id: control.name
      },
      success: function(data) {
        console.log(data);
      },
      error: function(error) {
        console.log("existe un error revisar");
      }
    });
  }, 0);
}
$(document).ready(function () {
  $('.btn_reload').click(function () {
    let stage = $(this).attr('stage_id');
    $.ajax({
      type: "post",
      async: false,
      url: APP_URL + "/project/stages/update",
      data: {
        _token: $("#token").val(),
        project_id: $("#project_id").val(),
        status: 0,
        stage_id: stage
      },
      success: function(rj) {
        if (rj == 1){
          $('#reload_stage_'+stage).css('display', 'none');
          $('#step_stage_'+stage).removeClass('acc-wizard-completed');
          $('#stage_inputs_group_'+stage).removeAttr('disabled');
        }
      },
      error: function(error) {
        console.log("existe un error revisar");
      }
    });
  });
});

