 @extends('layouts/default')

@section('title', trans('budget.edit'))
@section('page_parent',trans('project.project'))
@section('header_styles')
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
  <!-- Toast -->
  <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
  {{-- date time picker --}}
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
  <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
  <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>

  {{--  XEDITABLE  --}}
  <link href="{{ asset('assets/x-editable/bootstrap-editable.css') }}" rel="stylesheet"/>

  {{--    CUSTOM CACAO--}}
  <link href="{{ asset('assets/css/budget/custom.css') }}" rel="stylesheet"/>

  {{--  FONTICONS  --}}
  <link href="{{asset("assets/fontawesome/css/all.css")}}" rel="stylesheet">
  <!-- ALERTS -->
  <link href="{{ asset('assets/css/pages/alerts.css') }}" rel="stylesheet" type="text/css"/>
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
              {{trans('budget.edit')}}
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
              </ul>
            </div>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="alert-message alert-message-success">
                <div class="row">
                  <h2>{{trans('project.name')}}: <strong>{{$project->name}}</strong></h2>
                  <h4>{{trans('customer.customer')}}: <strong>{{$project->customer->name}}</strong></h4>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblDate', trans('budget.date')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-calendar"></li></span>
                    {!! Form::text('date', $budget->date, array('id'=>'date','class' => 'form-control date', 'form'=>'frmSend')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblDate', trans('budget.days')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-flag"></li></span>
                    {!! Form::text('days', $budget->days, array('id'=>'days','class' => 'form-control money', 'form'=>'frmSend')) !!}
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="tab-content" id="slim1">
                <div class="tab-pane text-justify active" id="tab1">
                  @include('project.budget.components.budget_detail')
                </div>
                <div class="tab-pane text-justify" id="tab2">
                  @include('project.budget.components.budtet_integrated')
                </div>
              </div>
            </div>
            <div>
              {{--              FORMULARIO DE GUARDADO--}}
              {!! Form::open(['url' => 'project/'.$project->id.'/budget/'.$budget->id, 'method' => 'PUT', 'id'=>'frmSend']) !!}
              {!! Form::hidden('data', '', ['id' => 'data']) !!}
              {!! Form::hidden('budgetHead', '', ['id' => 'head']) !!}

              {!! Form::close() !!}
            </div>
            <div class="row">
              @include('partials.buttons_withmodal', ["cancel_url" => url('project/'.$project->id.'/budget/')])
            </div>
            <div>
              @include('layouts.modal_confirm_generic', ['confirm' => "Desea actualizar los datos del presupuesto?"])
            </div>
          </div>
        </div>
      </div>
  </section>
@endsection
@section('footer_scripts')
  {{-- FORMATO DE MONEDAS --}}
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>

  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
  <script type="text/javascript" src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
          type="text/javascript"></script>
  <script type="text/javascript " src="{{ asset('assets/js/route/validations.js')}} "></script>
  <script language="javascript" type="text/javascript"
          src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>

  {{--  XEDITABLE  --}}
  <script src="{{ asset('assets/x-editable/bootstrap-editable.min.js') }}" type="text/javascript"></script>

  {{--    CUSTOM JS CACAO--}}
  <script src="{{ asset('assets/js/budget/functions.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/js/budget/main.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/js/budget/ClassLib.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/js/budget/edit.js') }} " type="text/javascript "></script>

@endsection
