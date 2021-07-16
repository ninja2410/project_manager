<!-- Modal new customer -->
    <div class="modal fade in" id="modal-2" tabindex="-1" role="dialog" aria-hidden="false">
      <div class="modal-dialog modal-lg" role="document">
        <form method="post" id="idFormNewCustomer">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <h4 class="modal-title" id="modalLabelsuccess">Agregar nuevo cliente</h4>
            </div> {{-- modal-header --}}
            <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
              <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="nit_customer2">Nit cliente: *</label>
                  <input type="text" name="nit_customer2" value="C/F" class="form-control" required id="nit_customer2">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="name_customer2">Nombre: *</label>
                  <input type="text" name="name_customer2" value="" class="form-control" required id="name_customer2">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="address_customer2">Dirección: *</label>
                  <input type="text" name="address_customer2" value="Ciudad" class="form-control" required id="address_customer2">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="phone">Teléfono:</label>
                  <input type="text" name="phone" maxlength="8" class="form-control" id="phone">
                </div>
              </div>
            </div>
            <div class="row">
              @if (isset($rutas))
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="ruta">Ruta: *</label>
                  <select name="ruta" id="ruta" class="form-control select2">
                    <option disabled selected>Seleccione ruta</option>
                    @foreach ($rutas as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              @endif
            </div>                               
              <div class="row">
                <div class="col-lg-5"></div>
                <div class="col-lg-3">
                  <button type="button" class="btn  btn-primary" id="btnSaveCustomer">Guardar</button>
                  <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
                <div class="col-lg-4"></div>
              </div> {{-- row --}}
            </div> {{-- modal-body --}}
            
          </div> {{-- modal-content --}}
        </form> 
      </div> {{-- modal-dialog  --}}
      
    </div> {{-- modal fade in  --}}
    {{-- </div> --}}
    <!--  Fin del modal new customer-->    