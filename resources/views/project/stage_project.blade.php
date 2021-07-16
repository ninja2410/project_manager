@extends('layouts/default')

@section('title',trans('project.managment'))
@section('page_parent',trans('project.projects'))
@section('header_styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/acc-wizard/acc-wizzard.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/acc-wizard/accordionforwizard.css') }}">
  <link href="{{asset('assets/css/dropzone.css')}}" rel="stylesheet" type="text/css"/>
  <link href="{{asset('assets/css/pages/advbuttons.css')}}" rel="stylesheet" type="text/css"/>
  <link href="{{asset('css/pages/buttons.css')}}" rel="stylesheet" type="text/css"/>
  <link href="{{asset('assets/css/pages/user_profile.css')}}" rel="stylesheet" type="text/css"/>
  {{-- date time picker --}}
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>

  <!-- ALERTS -->
  <link href="{{ asset('assets/css/pages/alerts.css') }}" rel="stylesheet" type="text/css"/>

  <!-- Add fancyBox main CSS files -->
  <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/fancybox/jquery.fancybox.css')}}"
        media="screen"/>
  <!-- Add Button helper (this is optional) -->
  <link rel="stylesheet" type="text/css"
        href="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.css')}}"/>
  <!-- Add Thumbnail helper (this is optional) -->
  <link rel="stylesheet" type="text/css"
        href="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-thumbs.css')}}"/>
  <!--page level css end-->
@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                 data-loop="true"></i>
              {{$project->code}} | {{$project->name}}
            </h3>
            <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
          </div>
          <div class="panel-body">
            @if (Session::has('message'))
              <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            <div class="row">
              <div class="row">
                <div class="alert-message alert-message-success">
                  <div class="row">
                    <div class="col-lg-10">
                      <h2>{{trans('project.name')}}: <strong>{{$project->name}}</strong></h2>
                        <h4>{{trans('customer.customer')}}: <strong>{{$project->customer->name}}</strong></h4>
                      <input type="hidden" id="project_status" value="{{$project->status}}">
                    </div>
                    <div class="col-lg-2">
                      <div class="pull-right">
                        <a class="btn btn-warning" href="{{ URL::to('project/projects/logs/'.$project->id ) }}"
                           data-toggle="tooltip" data-original-title="Ver historial de cambios">
                          <span class="glyphicon glyphicon-log-out"></span>&nbsp;{{trans('project.log')}}
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      @if ($acc_price)
                        <h4>{{trans('project.price')}}: <strong>@money($project->price)</strong></h4>
                      @endif
                      @if ($project->create_account && $acc_balance)
                        <h4>{{trans('project.balance')}}: <strong>@money($project->account->pct_interes)</strong></h4>
                      @endif
                      <h4>{{trans('project.type_id')}}: <strong>{{$project->type->name}}</strong></h4>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        {!! Form::label('lblName', 'Descripción', array('class'=>'control-label')) !!}
                        <div class="input-group">
                          <label for="" class="control-label">{{$project->description}}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    @if ($project->create_account)
                        @if ($acc_revenues)
                          <div class="col-lg-2 btnMenu">
                            <a  class="list-group-item facebook-like" href="{{ URL::to('project/revenues/'.$project->account_id ) }}" data-toggle="tooltip"
                                data-original-title="Ver ingresos monetarios del proyecto">
                              <p class="pull-right">
                                <i class="fa fa-sign-in fa-2x"></i>
                              </p>
                              <h4 class="list-group-item-heading count">{{trans('project.revenues')}}&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h4>
                            </a>
                          </div>
                        @endif
                        @if ($acc_expenses)
                            <div class="col-lg-2 btnMenu">
                              <a  class="list-group-item google-plus" href="{{ URL::to('project/expenses/'.$project->account_id ) }}" data-toggle="tooltip"
                                  data-original-title="Ver gastos monetarios del proyecto">
                                <p class="pull-right">
                                  <i class="fa fa-sign-out fa-2x"></i>
                                </p>
                                <h4 class="list-group-item-heading count">{{trans('project.expenses')}}&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h4>
                              </a>
                            </div>
                        @endif
                        @if ($acc_retentions)
                            <div class="col-lg-2 btnMenu">
                              <a  class="list-group-item visitor" href="{{ URL::to('project/retentions/'.$project->id ) }}" data-toggle="tooltip"
                                  data-original-title="Ver retenciones realizadas">
                                <p class="pull-right">
                                  <i class="fa fa-gavel fa-2x"></i>
                                </p>
                                <h4 class="list-group-item-heading count">{{trans('project.retentions')}}</h4>
                              </a>
                            </div>
                        @endif
                        @if ($acc_balance)
                            <div class="col-lg-2 btnMenu">
                              <a  class="list-group-item budget" href="{{ URL::to('banks/accounts/statement/'.$project->account_id ) }}" data-toggle="tooltip"
                                  data-original-title="Ver estado de cuenta de proyecto">
                                <p class="pull-right">
                                  <i class="fa fa-money fa-2x"></i>
                                </p>
                                <h4 class="list-group-item-heading count">{{trans('accounts.statement')}}&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h4>
                              </a>
                            </div>
                        @endif
                    @endif
                      <div class="col-lg-2 btnMenu">
                        <a  class="list-group-item count" href="{{ URL::to('project/'.$project->id.'/budget/' ) }}" data-toggle="tooltip"
                            data-original-title="Ver presupuestos de proyecto">
                          <p class="pull-right">
                            <i class="fa fa-money fa-2x"></i>
                          </p>
                          <h4 class="list-group-item-heading count">{{trans('project.budgets')}}</h4>
                        </a>
                      </div>
                  </div>
                </div>
              </div>

              <div class="row acc-wizard">
                <div class="col-md-3 pd-2">
                  <p class="mar-2">
                    {{trans('project.steps')}}
                  </p>
                  <ol class="acc-wizard-sidebar">
                    @foreach ($stages as $key => $value)
                      @if ($value->complete == 1)
                        <li id="step_stage_{{$value->id}}" class="acc-wizard-completed acc-wizard-active">
                      @else
                        <li id="step_stage_{{$value->id}}">
                          @endif
                          <a href="#stage_{{$value->id}}">{{$value->name}}</a>
                        </li>
                        @endforeach
                  </ol>
                </div>
                <input type="hidden" name="project" id="project_id" value="{{$project->id}}">
                <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                <div class="col-md-9">
                  <div id="accordion-demo" class="panel-group">
                    @foreach ($stages as $key => $value)
                      <div class="panel panel-success">
                        <div class="panel-heading clearfix" style="background-color:{{$value->color}}; height: 13%;">
                          <h4 class="panel-title pull-left">
                            <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff"
                               data-hc="white"></i>
                            <a href="#stage_{{$value->id}}" data-parent="#accordion-demo"
                               data-toggle="collapse">{{$value->name}}</a>
                          </h4>
                          @if (isset($value->register) && $value->register->status == 1)
                            <div id="reload_stage_{{$value->id}}" class="pull-right">
                              @else
                                <div id="reload_stage_{{$value->id}}" class="pull-right" style="display: none;">
                                  @endif
                                  @if (Session::get('administrador', false))
                                    <button type="button" class="btn btn-sm btn-default btn_reload"
                                            stage_id="{{$value->id}}" }}><span
                                              class="glyphicon glyphicon-refresh"></span>Reactivar etapa
                                    </button>
                                  @endif
                                </div>
                            </div>
                            <div id="stage_{{$value->id}}" class="panel-collapse collapse awd-h"
                                 stage_id="{{$value->id}}">
                              <div class="row">
                                  <?php
                                  $filter = $atributes->filter(function ($val) use ($value) {
                                      if ($val['stage_id'] == $value->id) {
                                          return true;
                                      }
                                  });
                                  ?>
                                <div class="panel-body">
                                  <form id="form-prerequisites">
                                    <fieldset @if($value->complete == 1 || $project->status != 1) disabled
                                              @endif id="stage_inputs_group_{{$value->id}}">
                                      <div class="row">
                                        @foreach ($filter as $key => $atribute)
                                              <?php
                                              $vTemp = $values->filter(function ($val2) use ($atribute) {
                                                  if ($val2['atribute_id'] == $atribute->id) {
                                                      return true;
                                                  }
                                              });
                                              ?>
                                          <div class="{{$atribute->size}}">
                                            <div class="form-group">
                                              <label for="{{$atribute->id}}">{{$atribute->name}}</label>
                                              <div class="input-group">
                                                <span class="input-group-addon">{{$atribute->name[0]}}</span>
                                                @if ($atribute->type == 'checkbox' || $atribute->type =='color')
                                                      <?php
                                                      $type = $atribute->type;
                                                      ?>
                                                @else
                                                      <?php
                                                      $type = 'text';
                                                      ?>
                                                @endif
                                                <input type="{{$type}}"
                                                       @if ($atribute->type=="checkbox")
                                                       @if (isset($vTemp->first()->value))
                                                       @if ($vTemp->first()->value=="true")
                                                       checked
                                                       @endif
                                                       @endif
                                                       @endif
                                                       class="form-control {{$atribute->type}}" name="{{$atribute->id}}"
                                                       onchange="saveValue(this)"
                                                       value="{!! isset($vTemp->first()->value) ? $vTemp->first()->value : ""!!}">
                                              </div>
                                            </div>
                                          </div>
                                        @endforeach
                                      </div>
                                    </fieldset>

                                    <div class="acc-wizard-step"></div>
                                  </form>
                                </div>
                                <!--/.panel-body -->
                                @if ($value->galery==1)
                                      <?php
                                      $vTemp = $images->filter(function ($val2) use ($value) {
                                          if ($val2['stage_id'] == $value->id) {
                                              return true;
                                          }
                                      });
                                      ?>
                                  <div class="row">
                                    <hr>
                                    <h4>{{trans('project.images')}}</h4>
                                    <div class="col-lg-10">
                                      <div class="gallery-padding panel">
                                        <div class="row col-md-12">
                                          @foreach ($vTemp as $key => $img)
                                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-3 gallery-border">
                                              <a class="fancybox-thumbs" data-fancybox-group="thumb"
                                                 href="{!! asset('images/project/') . '/' . $img->path !!}">
                                                  <?php
                                                  $val = (pathinfo((url('images/project/') . '/' . $img->path), PATHINFO_EXTENSION));
                                                  ?>
                                                @if($val =='pdf')
                                                  <img src="{!! asset('img/pdf.png')!!}"
                                                       class="img-responsive gallery-style" alt="Image">
                                                @elseif($val == 'xlsx')
                                                  <img src="{!! asset('img/xlsx.png')!!}"
                                                       class="img-responsive gallery-style" alt="Image">
                                                @elseif($val == 'docx')
                                                  <img src="{!! asset('img/docx.png')!!}"
                                                       class="img-responsive gallery-style" alt="Image">
                                                @else
                                                  <img src="{!! asset('images/project/') . '/' . $img->path !!}"
                                                       class="img-responsive gallery-style" alt="Image">
                                                @endif

                                              </a>
                                            </div>
                                          @endforeach
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-2">
                                      <button type="button" class="btn btn-primary" data-toggle="modal"
                                              data-target=".full-width{{$value->id}}">Administrar
                                        <br> archivos
                                      </button>
                                    </div>
                                    <div class="modal fade full-width{{$value->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                      <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                              ×
                                            </button>
                                            <h4 class="modal-title">{{trans('project.galery')}}</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="row">
                                              <label for="my-awesome-dropzone">Agregar archivos</label>
                                              <form class="dropzone" action="{{ route('images')}}" method="post"
                                                    id="my-awesome-dropzone">
                                                <input type="hidden" name="_token" id="token" value="{{csrf_token()}}">
                                                <input type="hidden" name="project_id" id="project_id"
                                                       value="{{$project->id}}">
                                                <input type="hidden" name="stage_id" value="{{$value->id}}"
                                                       id="stage_id">
                                              </form>
                                            </div>
                                            <hr>
                                            <div class="row">
                                              <table class="table table-striped table-bordered" style="width: 100%"
                                                     id="table1">
                                                <thead>
                                                <tr>
                                                  <th>{{trans('project.old_name')}}</th>
                                                  <th>{{trans('project.preview')}}</th>
                                                  <th>{{trans('project.actions')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($vTemp as $image)
                                                  <tr>
                                                    <td>{{$image->original_name}}</td>
                                                    <td>


                                                                            <span class="table-remove">
                                                                                <button type="button"
                                                                                        class="btn btn-primary"
                                                                                        <?php
                                                                                        $val = (pathinfo((url('images/project/') . '/' . $image->path), PATHINFO_EXTENSION));
                                                                                        ?>
                                                                                        @if($val =='pdf')
                                                                                        onclick="showImage('{!! asset('img/pdf.png') !!}', '{{$image->original_name}}')"
                                                                                        @elseif($val == 'xlsx')
                                                                                        onclick="showImage('{!! asset('img/xlsx.png') !!}', '{{$image->original_name}}')"
                                                                                        @elseif($val == 'docx')
                                                                                        onclick="showImage('{!! asset('img/docx.png') !!}', '{{$image->original_name}}')"
                                                                                        @else
                                                                                        onclick="showImage('{!! asset('images/project/') . '/' . $image->path !!}', '{{$image->original_name}}')"
                                                                                @endif
                                                                                    >
                                                                                    <span class="glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;Ver archivo
                                                                                </button>

                                                                          </span>

                                                    </td>
                                                    <td>
                                                                        <span class="table-remove">
                                                                            <button type="button"
                                                                                    class="btn btn-primary btn-danger"
                                                                                    data-toggle="modal"
                                                                                    data-target="#modal{!! $image->id !!}">
                                                                              <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;
                                                                              Eliminar
                                                                            </button>
                                                                            {{--Begin modal--}}
                                                                            <div class="modal fade modal-fade-in-scale-up"
                                                                                 tabindex="-1"
                                                                                 id="modal{!! $image->id !!}"
                                                                                 role="dialog"
                                                                                 aria-labelledby="modalLabelfade"
                                                                                 aria-hidden="true">
                                                                              <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                  <div class="modal-header bg-danger">
                                                                                    <h4 class="modal-title">Confirmación Eliminar</h4>
                                                                                  </div>
                                                                                  <div class="modal-body">
                                                                                    <div class="text-center">
                                                                                      ¿Desea eliminar el archivo <strong>{{$image->original_name}}</strong>?
                                                                                    </div>
                                                                                  </div>
                                                                                  <div class="modal-footer"
                                                                                       style="text-align:center;">
                                                                                    {!! Form::open(array('url' => url('images/delete/'. $image->id) , 'method'=>'DELETE')) !!}
                                                                                      <button type="submit"
                                                                                              class="btn  btn-info">Aceptar</button>
                                                                                      <button class="btn  btn-danger"
                                                                                              data-dismiss="modal">Cancelar</button>
                                                                                    {!! Form::close() !!}
                                                                                  </div>
                                                                                </div>
                                                                              </div>
                                                                            </div>
                                                                            {{--End modal--}}
                                                                          </span>
                                                    </td>
                                                  </tr>
                                                @endforeach
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" data-dismiss="modal" class="btn btn-default">Cerrar
                                            </button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                @endif
                              </div>
                            </div>
                        </div>
                        @endforeach
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    {{--  MODAL VER IMAGEN DE PDORUCTO--}}
    @include('partials.show_image')
    {{--  FIN MODAL VER IMAGEN DE PRODUCTO--}}
  </section>
@endsection
@section('footer_scripts')
  <script type="text/javascript" src="{{ asset('assets/js/ac_wizard/acc_wizzard.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/ac_wizard/accforwizzard.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/stages_project/stages_project.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/dropzone.js') }}"></script>

  {{-- FORMATO DE NUMERO --}}
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
  {{--DATETIME PICKER--}}
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
          type="text/javascript"></script>

  <!-- Add mousewheel plugin (this is optional) -->
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/jquery.fancybox.pack.js')}}"></script>
  <script type="text/javascript"
          src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-thumbs.js')}}"></script>
  <!-- Add Media helper (this is optional) -->
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-media.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/pages/gallery.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/buttons.js')}}"></script>
  <script>
      $(document).ready(function () {
          $('.number').toArray().forEach(function (field) {
              new Cleave(field, {
                  numeral: true,
                  numeralPositiveOnly: true,
                  numeralThousandsGroupStyle: 'thousand'
              });
          });
          var dateNow = new Date();
          $(".date").datetimepicker({
              sideBySide: true,
              locale: 'es',
              format: 'DD/MM/YYYY',
              defaultDate: dateNow
          }).parent().css("position :relative");
          $('table').DataTable({
              "language": {"url": "{{ asset('assets/json/Spanish.json') }}"},
              "lengthMenu": [[4], [4]]
          });
      });

      /*
    * MODAL PARA MOSTRAR IMAGENES DE PRODUCTOS
    * */
      function showImage(avatar, nombre) {
          $('#lblTitulo').text(nombre);
          $('#image').attr("src", avatar);
          $('#modal-image').modal("show");
      }

      /*--------------------------------------*/
  </script>
@endsection
