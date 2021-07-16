@extends('layouts/default')

@section('title',trans('itemkit.item_create'))
@section('page_parent',trans('itemkit.item_create'))

@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />

{{-- <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" /> --}}
@stop
@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/item.kits.js', array('type' => 'text/javascript')) !!}


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                    {{trans('itemkit.new_item_kit')}}
                </h3>
                <span class="pull-right clickable">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </span>
            </div>

            <div class="panel-body">

                

                <div class="row" ng-controller="SearchItemCtrl">

                    <div class="col-md-3">
                        <label>{{trans('itemkit.search_item')}} <input ng-model="searchKeyword" class="form-control"></label>

                        <table class="table table-hover">
                            <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">

                                <td>@{{item.item_name}}</td><td><button class="btn btn-primary btn-xs" type="button" ng-click="addItemKitTemp(item)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button></td>

                            </tr>
                        </table>
                    </div>

                    <div class="col-md-9">

                        <div class="row">

                            {!! Form::open(array('url' => 'store-item-kits','id'=>'item_kit_form', 'class' => 'form-horizontal')) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="item_kit_name"
                                           class="control-label">{{trans('itemkit.item_kit_name')}}</label>
                                    <input type="text" class="form-control" name="item_kit_name" id="item_kit_name"
                                           value="{{Input::old('item_kit_name')}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="item_kit_name"
                                           class="control-label">{{trans('itemkit.categorie')}}</label>
                                    <select class="form-control" name="categorie_id">
                                        @foreach ($categories as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                        <label for="description" class="control-label">{{trans('itemkit.description')}}</label>
                                        <input type="text" class="form-control" name="description" id="description" value="{{Input::old('description')}}"/>
                                </div>
                                <div class="col-md-4">
                                        <label for="code" class="control-label">{{trans('itemkit.code')}}</label>
                                        <input type="text" class="form-control" name="code" id="code" value="{{Input::old('code')}}"/>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <table class="table table-bordered">
                                <tr><th>{{trans('itemkit.item_id')}}</th><th>{{trans('itemkit.item_name')}}</th><th>{{trans('itemkit.quantity')}}</th><th>&nbsp;</th></tr>
                                <tr ng-repeat="newitemkittemp in itemkittemp">
                                    <td>@{{newitemkittemp.item_id}}</td><td>@{{newitemkittemp.item.item_name}}</td><td><input type="text" style="text-align:center" autocomplete="off" name="quantity" ng-change="updateItemKitTemp(newitemkittemp)" ng-model="newitemkittemp.quantity" size="2"></td><td><button class="btn btn-danger btn-xs" type="button" ng-click="removeItemKitTemp(newitemkittemp.id)"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
                                </tr>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cost_price" class="control-label">{{trans('itemkit.cost_price')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">Q</div>
                                        <input type="text" class="form-control" name="cost_price" id="cost_price" ng-model="sumCost(itemkittemp)" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="selling_price" class="control-label">{{trans('itemkit.selling_price')}}</label>
                                    <div class="input-group">

                                        <input type="text" class="form-control" name="selling_price_ori" id="selling_price_ori" ng-model="sumSell(itemkittemp)" readonly/>
                                        <div class="input-group-addon">Q</div>
                                        <input type="text" class="form-control" name="selling_price" id="selling_price" ng-model="sp"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="supplier_id" class="control-label">{{trans('itemkit.profit')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">Q</div>
                                        <input type="text" class="form-control"  value="@{{sp - sumCost(itemkittemp)}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                {{-- <button type="submit" class="btn btn-primary btn-block">{{trans('itemkit.submit')}}</button> --}}
                                @include('partials.buttons',[ 'cancel_url'=>"/item-kits"])
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')

<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script type="text/javascript">
    // $('#item_kit_form').bootstrapValidator({
        // feedbackIcons: {
        //     valid: 'glyphicon glyphicon-ok',
        //     invalid: 'glyphicon glyphicon-remove',
        //     validating: 'glyphicon glyphicon-refresh'
        // },
        // fields: {
        //     item_kit_name: {
        //         validators: {
        //             notEmpty: {
        //                 message: 'Debe ingresar nombre del kit'
        //             }
        //         }
        //     },
            // selling_price: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Debe ingresar un precio de venta.'
            //         },
            //         regexp:{
            //             regexp: /^\d*\.?\d*$/,
            //             message: 'Ingrese un número válido'
            //         }
            //     }
            // }
    //     }
    // });
</script>
@stop
