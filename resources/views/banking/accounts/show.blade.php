@extends('layouts/default') 
@section('title',trans('accounts.show_account')) 
@section('page_parent',trans('accounts.banks'))

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="edit" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('accounts.show_account')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          @if (count($errors) > 0)
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <div class="col-md-2">
            <div class="panel-body">
              <a href="{{ URL::previous() }}" class="btn btn-danger btn-large button-block">
                <span class="livicon" data-name="undo" data-size="14" data-loop="true" data-c="#fff" data-hc="white">&nbsp;&nbsp;</span>
                Regresar
              </a>
            </div>
            
            
          </div>
          <div class="col-md-10">
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped" >
                  <tr>
                    <td>Fecha de creación</td>
                    <td>
                      <p class="user_name_max">{{ $account->created_at }}</p>
                    </td>
                    
                  </tr>
                  <tr>
                    <td>Nombre</td>
                    <td>
                      <p class="user_name_max">{{$account->account_name}}</p>
                    </td>
                    
                  </tr>
                  <tr>
                    <td>{{trans('accounts.account_number')}}</td>
                    <td>
                      {{ $account->account_number }}
                    </td>
                  </tr>
                  
                  <tr>
                    <td>{{trans('accounts.bank_name')}}</td>
                    <td>
                      {{ $account->bank_name }}
                    </td>
                  </tr>
                  <tr>
                    <td>{{trans('accounts.account_type')}}</td>
                    <td>
                      {{ $account->type->name }}
                    </td>
                  </tr>
                  
                  <tr>
                    <td>{{trans('accounts.opening_balance')}}</td>
                    <td>
                      @money($account->opening_balance)
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {{trans('accounts.current_balance')}}
                    </td>
                    <td>
                      @money($account->pct_interes)
                    </td>
                  </tr>
                  <tr>
                    <td>{{trans('accounts.description')}}</td>
                    <td>
                      {{ $account->description }}
                    </td>
                  </tr>
                  
                  <tr>
                    <td>{{trans('accounts.account_responsible')}}</td>
                    <td>
                      {{ $account->responsible->name }}
                    </td>
                  </tr>
                  <tr>
                    <td>{{trans('accounts.status')}}</td>
                    <td>
                      @if ($account->status==1)
                      <span class="label label-success">{{ trans('Activo') }}</span> @else
                      <span class="label label-danger">{{ trans('Inactivo') }}</span> @endif
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection