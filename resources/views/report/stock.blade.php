@extends('layouts/default')

@section('title',trans('report-stock.stock_report'))
@section('page_parent',trans('report-stock.reports'))


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
            {{trans('report-stock.stock_report')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          {!! Form::open(array('url'=>'reports/stock','target'=>'_blank','id'=>'stock_report')) !!}
          
          <div class="row">
            <div class="col-md-3">              
            </div>
            <div class="col-md-3">
              <center><label for=""><b>Fecha inicial</b></label></center>
              <input type="text" name="date1"  id='admited_at'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
            </div>
            <div class="col-md-3">
              <center><label for=""><b>Fecha final</b></label></center>
              <input type="text" name="date2"  id='admited_at2'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
            </div>
            <div class="col-md-3">
              <br>
            </div>
          </div>
          <hr>          
          {{-- ************************************************************************************ --}}
          <div class="row">
            <div class="col-md-12 py-5 alert-info text-center">
                <h4>Reportes </h4>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-5">
              <h4>Existencia general</h4>
            </div>
            <div class="col-md-3">         
            </div>
            <div class="col-md-4">
              <button type="submit"class="btn btn-block btn-pdf" id="existencia_general" name="existencia_general" onclick="existenciaGeneral()">
                <i class="fa fa-file-text-o"></i> Generar
              </button>
            </div>            
          </div>
          {{-- Producto --}}
          <hr>
          <div class="row">
            <div class="col-md-5">
              <h4>Existencia por bodega</h4>
            </div>
            <div class="col-md-3">
              <div class="input-group select2-bootstrap-prepend">
                <select class="form-control" id="almacen_id" name="almacen_id">
                  <option value="0">{{trans('report-stock.show_all_storages')}}</option>
                  @foreach($almacen as $index => $value)
                  <option value="{{$value->id}}" >{{$value->name}}</option>
                  @endForeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <button type="submit"class="btn btn-block btn-pdf" id="existencia_por_bodega" name="existencia_por_bodega" onclick="existenciaPorBodega()">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>            
          </div>
          <hr>
          <div class="row">
            <div class="col-md-5">
              <h4>Existencia por producto</h4>
            </div>
            <div class="col-md-3">
              <div class="input-group select2-bootstrap-prepend">
                <select class="form-control" id="item_id" name="item_id">
                  <option value="0">{{trans('report-stock.show_all_items')}}</option>
                    @foreach($items as $index => $value)
                    <option value="{{$value->id}}" >{{$value->item_name}}</option>
                    @endForeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <button type="submit"class="btn btn-block btn-pdf" id="pdfsrepitem" name="pdfsrepitem" onclick="existenciaPorProducto()">
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
    document.getElementById("stock_report").addEventListener("click", function(event){
        event.preventDefault()
    });
  });
 /*EXISTENCIA GENERAL*/
  function existenciaGeneral(){    
    var b= document.getElementById('stock_report');
    // b.setAttribute('target','_blank');    
    b.setAttribute('action','general_stock_report');
    b.submit();
  }

/*EXISTENCIA POR BODEGA*/
  function existenciaPorBodega(){
    var b= document.getElementById('stock_report');
    var bodega = document.getElementById('almacen_id').value;
    
    b.setAttribute('action','stock_report_by_storage');
    b.submit();
  }

  /*EXISTENCIA POR PRODUCTO*/
  function existenciaPorProducto(){
    var b= document.getElementById('stock_report');
    var bodega = document.getElementById('almacen_id').value;
    
    b.setAttribute('action','stock_report_by_product');
    b.submit();
  }
  
  </script>
  <!--Canlendario  -->
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
  <script>
    $("#admited_at").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");  
    $("#admited_at2").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
  </script>
  @stop
  