angular.module('numfmt-error-module', [])

.run(function($rootScope) {
  $rootScope.typeOf = function(value) {
    return typeof value;
  };
})

.directive('stringToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(value) {
        return '' + value;
      });
      ngModel.$formatters.push(function(value) {
        return parseFloat(value, 10);
      });
    }
  };
});
(function(){
    var app = angular.module('tutapos', [ ]);
function convert(string){
  var n=string.replace("Q");
  return parseFloat(n);
}
    app.controller("SearchItemCtrl", [ '$scope', '$http', function($scope, $http) {
        $scope.items = [ ];
        $http.get('api/item').success(function(data) {
            $scope.items = data;
        });

        $scope.receivingtemp = [ ];
        $scope.newreceivingtemp = { };
        $http.get('api/receivingtemp').success(function(data, status, headers, config) {
            $scope.receivingtemp = data;
        });


        $scope.addReceivingTemp = function(item,newreceivingtemp) {
            $http.post('api/receivingtemp', { item_id: item.id, cost_price: item.cost_price, total_cost: item.cost_price, type: item.type,last_cost:item.cost_price }).
            success(function(data, status, headers, config) {
                $scope.receivingtemp.push(data);
                    $http.get('api/receivingtemp').success(function(data) {
                    $scope.receivingtemp = data;
                    });
            });
        }
        //validacion de ingreso de cantidades menores a cero y vacios
        $scope.validarCantidades=function(elemento){
            //validamos el elemento para verificar que tenga cantidades mayores a cero
            //o vacios
            if(elemento.quantity==null ){
                //si es cero o vacio se agrega uno por default
                elemento.quantity=1;
                //se llama a la funcion que se encarga de actualizar la tabla temporal
                $scope.$apply($scope.updateReceivingTemp(elemento));
            }
        }
        $scope.updateReceivingTemp = function(newreceivingtemp) {
            $http.put('api/receivingtemp/' + newreceivingtemp.id, { quantity: newreceivingtemp.quantity, cost_price: newreceivingtemp.cost_price }).
            success(function(data, status, headers, config) {
                });
        }
        $scope.updateCostPriceReceivingTemp=function(newreceivingtemp){
          $http.put('api/receivingtemp/' + newreceivingtemp.id, {quantity:newreceivingtemp.quantity, cost_price: newreceivingtemp.cost_price}).
          success(function(data, status, headers, config) {
              });
        }

        $scope.removeReceivingTemp = function(id) {
            $http.delete('api/receivingtemp/' + id).
            success(function(data, status, headers, config) {
                $http.get('api/receivingtemp').success(function(data) {
                        $scope.receivingtemp = data;
                        });
                });
        }
        $scope.sum = function(list) {
            var total=0;
            angular.forEach(list , function(newreceivingtemp){
                total+= parseFloat(newreceivingtemp.cost_price * newreceivingtemp.quantity);
            });
            return total;
        }
        $scope.updtPro = function(list) {
          if (document.getElementById('flag_').value==1) {
            return;
          }
            angular.forEach(list , function(newreceivingtemp){
              var tmp=$("#pr_"+newreceivingtemp.item_id).html();
              var nw=tmp.replace("Q", "");
                if (nw!="") {
                  var url=APP_URL+'/api/receivingtemp/'+newreceivingtemp.id+"/"+parseFloat(nw);
                  // $http.get(url).success(function(data) {
                  //     console.log(data);
                  // });
                  $.ajax({
                    type: "get",
                    async: false,
                    url: url,
                    success: function(data) {
                      console.log(data);
                    },
                    error: function(error) {
                      console.log("existe un error revisar");
                    }
                  });
                }
            });
            document.getElementById('flag_').value=1;
        }
    }]);

    

})();
function setFlag(){
  $('#flag_').val("0");
  angular.element($('#flag_')).triggerHandler('input');
}



