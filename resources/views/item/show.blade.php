@extends('layouts/default')

@section('title',trans('item.item_details'))
@section('page_parent',trans('item.items'))

@section('header_styles')
  <link href="{{asset('assets/css/pages/user_profile.css')}}" rel="stylesheet" type="text/css"/>
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

  <!-- ALERTS -->
  <link href="{{ asset('assets/css/pages/alerts.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <?php $permisos=Session::get('permisions'); $array_p = array_column(json_decode(json_encode($permisos), True), 'ruta');  ?>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">
                <i class="livicon" data-name="align-left" data-size="18" data-c="#fff" data-hc="#fff"
                   data-loop="true"></i>
                {{trans('item.item_details')}}
              </h3>
              <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
            </div>
            @if($show_budget_price->active)
              @if(!$valid_price)
                <div class="row">
                  <div class="alert-message alert-message-danger">
                    <h4>El costo para presupuesto ha vencido!</h4>
                    <p>
                      Se ha superado el tiempo válido del costo para elaborar presupuestos.
                    </p>
                  </div>
                </div>
              @endif
            @endif
            <div class="row ">
              <div class="col-md-4">
                <br>
                <div class="form-group">
                  <div class="text-center">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                      <div class="fileinput-new thumbnail">
                        <img src="{!! asset('images/items/') . '/' . $data->avatar !!}"
                             class="img-responsive user_image" alt="{{$data->name}}" style="max-width: 200px;"/>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <center>
                    <a class="btn btn-danger" href="{{ URL::previous() }}">
                      {{trans('button.back')}}
                    </a>
                    <a @if(in_array('ítems/edit',$array_p)) class="btn btn-info"
                       href="{{ URL::to('items/' . $data->id . '/edit') }}" data-original-title="Editar"
                       @else data-original-title="No tiene permisos" readonly="readonly" @endif  data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>&nbsp;Editar
                    </a>
                  </center>
                </div>
                <br>
                <div class="table-responsive">
                  <center>
                    <a class="btn btn-primary" href="{{ url('/items/search') }}">
                      {{trans('menu.item_search')}}
                    </a>
                    <br><br>
                    <a class="btn btn-warning" href="{{ url('items') }}">
                      {{trans('menu.item_catalog')}}
                    </a>
                  </center>
                </div>
              </div>
              <div class="col-md-8">
                <ul class="nav nav-tabs ul-edit responsive">
                  <li>
                    <a href="#tab-change-pwd" data-toggle="tab">
                      <i class="livicon" data-name="align-left" data-size="16" data-c="#01BC8C" data-hc="#01BC8C"
                         data-loop="true"></i> {{trans('item.item_data')}}
                    </a>
                  </li>
                </ul>
                <div class="table-responsive">
                  <table class="table  table-striped" id="users">
                    <tr>
                      <td>{{ trans('item.upc_ean_isbn') }}</td>
                      <td>
                        <strong>{{$data->upc_ean_isbn}}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td>{{ trans('item.item_name') }}</td>
                      <td>
                        <strong>{{$data->item_name}}</strong>
                      </td>
                    </tr>
                    <td>{{ trans('item.description') }}</td>
                    <td>
                      <strong>{{$data->description}}</strong>
                    </td>
                    </tr>
                    <td>{{ trans('item.category') }}</td>
                    <td>
                      <strong>{{$data->itemCategory->name or 'N/A' }}</strong>
                    </td>
                    </tr>
                    <tr>
                      <td>{{ trans('item.item_type') }}</td>
                      <td>
                        <strong>{{$data->itemType->name or 'N/A' }}</strong>
                      </td>
                    </tr>

                    <tr>
                      <td>{{ trans('item.size') }}</td>
                      <td>
                        <strong>{{$data->size}}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {{ trans('item.minimal_existence_long') }}
                      </td>
                      <td>
                        <strong>{{$data->minimal_existence}}</strong>
                      </td>
                    </tr>
                    @if(Session::get('administrador',false))
                      <tr>
                        <td>
                          {{ trans('item.cost_price') }}</strong>
                        </td>
                        <td>
                          <strong>@money($data->cost_price)</strong>
                        </td>
                      </tr>
                      @if($show_budget_price->active)
                        <tr>
                          <td>
                            {{ trans('item.budget_cost') }}</strong>
                          </td>
                          <td>
                            <strong>@money($data->budget_cost)</strong>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            {{ trans('item.valid') }}</strong>
                          </td>
                          <td>
                            <strong>{{$data->days_valid . ' '.trans('item.days'). ' / '.$data->monts_valid.' '.trans('item.months')}}</strong>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            {{ trans('item.last_updated_budget_cost') }}</strong>
                          </td>
                          <td>
                            @if($data->updated_budget_cost_at != '0000-00-00')
                              <strong>{{date('d/m/Y', strtotime($data->updated_budget_cost_at))}}</strong>
                            @else
                              <strong>No configurado</strong>
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td>
                            {{ trans('item.wildcard') }}</strong>
                          </td>
                          <td>
                            <strong>{{$data->wildcard ? 'Si' : 'No'}}</strong>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            {{ trans('item.approach_type') }}</strong>
                          </td>
                          <td>
                            <strong>{{$data->approach_type==1 ? trans('item.toInteger') : trans('item.toDecimal')}}</strong>
                          </td>
                        </tr>
                      @endif
                      {{-- <tr>
                          <td>
                              {{ trans('item.profit') }}</strong>
                          </td>
                          <td>
                              <strong>{{(number_format($data->profit, 2))}}%</strong>
                          </td>
                      </tr> --}}
                    @endif
                    <tr>
                      <td>{{ trans('item.item_last_tx') }}</td>
                      <td>
                        <strong>{{ $last_tx or 'Ninguna' }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td>{{ trans('item.existence_total') }}</td>
                      <td>
                        <strong>{{ $existencia }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" style="text-align:center;">
                        <strong>{{trans('item.price_list')}}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <table style="width:100%" class="table  table-striped">
                          @foreach($prices as $key)
                            <tr>
                              <td>{{ $key->name }}</td>
                              <td style="border-right: 1px solid #ddd;"><strong>@if($key->selling_price==null)
                                    @money($data->cost_price+(($key->pct/100)*$data->cost_price)) @else
                                    @money($key->selling_price) @endif</strong></td>
                              <td>{!! trans('unit_measure.unit_measure') !!}</td>
                              <td style="border-right: 1px solid #ddd;"><strong>
                                {{$key->unidad}}
                              </strong></td>
                              {{-- <td style="border-right: 1px solid #ddd;"><strong>@if($key->low_price==null)
                                    @money($data->cost_price+(($key->pct_min/100)*$data->cost_price)) @else
                                    @money($key->low_price) @endif</strong></td> --}}
                              <td>{{ trans('item.pagos')}}</td>
                              <td>
                                @foreach ($key->pagos as $pago)
                                  <strong>{{ $pago->name.', ' }}</strong>
                                @endforeach
                              </td>
                            </tr>
                          @endforeach
                        </table>
                      </td>
                    </tr>

                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
@section('footer_scripts')
  <!-- Add mousewheel plugin (this is optional) -->
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/jquery.fancybox.pack.js')}}"></script>
  <script type="text/javascript"
          src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-thumbs.js')}}"></script>

  <script type="text/javascript">
      $(document).ready(function () {
          $('[data-toggle="tooltip"]').tooltip();
      });
  </script>
@stop
