@extends('layouts/default')

@section('title',trans('report-sale.profit_by_products'))
@section('page_parent',trans('report-sale.reports'))


@section('header_styles')
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
{{-- select 2 --}}
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12 ">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="linechart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('report-sale.profit_by_products')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          {!! Form::open(array('url'=>'reports/profit_by_product','target'=>'_blank','id'=>'profit_form')) !!}
          <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    {!! Form::label('product', trans('report-sale.items_list'), ['class' => 'control-label']) !!}
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon"><i class="fa fa-archive"></i></div>
                      <select class="form-control" name="product" id="product">
                        <option value="0">Todos</option>
                        @foreach($list_items as $value)
                        <option value="{!! $value->id !!}" >
                          {{ $value->item_name }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
            </div>
            <div class="col-md-2">
              <center><label for=""><b>Fecha inicial</b></label></center>
              <input type="text" name="date1"  id='date1'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
            </div>
            <div class="col-md-2">
              <center><label for=""><b>Fecha final</b></label></center>
              <input type="text" name="date2"  id='date2'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
            </div>
            <div class="col-md-3">
              <br>
              {{-- {!! Form::submit(trans('Generar'), array('class' => 'btn btn-primary ')) !!} --}}
            </div>
          </div>
          
          <hr>
          <div class="row">
            <div class="col-md-8">
              <h4>Rentabilidad por producto por forma de pago</h4>
            </div>
            <div class="col-md-4">
              <button type="submit"class="btn btn-block btn-pdf" id="pdfiteminv" name="pdfiteminv" onclick="profitItemInv()">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>            
          </div>
          <hr>
          <div class="row">
            <div class="col-md-8">
              <h4>Rentabilidad total por producto</h4>
            </div>
            <div class="col-md-4">
              <button type="submit"class="btn btn-block btn-pdf" id="pdfitemtotal" name="pdfitemtotal" onclick="profitItemTotal()">
                <i class="fa fa-file-text-o"></i> PDF
              </button>
            </div>            
          </div>
          {!! Form::close() !!}
        </div>
      </>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/js/general-function/currency_format.js') }}" ></script>
  {{-- Select2 --}}
  <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
<script type="text/javascript">
  $(document).ready(function(){
      /* Inicialización de dropdowns */
      $('select').select2({
      allowClear: true,
      theme: "bootstrap",
      placeholder: "Buscar"
    });
  });

  /* Rentabilidad por producto por factura*/
  function profitItemInv(){
    var b= document.getElementById('profit_form');
    
    b.setAttribute('action','profit-by-product-det');
    b.submit();
  }
/* Rentabilidad por producto total */
  function profitItemTotal(){
    var b= document.getElementById('profit_form');
    
    b.setAttribute('action','profit-by-product-sum');
    b.submit();
  }


  </script>
  <!--Canlendario  -->
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

  <script>
    $("#date1").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
  </script>
  <script>
    $("#date2").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
  </script>
  @stop
