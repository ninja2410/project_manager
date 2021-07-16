@extends('layouts/default')

@section('title',trans('revenues.revenue_recepit'))
@section('page_parent',trans('accounts.accounts'))

@section('header_styles')
  <style>
    table td {
      border-top: none !important;
    }
    .logo_invoice{
      height: 70px;
    }
  </style>
@stop
@section('content')
  <section class="content">
    <div class="" id="dvContents">
      <div class="row">
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2" style="border:1px solid #e3e3e3;background-color: #f5f5f5;">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="text-center">
                <img class="logo_invoice" src="{{ asset('images/system/logo2.png') }}" alt="" style="height: 70px;"><br><br>
                {{-- <strong>{{!empty($parameters->name_company)?$parameters->name_company:trans('general.na') }}</strong><br> --}}
                {{ trans('general.address').' : '}} {{ !empty($parameters->address)?$parameters->address:trans('general.na') }}
                <br>
                {{ trans('general.phone_number').' : '}}{{ !empty($parameters->phone)?$parameters->phone:trans('general.na')}}
                <h2>{{$revenue->serie->document->name .' '.$revenue->serie->name.'-' .$revenue->receipt_number}}</h2>
              </div>
            </div>
            <br>
            <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left">
              <strong>{{trans('customer.customer')}}:&nbsp;</strong> {{ !empty($revenue->customer_id) ? $revenue->customer->name : 'N/A'}}<br>
              <strong>{{trans('customer.nit')}}:&nbsp;</strong> {{ !empty($revenue->customer_id) ? $revenue->customer->nit_customer : 'N/A'}}<br>
              <strong>{{trans('customer.address')}}:&nbsp;</strong> {{ !empty($revenue->customer_id) ? $revenue->customer->address : 'N/A'}}<br>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left">
              <strong>{{trans('customer.phone_number')}}:&nbsp;</strong> {{ !empty($revenue->customer_id) ? $revenue->customer->phone_number : 'N/A'}}<br>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="text-center">
                <h4><b>{{trans('revenues.payment_method_info')}} </b></h4>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6" style="text-align:left">
              <strong>{{trans('revenues.payment_method')}}:&nbsp;</strong> {{ $revenue->pago->name}}<br/>
              <strong>{{trans('revenues.receiving_account')}}:&nbsp;</strong> {{ $revenue->account->account_name.' - '.$revenue->account->account_number}}<br/>
              <strong>{{trans('revenues.amount')}}: &nbsp;</strong>
              @money($revenue->amount)<br/>
              @if($revenue->pago->id==2 || $revenue->pago->id==5)
                <strong>{{trans('revenues.bank_name')}}:&nbsp;</strong>   {{ $revenue->bank_name}}<br/>@endif
              @if($revenue->pago->id==4) <strong>{{trans('revenues.card_name')}}:&nbsp;</strong>   {{ $revenue->card_name}}<br/> @endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6" style="text-align:left">
              <strong>{{trans('revenues.paid_at')}}:&nbsp;</strong> {{ $revenue->paid_at}}<br/>
              <strong>{{trans('revenues.ref')}}:&nbsp;</strong> {{ $revenue->reference}}<br/>
              @if($revenue->pago->id==2 || $revenue->pago->id==5)
                <strong>{{trans('revenues.same_bank')}}:&nbsp;</strong>   {{ $revenue->same_bank==1?'Si':'No' }}
                <br/>@endif
              @if($revenue->pago->id==4) <strong>{{trans('revenues.card_number')}}:&nbsp;</strong>   {{ $revenue->card_number}}<br/> @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="text-center">
                <h4><b>{{trans('revenues.payment_detail')}}</b></h4>
              </div>
            </div>
          </div>
          <div class="row">
            <br>
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                  <tr>
                    <th style="text-align:center; width: 20%;"><strong>{{trans('revenues.paid_at')}}</strong></th>
                    <th style="text-align:right; width: 20%;"><strong>{{trans('revenues.amount')}}</strong></th>
                    <th style="text-align:left; width: 60%;"><strong>{{trans('revenues.description')}}</strong></th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="text-align:center">{{ $revenue->paid_at}}</td>
                      <td style="text-align:right">@money($revenue->amount)
                      </td>
                      <td style="text-align:left">{{$revenue->description}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>{{-- table-responsive --}}
              <div class="row">
                <label for="">{{trans('revenues.total_letters')}}:</label> {{$precio_letras}}
              </div>
            </div> {{-- col-md-12 --}}
          </div>{{-- row --}}
        </div>
      </div>
      {{-- <div class="row">
          <div class="col-md-12">
          </div>
      </div> --}}
      <hr class="hidden-print"/>

    </div>
    <div class="row">
      <div class="col-md-4">&nbsp;</div>

      <div class="col-md-2">
        <button type="button" onclick="printReceipt();"
                class="btn btn-info pull-right hidden-print">{{trans('Imprimir comprobante')}}</button>
      </div>
{{--      <div class="col-md-2">--}}
{{--        <a  href="{{ url("banks/accounts/statement/".$revenue->account_id) }}" type="button"--}}
{{--            --}}{{-- <a  href="{{ URL::previous() }}" type="button"                 --}}
{{--            class="btn btn-default hidden-print">{{trans('Ir a estado de cuenta')}}</a>--}}
{{--      </div>--}}
      <div class="col-md-2">
        <a  href="{{ $bk_route }}" type="button"
            {{-- <a  href="{{ URL::previous() }}" type="button"                 --}}
            class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
      </div>
    </div>
    <br>
  </section>
@endsection
@section('footer_scripts')
  <script>
      function printReceipt() {
          var name_document = $('#name_document').text();
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

          frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_receipt.css')}}" rel="stylesheet" type="text/css" />');
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

