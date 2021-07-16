(function(){
	var app = angular.module('tutapos', [ ]);
	app.controller("listProducts", [ '$scope', '$http', function($scope, $http) {
		$scope.items = [ ];
		$scope.detailsItems=[];
		$scope.exists=false;
		$scope.indexItem=0;
		$scope.quantityItems=0;
		$http.get('api/item').success(function(data) {
			$scope.items = data;
		});
		$scope.addProduct=function(items){
			$scope.exists=false;
			$scope.indexItem=0;
			$scope.verifyExists($scope.detailsItems,items.id);
			if(!$scope.exists){
				$scope.detailsItems.push({
					'item_id':items.id,
					'item_name':items.item_name,
					'qt':1
				});
				$scope.quantityItems++;
			}else{
				$scope.detailsItems[$scope.indexItem].qt=parseInt($scope.detailsItems[$scope.indexItem].qt)+1;
			}
		}
		//verificar si existe elemento
		$scope.verifyExists=function(array,item_id){
			var length=array.length;
			for (var i = 0; i < length; i++) {
				if(array[i].item_id==item_id){
					$scope.exists=true;
					$scope.indexItem=i;
				}
			}
		}
		//eliminar elemento
		$scope.deleteItems=function(index){
			$scope.detailsItems.splice(index,1);
			$scope.quantityItems--;
		}
	}]);	
})();