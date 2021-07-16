function applyRetentions(control){
  var amount=control.value;
  if(document.getElementById('_jretentions')) 
  {
    var retentions=JSON.parse(document.getElementById('_jretentions').value);
  retentions.forEach(function(val){
    var tmpCtrl=document.getElementById(val.id);
    tmpCtrl.value=parseFloat(amount*(val.percent/100)).toFixed(2);
    document.getElementById('r'+val.id).value=tmpCtrl.value;
  });
  buildJson();
}
}

function buildJson(){
  var data = [];
  var json = {};
  var retentions=JSON.parse(document.getElementById('_jretentions').value);
  retentions.forEach(function(val){
    data.push({
      "retention_id": val.id,
      "value": document.getElementById(val.id).value,
      "reference":document.getElementById('r'+val.id).value
    });
  });
  json = data;
  $('#jRetentions').val(JSON.stringify(json));
}
