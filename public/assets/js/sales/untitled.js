$("#idFormNewCustomer").submit(function(ev){ev.preventDefault();});
$("#submit").on("click", function(){

   var bootstrapValidator = $("#yourform").data('bootstrapValidator');
   bootstrapValidator.validate();
   if(bootstrapValidator.isValid()){
     $("#yourform").submit();

 }else {
    return;
}

});