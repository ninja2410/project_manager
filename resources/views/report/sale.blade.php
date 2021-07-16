@extends('layouts/default')

@section('title',trans('report-sale.sales_report'))
@section('page_parent',trans('report-sale.reports'))


@section('header_styles')
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
{{-- select 2 --}}
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<?php $permisos=Session::get('permisions'); $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?> 
<section class="content">
  <div class="row">
    <div class="col-md-12 ">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="linechart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('report-sale.sales_report')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          {{-- {!! Form::open(array('url'=>'reports/sales')) !!} --}}
          {!! Form::open(array('url'=>'reports/download-sales','id'=>'sales_report')) !!}
          
          <div class="row">
            <div class="col-md-3">
              <center>
                <label for="">
                  <b>
                    Seleccione documento
                  </b>
                </label>
              </center>
              <select class="form-control" id="documentsName" name="document">
                <option value="Todo" {{ ($document == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
                @foreach($dataDocuments as $index => $value)
                <option value="{{$value->id_serie}}" {{ ($document == $value->id_serie) ?  'selected="selected"' : '' }}>{{$value->document.' '.$value->serie}}</option>
                @endForeach
              </select>
            </div>
            <div class="col-md-3">
              <center class="fecha" @if($all===0) style="display: none" @endif><label for=""><b>Fecha inicial</b></label></center>
              <input type="text" name="date1"  id='admited_at'class="form-control fecha" value="{{date('d/m/Y', strtotime($fecha1))}}" @if($all===0) style="display: none" @endif>
            </div>
            <div class="col-md-3">
              <center class="fecha" @if($all===0) style="display: none" @endif><label for=""><b>Fecha final</b></label></center>
              <input type="text" name="date2"  id='admited_at2'class="form-control fecha" value="{{date('d/m/Y', strtotime($fecha2))}}" @if($all===0) style="display: none" @endif>
            </div>
            <div class="col-md-3">
                <center><label for=""><b>{{trans('credit.use_dates')}}</b></label></center>
                <input type="checkbox" name="all"  id='all' class="form-control" @if($all===1) checked="checked" @endif >
              <br>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12 py-5 alert-info text-center">
                <h4>Reportes Planos</h4>
            </div>
          </div>
          @if($admin)
          <hr>          
          <div class="row">
            <div class="col-md-8">
              <h4>Ventas detalladas generales - admin </h4>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-pdf" id="pdf" name="pdf" onclick="ventasDetAdmin(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-excel" id="excel" name="excel" onclick="ventasDetAdmin(0)">
                <i class="fa fa-table"></i> Excel
              </button>
            </div>
          </div>
        @endif
          <hr>
          <div class="row">
            <div class="col-md-8">
              <h4>Ventas totales por cliente  </h4>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-pdf" id="pdfcustsum" name="pdfcustsum" onclick="ventasCustSum(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-excel" id="excelcustsum" name="excelcustsum" onclick="ventasCustSum(0)">
                <i class="fa fa-table"></i> Excel
              </button>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-8">
              <h4>Ventas por cliente por factura</h4>
            </div>
            <div class="col-md-2">
              <button type="submit"class="btn btn-block btn-pdf" id="pdfcustdet" name="pdfcustdet" onclick="ventasCustDet(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-excel" id="excelcustdet" name="excelcustdet" onclick="ventasCustDet(0)">
                <i class="fa fa-table"></i> Excel
              </button>
            </div>
          </div>
          
          {{-- forma de pago --}}
          <hr>
          <div class="row">
            <div class="col-md-8">
              <h4>Ventas totales por forma de pago </h4>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-pdf" id="pdfpagosum" name="pdfpagosum" onclick="ventasPagoSum(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-excel" id="excelpagosum" name="excelpagosum" onclick="ventasPagoSum(0)">
                <i class="fa fa-table"></i> Excel
              </button>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-8">
              <h4>Ventas por forma pago por factura </h4>
            </div>
            <div class="col-md-2">
              <button type="submit"class="btn btn-block btn-pdf" id="pdfpagodet" name="pdfpagodet" onclick="ventasPagoDet(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-excel" id="excelpagodet" name="excelpagodet" onclick="ventasPagoDet(0)">
                <i class="fa fa-table"></i> Excel
              </button>
            </div>
          </div>
          
          {{-- Vendedor --}}
          <hr>
          <div class="row">
            <div class="col-md-8">
              <h4>Ventas totales por Vendedor </h4>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-pdf" id="pdfsalesrep" name="pdfsalesrep" onclick="ventasSalesRepSum(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-block btn-excel" id="excelsalesrep" name="excelsalesrep" onclick="ventasSalesRepSum(0)">
                <i class="fa fa-table"></i> Excel
              </button>
            </div>
          </div>
          <hr>
          {{-- ************************************************************************************ --}}
          <div class="row">
            <div class="col-md-12 py-5 alert-info text-center">
                <h4>Reportes Agrupados</h4>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-5">
              <h4>Ventas por cliente por forma pago por factura</h4>
            </div>
            <div class="col-md-3">
              <div class="input-group select2-bootstrap-prepend">
                <select class="form-control" id="customer_id" name="customer_id">
                  <option value="0" {{ ($document == '0') ?  'selected="selected"' : '' }}>{{trans('report-sale.show_all_customers')}}</option>
                  @foreach($customers as $index => $value)
                  <option value="{{$value->id}}" >{{$value->nit_customer.' -'.$value->name}}</option>
                  @endForeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <button type="submit"class="btn btn-block btn-pdf" id="pdfcustdetinv" name="pdfcustdetinv" onclick="ventasCustDetInv(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>            
          </div>
          {{-- Por cliente por producto --}}
          <hr>
          <div class="row">
            <div class="col-md-4">
              <h4>Ventas por cliente por producto</h4>
            </div>
            <div class="col-md-2">
              <div class="input-group select2-bootstrap-prepend">
                <select class="form-control" id="customer_idx" name="customer_idx">
                  <option value="0" {{ ($document == '0') ?  'selected="selected"' : '' }}>{{trans('report-sale.show_all_customers')}}</option>
                  @foreach($customers as $index => $value)
                  <option value="{{$value->id}}" >{{$value->nit_customer.' -'.$value->name}}</option>
                  @endForeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="input-group select2-bootstrap-prepend">
                <select class="form-control" id="item_idx" name="item_idx">
                  <option value="0" {{ ($document == '0') ?  'selected="selected"' : '' }}>{{trans('report-sale.show_all_items')}}</option>
                  @foreach($items as $index => $value)
                  <option value="{{$value->id}}" >{{$value->item_name}}</option>
                  @endForeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <button type="submit"class="btn btn-block btn-pdf" id="pdfcustdetinv" name="pdfcustdetinv" onclick="ventasCustProd(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>            
          </div>
          {{-- Producto --}}
          <hr>
          <div class="row">
            <div class="col-md-5">
              <h4>Ventas por producto por vendedor por factura</h4>
            </div>
            <div class="col-md-3">
              <div class="input-group select2-bootstrap-prepend">
                <select class="form-control" id="item_id" name="item_id">
                  <option value="0" {{ ($document == '0') ?  'selected="selected"' : '' }}>{{trans('report-sale.show_all_items')}}</option>
                  @foreach($items as $index => $value)
                  <option value="{{$value->id}}" >{{$value->item_name}}</option>
                  @endForeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <button type="submit"class="btn btn-block btn-pdf" id="pdfitemsrep" name="pdfitemsrep" onclick="ventasItemSrep(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>            
          </div>
          <hr>
          <div class="row">
            <div class="col-md-5">
              <h4>Ventas por vendedor por producto</h4>
            </div>
            <div class="col-md-3">
              <div class="input-group select2-bootstrap-prepend">
                <select class="form-control" id="user_id" name="user_id">
                  <option value="0" {{ ($document == '0') ?  'selected="selected"' : '' }}>{{trans('report-sale.show_all_salesreps')}}</option>
                  @foreach($users as $index => $value)
                  <option value="{{$value->id}}" >{{$value->name}}</option>
                  @endForeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <button type="submit"class="btn btn-block btn-pdf" id="pdfsrepitem" name="pdfsrepitem" onclick="ventasSrepItem(1)">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>
          </div>
          
          {!! Form::close() !!}
          <hr>
          
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
{{-- <script type="text/javascript" src="{{ asset('assets/js/general-function/currency_format.js') }}" ></script> --}}
{{-- Select2 --}}
<script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
<script type="text/javascript">  

$(document).ready(function () {
    $.fn.select2.defaults.set("width", "100%");
    /* Inicialización de dropdowns */
    $('select').select2({
      allowClear: true,
      theme: "bootstrap",
      placeholder: "Buscar"
    });
    $('input[type=checkbox][name=all]').change(function() {
          if ($(this).prop("checked")) {
              $('.fecha').show();
              console.log('1');
          }
          else {
              $('.fecha').hide();
              console.log('0');
          }
      });
  });
 /*VENTAS DETALLADAS AMINISTRADOR*/
  function ventasDetAdmin(att){    
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }
    b.setAttribute('action','sales-det-admin');
    // b.submit();
  }

/*VENTAS DETALLADAS POR CLIENTE*/
  function ventasCustDet(att){    
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }
    b.setAttribute('action','sales-cust-det');
    // b.submit();
  }

  /*VENTAS SUMARIZADAS POR CLIENTE*/

  function ventasCustSum(att){    
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }
    b.setAttribute('action','sales-cust-sum');
    // b.submit();
  }

/*Ventas por cliente por forma pago por factura*/
  function ventasCustDetInv(att){    
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }    
    b.setAttribute('action','sales-cust-inv');
    // b.submit();
  }

  /*Ventas por cliente por forma pago por factura*/
  function ventasCustProd(att){    
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }    
    b.setAttribute('action','sales-cust-prod');
    // b.submit();
  }

  // FORMA PAGO

  /*VENTAS DETALLADAS POR FORMA PAGO*/
  function ventasPagoDet(att){    
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }    
    b.setAttribute('action','sales-pago-det');
    // console.log(b);
    // b.submit();
  }

  /*VENTAS SUMARIZADAS POR FORMA PAGO*/

  function ventasPagoSum(att){    
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }    
    b.setAttribute('action','sales-pago-sum');
    // b.submit();
  }

  /*VENTAS POR PRODUCTO POR VENDEDOR POR FACTURA*/
  
  function ventasItemSrep(att){
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }
    b.setAttribute('action','sales-item-srep');
    console.log(b);
    // b.submit();
  }


  /*VENTAS POR VENDEDOR*/

  function ventasSalesRepSum(att){
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }    
    b.setAttribute('action','sales-salesrep-sum');
    // b.submit();
  }

  
  /*Ventas por vendedor por producto*/
  
  function ventasSrepItem(att){
    var b= document.getElementById('sales_report');
    if(att==1){
      console.log('PDF '+att)
      b.setAttribute('target','_blank');
    } else {
      console.log('Excel '+att)
      b.removeAttribute('target','_blank');
    }
    b.setAttribute('action','sales-salesrep-item');
    console.log(b);
    // b.submit();
  }
  
  </script>
  <!--Canlendario  -->
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
  <script>
    $("#admited_at").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
  </script>
  <script>
    $("#admited_at2").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
  </script>
  @stop
  