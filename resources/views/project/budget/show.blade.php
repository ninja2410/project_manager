@extends('layouts/default')

@section('title', trans('budget.show'))
@section('page_parent',trans('project.create'))
@section('header_styles')
  <!-- Toast -->
  <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>

  <!-- ALERTS -->
  <link href="{{ asset('assets/css/pages/alerts.css') }}" rel="stylesheet" type="text/css"/>

{{--      CUSTOM CACAO--}}
  <link href="{{ asset('assets/css/budget/custom.css') }}" rel="stylesheet"/>
  {{--  FONTICONS  --}}
{{--  <link href="{{asset("assets/fontawesome/css/all.css")}}" rel="stylesheet">--}}

  <style>
    :root {
      --color_primary: {{$parameters->primary}};
      --color_secundary: {{$parameters->second}};
    }
  </style>
  {{-- PRINTABLE --}}
  <link href="{{ asset('assets/css/budget/printable.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
  <input type="hidden" id="token" value="{{ csrf_token() }}">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                 data-loop="true"></i>
              {{trans('budget.show')}}
            </h3>
            <div class=" kal pull-right">
              <!-- Tabs -->
              <ul class="nav panel-tabs">
                <li class="active">
                  <a href="#tab1" data-toggle="tab">{{trans('budget.broken_down_budget')}}</a>
                </li>
                <li>
                  <a href="#tab2" class="calcTab" data-toggle="tab">{{trans('budget.integrated_budget')}}</a>
                </li>
                <li>
                  <a href="#tab3" class="calcTab" data-toggle="tab">{{trans('budget.material_summary')}}</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="panel-body">
            <div id="dvContents">
              <div class="col-md-12">
                <div class="row">
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="row">
                        <div class="col-xs-2 text-center color_primary">
                          {{trans('budget.budget')}}
                          <h5 style="color: var(--color_primary)">No. {{$budget->correlative}}</h5>
                        </div>
                        <div class="col-xs-7 text-center logo_proforma">
                          <img class="logo_invoice" src="{{ asset('images/system/logo2.png') }}"
                               alt="">
                        </div>
                        <div class="col-xs-3">
                          <h5>Fecha</h5>
                          <h5> {{$budget->date}}</h5>
                        </div>
                      </div>
                      @if($imprimir_propietario==1)
                        <div class="row">
                          <div class="col-xs-12 text-center slogan">
                            {{$parameters->slogan}}
                          </div>
                        </div>
                      @endif
                      <input type="hidden" id="name_document" name="name_document"
                             value="{{$budget->description}}">
                      <br>
                      <div class="row">
                        <div class="col-xs-8">
                          <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                          <strong>{{trans('parameter.address')}}
                            :</strong> {{$parameters->address}}
                        </div>
                        <div class="col-xs-4 text-right">
                          <address>
                            <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                            <strong>{{trans('parameter.phone')}}:</strong> {{$parameters->phone}}
                          </address>
                        </div>
                      </div>
                      @if($imprimir_propietario==1)
                        <div class="row">
                          <div class="col-xs-12">
                            <div class="col-xs-4 text-center back_primary ceo"
                                 style="height: 65px;">
                              {{$parameters->ceo}}
                            </div>
                            <div class="col-xs-8 text-center back_second description"
                                 style="height: 65px;">
                              {{$parameters->description}}
                            </div>
                          </div>
                        </div>
                      @endif
                      <br>
                      <div class="row">
                        <div class="col-xs-6">
                          <div class="customer-info">
                            <strong>{{trans('budget.project')}}:</strong> {{$budget->project->name}} <font style="font-size:12px">@if (isset($documento[0]->date_payments)) <strong>&nbsp;&nbsp;| Fecha Pago:&nbsp;</strong>{{date('d/m/Y', strtotime(substr($documento[0]->date_payments,0,10)))}} @endif</font>
                          </div>
                        </div>
                        <div class="col-xs-3">
                          <div class="customer-info">
                            <strong>{{trans('customer.phone_number')}}:</strong> {{$budget->project->customer->phone_number}}
                          </div>
                        </div>
                        <div class="col-xs-3">
                          <div class="customer-info">
                            <strong>{{trans('budget.days')}}</strong> {{$budget->days}}
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-xs-6">
                            <div class="customer-info">
                              <strong>{{trans('project.customer')}}: </strong>{{$budget->project->customer->name}}
                            </div>
                          </div>
                          <div class="col-xs-6">
                            <div class="customer-info">
                              <strong>{{trans('customer.address')}}: </strong> {{$budget->project->customer->address}}
                            </div>
                          </div>
                        </div>
                      </div>
                      <br>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="tab-content" id="slim1">
                    <div class="tab-pane text-justify active" id="tab1">
                      @include('project.budget.components.show_detail')
                    </div>
                    <div class="tab-pane text-justify" id="tab2">
                      @include('project.budget.components.show_integrated')
                    </div>
                    <div class="tab-pane text-justify" id="tab3">
                      @include('project.budget.components.show_material_summary')
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <label for="comment">{{trans('budget.comments')}}</label>
                  <p>{{$budget->comments}}</p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4"></div>
              <div class="col-lg-4" style="text-align: center;">
                <div class="form-group">
                  @if($budget->status_id == 1)
                    <button type="button" class="btn btn-primary" onclick="print()">
                      {{trans('button.print')}}
                    </button>
                    <a class="btn btn-warning"
                       href="{{ url('project/'.$budget->project_id.'/budget/'.$budget->id.'/edit') }}">
                      {{trans('button.edit')}}
                    </a>
                  @endif
                  <a class="btn btn-danger" href="{{ url('project/'.$budget->project_id.'/budget') }}">
                    {{trans('button.cancel')}}
                  </a>
                </div>
              </div>
              <div class="col-lg-4"></div>
            </div>
            {{--            AQUI VAN LOS BOTONES--}}
          </div>
        </div>
      </div>
  </section>
@endsection
@section('footer_scripts')
  <script type="text/javascript" src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
  <script>
      function print() {
          var name_document = $('#name_document').val();
          var contents = $("#dvContents").html();
          var frame1 = $('<iframe />');
          frame1[0].name = "frame1";
          frame1.css({"position": "absolute", "top": "-1000000px"});
          $("body").append(frame1);
          var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
          frameDoc.document.open();
          //Create a new HTML document.
          frameDoc.document.write('<html><head><title>' + name_document + '</title>');
          frameDoc.document.write('</head><body>');
          //Append the external CSS file.
          frameDoc.document.write('<style>:root { --color_primary: {{$parameters->primary}}; --color_secundary: {{$parameters->second}}; }</style>');
          frameDoc.document.write('<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />');
          frameDoc.document.write('<link href="{{ asset('assets/css/budget/printable.css')}}" rel="stylesheet" type="text/css" />');
          //Append the DIV contents.
          frameDoc.document.write(contents);
          frameDoc.document.write('</body></html>');
          frameDoc.document.close();
          setTimeout(function () {
              window.frames["frame1"].focus();
              window.frames["frame1"].print();
              frame1.remove();
          }, 500);
      }
  </script>
@endsection
