
@if($tipo==="lista")
    <thead>
        <tr>
            <th></th>
            <th data-priority="2" style="width: 14%;">{{trans('sale.number_doc')}}</th>
            <th data-priority="3" style="width: 20%;">{{trans('sale.customer')}}</th>
            <th data-priority="3">{{trans('customer.phone_number')}}</th>
            <th data-priority="4">{{trans('sale.date')}}</th>
            <th data-priority="4">{{trans('sale.payment_type')}}</th>
            <th data-priority="4">{{trans('sale.status')}}</th>
            <th data-priority="5">{{trans('sale.amount')}}</th>
            <th data-priority="6">{{trans('sale.balance')}}</th>
            <th style="width: 8%;">{{trans('sale.actions')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $i=> $value)
        <tr>
            <td></td>
            <td>
              @if ($value->status=="FACT")
                <a  href="{{route('completesale',$value->id.'?return=true')}}" data-toggle="tooltip" data-original-title="Ir a documento">{{$value->document_and_correlative}}</a>
              @endif
              @if ($value->status =="NCRE")
                  <a  href="{{url('credit_note',$value->id)}}" data-toggle="tooltip" data-original-title="Ir a documento">{{$value->document_and_correlative}}</a>
              @endif
            </td>
            <td><a href="{{URL::to('customers/profile/'.$value->customer_id)}}" data-toggle="tooltip" data-original-title="Ir a cliente">{{ strtoupper($value->customer_name) }}</a></td>
            <td>{{$value->customer->phone_number}}</td>
            <td>{{ $value->sale_date }}</td>
            <td>
              @if ($value->status!="NCRE")
                @if($value->pago_type==6)
                  <a href="{{ URL::to('credit/statemente/invoice/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Ir a estado de pagos">{{$value->pago}}</a>
                @else
                  <a @if(in_array('banks/revenues',$array_p)) href="{{ URL::to('banks/revenues/' . $value->revenues->id ) }}" @endif data-toggle="tooltip" data-original-title="Ir a transacciȯn">{{$value->pago}}</a>
                @endif
              @endif
            </td>
            <td>
              @if ($value->status=="FACT")
                @if( $value->cancel_bill==1)
                  <span class="label label-sm label-danger">{!! 'Anulada' !!}</span>
                @else
                  <span class="label label-sm label-success">{!! 'Activa' !!}</span>
                @endif
              @endif

              @if ($value->status=="NCRE")
                @if ($value->pago!="Anulado")
                    <span class="label label-sm label-success">{{$value->pago}}</span>
                  @else
                    <span class="label label-sm label-danger">{{$value->pago}}</span>
                @endif

              @endif

            </td>
            <td style="text-align:right">@money($value->total_cost)</td>
            <td style="text-align:right;">
                @if( $value->cancel_bill==1) @money(0) @else
                @money($value->total_cost-$value->total_paid) @endif
            </td>
            <td>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        @if(($value->cancel_bill==0) && (in_array('cancel_bill',$array_p)))
                        @if ($value->sale_date == date('d/m/Y'))
                        <li>
                            <a  data-toggle="modal"  data-href="#ajax-modal" href="#ajax-modal"  onclick="clickBtn(this);" id="{{$value->id}}">{{trans('sale.void').' '.$value->document_and_correlative}}</a>
                        </li>
                        @else
                        <a href="{{url('credit_note/create/'.$value->id)}}">{{trans('credit_notes.create')}}</a>
                        @endif
                        @endif
                        <li>
                            <a href="{{route('completesale',$value->id.'?return=true')}}">{{trans('sale.invoice_detail').' '.$value->document_and_correlative}}</a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>

            <th ></th>
            <th ></th>
            <th ></th>
            <th ></th>
            <th ></th>
            <th colspan="2" style="text-align:right">Total:</th>
            <th colspan="1" style="text-align:right"></th>
            <th style="text-align: right;color:red;"></th>
            <th ></th>
        </tr>
    </tfoot>
@endif

@if($tipo==="forma_pago")
<thead>
    <tr>
      <th style="width: 5%;text-align:center;">No.</th>
      <th style="text-align:left;width: 22%">{{trans('sale.number_doc')}}</th>
      <th style="width: 22%;">{{trans('sale.customer')}}</th>
      <th style="text-align:center;">{{trans('sale.date')}}</th>
      <th style="width:15%;center;">{{trans('sale.payment_type')}}</th>
      <th style="width: 15%;text-align:center;">Monto</th>
      <th style="width: 15%;text-align:center;">Totales</th>
    </tr>
  </thead>
  <tbody>
    @foreach($sales as $i=>$valor)
      @if($valor->id<>0)
        <tr>
            <td>{{$i+1}}</td>
            <td><a  href="{{route('completesale',$valor->id.'?return=true')}}" data-toggle="tooltip" data-original-title="Ir a documento">{{$valor->document}} {{$valor->serie}}-{{$valor->correlative}}</a></td>
            <td><a href="{{URL::to('customers/profile/'.$valor->customer_id)}}" data-toggle="tooltip" data-original-title="Ir a cliente">{{ strtoupper($valor->customer_name) }}</a></td>
            <td style="text-align:center;font-size:12px">{{$valor->sale_date}}</td>
            <td style="text-align:left;font-size:12px">{{$valor->name}}</td>
            <td style="text-align:right">@money($valor->total_cost)</td>
            <td style="text-align:right"></td>
        </tr>
      @else
        <tr style="background-color: #e0e0e0;">
            <td></td>
            <td></td>
            <td><strong># Ventas {{ $valor->name.' : '. $valor->correlative }}</strong></td>
            <td></td>
            <td><strong></strong></td>
            <td style="text-align:right"></td>
            <td style="text-align:right"><strong>@money($valor->total_cost)</strong></td>
        </tr>
      @endif

    @endforeach
  </tbody>
  <tfoot>
    <tr style="background-color: #000000;color:#fff;">
      <th colspan="5" style="text-align:right"></th>
      <th style="text-align:right;font-size:16px;"><strong>TOTAL GENERAL:</strong></th>
      <th style="text-align:right;font-size:16px;"></th>
    </tr>
  </tfoot>
@endif
