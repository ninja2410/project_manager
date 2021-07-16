(function(){

    var app = angular.module('tutapos', [ ]);
    app.controller("SearchItemCtrl", [ '$scope', '$http', function($scope, $http) {
        $scope.items = [ ];
        $scope.nuevo=function(id){
          $scope.valorRecibido=id;
          $http.get('api/item/'+$scope.valorRecibido).success(function(data) {
              $scope.items = data;
              //console.log(data);
          });
        };

    }
  ]);


})();
