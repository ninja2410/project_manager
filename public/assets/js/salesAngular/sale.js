(function(){
    var app = angular.module('tutapos', [ ]);

    app.controller("SearchItemCtrl", [ '$scope', '$http', function($scope, $http) {
        $scope.items = [ ];
        $scope.detailsItems=[ ];
        $scope.exists=false;
        $scope.indexItem=0;
        $scope.quantityItems=0;

        $scope.nuevo=function(id){
          $scope.valorRecibido=id;

          $http.get('api/item/'+$scope.valorRecibido).success(function(data) {
              $scope.items = data;
              //console.log(data);
          });
        };
        $scope.addItems=function(item){
            $scope.exists=false;
			$scope.indexItem=0;
            $scope.verifyExists($scope.detailsItems,item.id);
            if(!$scope.exists){
                if($scope.quantityItems<=8){
                    $scope.detailsItems.push({
                        'item_id':item.id,
                        'item_name':item.item_name,
                        'quantity_storage':item.quantity,
                        'quantity':1,
                        'cost_price':item.cost_price,
                        'selling_price':item.selling_price,
                        'selling_real':item.selling_price,
                        'subtotal':(1*item.selling_price)
                    });
                    $scope.quantityItems++;
                }else{
                    alertify.error('Solo se pueden agregar 9 articulos');
                }
            }else {
                var qtCurrent=$scope.detailsItems[$scope.indexItem].quantity;
				if((qtCurrent+1)>item.quantity){
                    alertify.error('No hay mas existencias');
                }else {
                    //actualizar cantidad
                    $scope.detailsItems[$scope.indexItem].quantity=parseInt($scope.detailsItems[$scope.indexItem].quantity)+1;
                    //actualizar total
                    $scope.detailsItems[$scope.indexItem].subtotal=parseInt($scope.detailsItems[$scope.indexItem].quantity)*parseFloat($scope.detailsItems[$scope.indexItem].selling_real);    
                }
            }
        }
        $scope.deleteItem=function(index){
            $scope.detailsItems.splice(index,1);
            $scope.quantityItems--;
        }
        //verificar existencia
        $scope.verifyExists=function(array,item_id){
			var length=array.length;
			for (var m = 0; m < length; m++) {
				if(array[m].item_id==item_id){
					$scope.exists=true;
					$scope.indexItem=m;
				}
			}
        }
        $scope.verifyQt=function(items,index){
            var qtId_=document.getElementById('qtId_'+index);
			if(items.quantity_storage>=parseInt(qtId_.value)){
                $scope.detailsItems[index].quantity=parseInt(qtId_.value);
                // $scope.detailsItems[index].subtotal=parseInt($scope.detailsItems[index].quantity)*parseFloat($scope.detailsItems[index].selling_real);    
			}else{
                alertify.error('No hay mas existencias');
                $scope.detailsItems[index].quantity=parseInt(items.quantity_storage);
            }
            $scope.detailsItems[index].subtotal=parseInt($scope.detailsItems[index].quantity)*parseFloat($scope.detailsItems[index].selling_real);    
        }
        $scope.verifyPrice=function(items,index){
            var priceReal_=document.getElementById('priceReal_'+index).value;
            if(parseFloat(priceReal_)<=parseFloat(items.selling_price)){
                $scope.detailsItems[index].subtotal=parseInt($scope.detailsItems[index].quantity)*parseFloat(priceReal_);    
            }else{
                alertify.error('No se puede agregar un precio mas bajo que el precio minimo');
                $scope.detailsItems[index].selling_real=items.selling_price;
                $scope.detailsItems[index].subtotal=parseInt($scope.detailsItems[index].quantity)*parseFloat($scope.detailsItems[index].selling_real);    
            }
        }
    }
  ]);


})();
