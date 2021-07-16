@extends('layouts/default')

@section('title',trans('receiving.print'))
@section('page_parent',trans('receiving.receivings'))
@section('header_styles')
  <style>
    table,thead,th,td {
      border-collapse: collapse;
      border: 1px solid black;
    }

    /* table, td, th,thead,tbody {
      border: 1px solid black;
    }   */
  </style>
@stop
@section('content')
<section class="content">
  <div id="complete_receiving">
    <div class="row">
      <div class="col-md-12" style="text-align:center">
        {{trans('dashboard.empresa')}}
      </div>
    </div>
    <div class="row">
      <div class="col-md-12" style="text-align:center">
        <label id="nombreDocumento">{{$dataDocuments[0]->doc.' '.$dataDocuments[0]->name.' - '.$receivings->correlative}}</label>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-12">
        <b>{{trans('receiving.supplier')}}:</b> {{ $receivings->supplier->company_name}}<br />
        <b>{{trans('receiving.employee')}}:</b> {{$receivings->user->name}}<br />
        <b>{{trans('receiving.reference')}}:</b> {{$receivings->reference}}<br />
        <b>{{trans('receiving.storage')}}:</b> {{$receivings->storageOrigin->name}}<br />
        <b>{{trans('receiving.date')}}</b>{{$receivings->date}}<br />
        <b>{{trans('receiving.payment_type')}}: </b>{{ $receivings->pago->name  }}
        @if ($receivings->deposit>0)
        <br /><b>{{trans('receiving.deposit')}}: </b>{{$receivings->deposit}}
        @endif
      </div>
    </div>
    <hr>
    <br><br>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr style="text-align:center">
                <th style="width:5%">No.</th>
                <th><b>{{trans('item.upc_ean_isbn')}}</b></th>
                <th><b>{{trans('receiving.item')}}</b></th>
                <th style="width: 20%; text-align: right;"><b>{{trans('receiving.cost_unit')}}</b></th>
                <th style="text-align: center;"><b>{{trans('receiving.qty')}}</b></th>
                <th style="width: 20%; text-align: right;"><b>{{trans('receiving.total')}}</b></th>
              </tr>
            </thead>
            @foreach($receivingItems as $i=> $value)
            <tr>
              <td>{{$i+1}}</td>
              <td>{{$value->item->upc_ean_isbn}}</td>
              <td>{{$value->item->item_name}}</td>
              <td style="text-align:right">
                @money($value->cost_price)
              </td>
              <td style="text-align:center">{{$value->quantity}}</td>
              <td style="text-align:right">
                @money($value->total_cost)
              </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5" style="text-align:right"><strong>{{trans('receiving.grand_total')}}</strong></td>
                <td style="text-align:right">@money($receivings->total_cost)</td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if (isset($outlays[0]->description))
  <div class="row">
    <div class="col-md-12">
      <h4>Detalle de gastos</h4>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <th><b>Descripción</b></th>
            <th><b>Total</b></th>
          </thead>
          <tbody>
            <?php $totalOutlay=0; ?>
            @foreach($outlays as $value)
            <tr>
              <td style="text-align:left">
                <?php echo ($value->description);?>
              </td>
              <td style="text-align:left">Q
                <?php echo number_format($value->amount,2);?>
              </td>
            </tr>
            <?php $totalOutlay+=$value->amount; ?>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td> <b> Total Gastos sobre compra:</b></td>
              <td>Q {{number_format($totalOutlay, 2)}}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  @endif
</div>
<div class="row">
  <hr class="hidden-print" />
  <div class="row">
    <div class="col-md-4">
      &nbsp;
    </div>
    <div class="col-md-2">
      <button type="button" onclick="$('#complete_receiving').print();" class="btn btn-danger pull-right hidden-print">{{trans('receiving.print')}}</button>
      {{-- <button type="button"  id="btnPrint_sale" class="btn btn-primary pull-right hidden-print">{{trans('receiving.print')}}</button> --}}

    </div>
    <div class="col-md-2">
      @if(isset($_GET['details']))
      <button class="btn btn-info" onclick="window.history.back()">Regresar</button>
      @else
      <a href="{{ url('/receivings/create') }}" type="button" class="btn btn-info pull-right hidden-print">{{trans('receiving.new_receiving')}}</a>
      @endif
    </div>
    <div class="col-md-4">
      &nbsp;
    </div>
  </div>
</br></br>
</div>
</section>
@endsection
@section('footer_scripts')
<script>
  function printInvoice() {
    window.print();
  }
  // jramirez
  $.fn.extend({
    print: function() {
      var frameName = 'CagaoGT_Print';
      var doc = window.frames[frameName];
      if (!doc) {
        $('<iframe>').hide().attr('name', frameName).appendTo(document.body);
          doc = window.frames[frameName];
        }
        doc.document.body.innerHTML = this.html();
        doc.window.print();
        return this;
      }
    });
    //jramirez new
    //
    $("#btnPrint_sale").click(function() {
      var nombreDocumento = $('#nombreDocumento').text();
      // console.log(nombreDocumento);
      var contents = $("#complete_receiving").html();
      var frame1 = $('<iframe />');
      frame1[0].name = "frame1";
      frame1.css({
        "position": "absolute",
        "top": "-1000000px"
      });
      $("body").append(frame1);
      var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
      frameDoc.document.open();
      //Create a new HTML document.
      frameDoc.document.write('<html><head><title>' + nombreDocumento + '</title>');
        frameDoc.document.write('</head><body>');
          //Append the external CSS file.

          frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_page2.css')}}" rel="stylesheet" type="text/css" />');
          //Append the DIV contents.
          frameDoc.document.write(contents);
          frameDoc.document.write('</body></html>');
          frameDoc.document.close();
          setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
          }, 500);

        });
      </script>
      @endsection
