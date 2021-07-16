<!-- Modal -->
<div class="modal fade" id="modalExpenses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:rgb(46, 144, 95); color:white">
        <h4 class="modal-title">Registrar Gastos</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-2"></div>
          <div class="col-lg-8">
            <p>
              <strong>NOTA:</strong> Para que los gastos ingresados sean tomados en cuenta en los calculos
              debe dar clic en el botón de color verde con el texto agregar gaasto.
            </p>
          </div>
          <div class="col-lg-2"></div>

        </div>
        <div class="row">
          <!-- Lista de gastos -->
          <div class="todolist">
            <header style="background-color: rgb(14, 99, 105);">
            </header>
            <form class="row list_of_items_gastos">
              <div class='todolist_list3 col-md-8 col-sm-8 col-xs-8 showactions list1'>
                <div class="col-md-6">
                  <b>Detalle de gasto</b>
                </div>
                <div class="col-md-3">
                  <b>Monto</b>
                </div>
              </div>
            </form>
            <div class="todolist_list adds">
              <form role="form" id="main_input_box_gastos" class="form-inline">
                <div class="form-group col-md-6">
                  <input id="txtDescripcion" style="width: 100%;" name="Item" type="text" required placeholder="Ingrese description del gasto." class="form-control cust_text1"
                  />
                </div>
                <div class="form-group col-md-3">
                  <input id="txtAmountGasto" style="width: 100%;" name="Item" type="text" required placeholder="Ingrese monto del gasto" class="form-control cust_text1 money_gasto"
                  />
                </div>
                <div class="form-group col-md-3">
                  <label id="totalGastos"></label> <br>
                  <label id="registrosGastos"></label>
                </div>
                <div class="col-md-12" style="text-align: left;">
                  <input type="submit" value="Agregar gasto" id="btn_save_gasto" class="btn btn-success add_button" />
                </div>
              </form>
            </div>
          </div>
        </div>
          <center>
          <div style="display:none;" class="form-group">
            <label for="target">Total monto de gasto:</label>
            <br>
            <div class="col-lg-3">
            </div>
            <div class="col-lg-6">
              <div class="input-group">
                <span class="input-group-addon">
                  Q
                </span>
                <input disabled type="number" onchange  id="modelGastosTotal"  class="form-control" >
              </div>
            </div>
            <div class="col-lg-3">
            </div>
          </div>
        </center>
      </div>
      <div class="modal-footer" style="text-align:center;">
        <button class="btn  btn-info"  data-dismiss="modal" onclick="buildJsonGastos()">Confirmar</button>
      </div>
    </div>
  </div>
</div>
