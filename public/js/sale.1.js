(function(){

    var app = angular.module('tutapos', [ ]);


    // app.controller('MainCtrl', function($scope) {
    //   $scope.Mensaje=function(){
    //       // console.log("aca vamos a borrar");
    //       alert('hola desde angularjs');
    //   }
    // });

    app.controller("SearchItemCtrl", [ '$scope', '$http', function($scope, $http) {
        $scope.items = [ ];
        $scope.nuevo=function(id){
          console.log("No se que es:"+id);
          $scope.valorRecibido=id;

          $http.get('api/item/'+$scope.valorRecibido).success(function(data) {
              $scope.items = data;
              //console.log(data);
          });
        };

        $scope.Saludos = function(serie_id) {
        //  alert('hola que haces');
        // alert("hola que haces"+serie_id);
        $scope.items2=[ ];
        $http.get('api/'+serie_id+'/item').success(function (data){
          $scope.items2=data;
          //console.log($scope.items2);
        });
        };


        var elementoMensaje=document.getElementById('mensaje');
        elementoMensaje.style.display="block";


        $scope.saletemp = [ ];
        $scope.newsaletemp = { };
        $http.get('api/item/'+$scope.valorRecibido).success(function(data, status, headers, config) {
            $scope.saletemp = data;
            // console.log(data);
        });
            $scope.addSaleTemp = function(item, newsaletemp) {

            $http.post('api/saletemp', { item_id: item.id, cost_price: item.cost_price, selling_price: item.selling_price,id_bodega: $scope.valorRecibido,cellar_quantity:item.quantity,low_price:item.selling_price }).
            success(function(data, status, headers, config) {
                var ventaNuevoElemento=document.getElementsByClassName('ventaNuevoElemento');
                if(!ventaNuevoElemento){

                }else
                {
                  if(ventaNuevoElemento.length<=8)
                  {
                    $scope.saletemp.push(data);
                    $http.get('api/saletemp').success(function(data) {
                    $scope.saletemp = data;
                    });
                  }
                  else
                  {

                    if(document.getElementById("elemento_de_venta_"+data.item_id))
                    {

                      var elemento=document.getElementById('elemento_de_venta_'+data.item_id);
                      //elemento.value=parseInt(elemento.value)+1;
                      //$scope.updateSaleTemp(newsaletemp);
                      elemento.focus();
                    }
                    else
                    {
                      alert('La factura solo puede contener 9 elementos ');
                    }
                  }
                }
            });
        }

        $scope.updateSaleTemp = function(newsaletemp) {

            // console.log(newsaletemp.quantity+" "+newsaletemp.cellar_quantity);
            var existencia=newsaletemp.cellar_quantity;
            var nuevaExistencia=newsaletemp.quantity;
            var precio_venta=document.getElementById('idventa_'+newsaletemp.item_id);

            if (nuevaExistencia<=existencia)
            {
                // Guardar en DB
                $http.put('api/saletemp/' + newsaletemp.id, { quantity: nuevaExistencia, total_cost: newsaletemp.item.cost_price * newsaletemp.quantity,total_selling: parseFloat(precio_venta.value) * newsaletemp.quantity,bandera:0}).
                success(function(data, status, headers, config) {

                    });
                // alert("Danny mira "+existencia+' en ' + 'quantity_'+newsaletemp.id );
            }
            else
            {
              // alert(existencia+" "+nuevaExistencia);
                  //document.getElementById('elemento_de_venta_'+newsaletemp.item_id).value =existencia;
                  var total=parseFloat(precio_venta.value)*existencia;
                  $http.put('api/saletemp/' + newsaletemp.id, { quantity: existencia, total_cost: newsaletemp.item.cost_price * newsaletemp.quantity,total_selling: total,bandera:0 }).
                  success(function(data, status, headers, config) {
                  precio_venta.focus();
                  newsaletemp.quantity=existencia;
                  elementoMensaje.innerHTML="<div class='alert alert-danger'><strong>No hay tantas Existencias</strong></div>";

                  var remover=function(){
                    elementoMensaje.style.display="none";
                   }
                    setTimeout(remover, 2000);
                });
            }

        }
        $scope.updateSaleTempPriceSelling = function(newsaletemp) {
            var id=newsaletemp.item_id;
            var costominimo=document.getElementById('costominimo_'+id);
            var valornuevo=document.getElementById('idventa_'+id);
            var precionormal=document.getElementById('precionormal_'+id);
            var nuevaExistencia=newsaletemp.low_price;
            var role_user=document.getElementById('role_user');
            if(role_user.value=='Administrador')
            {
              $http.put('api/saletemp/' + newsaletemp.id, {total_cost: newsaletemp.item.cost_price * newsaletemp.quantity,total_selling:nuevaExistencia * newsaletemp.quantity,low_price:nuevaExistencia,bandera:1 }).
              success(function(data, status, headers, config) {
                  });
            }
            else
            {
              if(parseFloat(nuevaExistencia) < parseFloat(costominimo.value))
              {

                $http.put('api/saletemp/' + newsaletemp.id, {total_cost: newsaletemp.item.cost_price * newsaletemp.quantity,total_selling: newsaletemp.item.selling_price * newsaletemp.quantity,low_price:costominimo.value,bandera:1 }).
                success(function(data, status, headers, config) {

                    });
                newsaletemp.low_price=costominimo.value;
                alert("Precio no permitido el precio de venta minimo es: "+costominimo.value);
              }
              else
              {
                // Guardar en DB
                $http.put('api/saletemp/' + newsaletemp.id, {total_cost: newsaletemp.item.cost_price * newsaletemp.quantity,total_selling: nuevaExistencia * newsaletemp.quantity,low_price:nuevaExistencia,bandera:1 }).
                success(function(data, status, headers, config) {
                    });
              }
            }

        }
        $scope.removeSaleTemp = function(id) {
            $http.delete('api/saletemp/' + id).
            success(function(data, status, headers, config) {
                $http.get('api/saletemp').success(function(data) {
                        $scope.saletemp = data;
                        });
                });
        }

        $scope.sum = function(list) {
            var total=0;
            angular.forEach(list , function(newsaletemp){

              //accedemos al low_price del elemento que se esta trabajando newsaletemp.item.selling_price
                total+= parseFloat(newsaletemp.low_price * newsaletemp.quantity);
            });
            return total;
        }
    }
  ]);


})();
