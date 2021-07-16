(function(){
	var app = angular.module('tutapos', [ ]);
	app.controller("listProductsSale", [ '$scope', '$http', function($scope, $http) {
		$scope.itemsData = [ ];
		$scope.dataStorage=[ ];
		$scope.detailItem=[ ];
		$scope.idStorage=0;
		$scope.exists=false;
		$scope.quantityItems=0;

		$http.get('../getStorages').success(function(data) {
			$scope.dataStorage=data;

			// $.get('../api/item/'+data[0].id).success(function(data){
			// 	$scope.itemsData=data;				
			// });
        });



        //cambio de productos de bodegas
		$scope.updateStorage=function(){
			$.get('../api/item/'+$scope.idStorage).success(function(data){
				$scope.itemsData=data;				
			});
			document.getElementById('id_input_search').focus();
			setTimeout(function(){
				document.getElementById('id_input_search').blur();
			},500);
			document.getElementById('id_input_search').focus();
		}
		//agregando productos 
		$scope.addProduct=function(items){
			$scope.exists=false;
			$scope.indexItem=0;
			$scope.verifyExists($scope.detailItem,items.id);
				if(!$scope.exists){
					$scope.detailItem.push({
					'item_id':items.id,
					'item_name':items.item_name,
					'qt':1,
					'qt_storage':items.quantity
				});	
				$scope.quantityItems++;
			}else{
				var qtCurrent=$scope.detailItem[$scope.indexItem].qt;
				if((qtCurrent+1)>items.quantity){
					alertify.error('No hay mas existencias');
				}else{
					$scope.detailItem[$scope.indexItem].qt=parseInt($scope.detailItem[$scope.indexItem].qt)+1;
				}
			}
		}
		//verificamos la existencia del producto
		$scope.verifyExists=function(array,item_id){
			var length=array.length;
			for (var m = 0; m < length; m++) {
				if(array[m].item_id==item_id){
					$scope.exists=true;
					$scope.indexItem=m;
				}
			}
		}
		//eliminar elemento
		$scope.deleteItems=function(index){
			$scope.detailItem.splice(index,1);
			$scope.quantityItems--;
		}
		//verificando cuando se escribe en el elemento 
		$scope.verifyQt=function(items,index){
			var qtId_=document.getElementById('qtId_'+index);
			if(items.qt_storage>cleanNumber(qtId_.value)){
				$scope.detailItem[index].qt=cleanNumber(qtId_.value);
			}else{
				alertify.error('No hay mas existencias');
				$scope.detailItem[index].qt=cleanNumber(items.qt_storage);
			}
		}
	}]);	
})();