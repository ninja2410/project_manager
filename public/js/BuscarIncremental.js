(function(){
    var app = angular.module('tutapos', [ ]);

    app.controller("BuscarIncremental", [ '$scope', '$http', function($scope, $http) {
      $scope.Click = function(){
          alert('Hola que haces');
      }
    }]);
})();
