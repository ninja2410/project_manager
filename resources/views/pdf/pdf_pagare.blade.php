<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pagare- {{$dataCredits[0]->name}}</title>
  {!! Html::style('assets/css/pdf.css') !!}
</head>
<body>
  <main id="html">
    <!-- <h1  class="clearfix" style="width: 90%;"><small><span>Fecha</span><br />{{date('d/m/Y',strtotime($date))}}</h1> -->
      <hr style="width: 80%;">
      <table>
        <thead>
          <tr>
            <th>
              <div class="" style="text-align:center;">
                <label style="font-size: 30px;font-weight: bold;">
                 Pagare
                </label>
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
           <td>
             <div style="text-align:justify;">
               Yo: <b>{{$dataCredits[0]->name}}</b> de <b>{{$edad}}</b> años de edad, estado civil <b>{{trans('customer.'.$customer->marital_status)}}</b>; identificándome  con DPI número: <b>{{$customer->dpi}}</b>,
               Guatemalteco/a, señalo lugar para recibir notificaciones: <b>{{$dataCredits[0]->address}}</b>, Teléfonos: <b>{{$customer->phone_number}}</b>. Actúo en nombre propio, mediante este documento,
               me reconozco listo/a y lleno/a deudor de <b>{{$company->name_company}}</b>, por la suma de Q <b>{{number_format($dataCredits[0]->amount,2)}}</b> en letras: <b>{{$total_letras}}</b>.
               Que en calidad de préstamo me fue entregado en forma satisfactoria, el día de hoy: <b>{{$dayname}}</b>, Fecha: <b>{{date('d/m/Y',strtotime($date))}}</b>, me comprometo a cancelar bajo las siguientes disposiciones:
             </div>
             <br>
             <br>
             <div class="incisos" style="text-align:justify;">
               <b>a)</b> El capital e intereses adeudados lo pagaré mediante: <b>{{$dataCredits[0]->cuotas}}</b> cuotas, las cuales se me explicaron y entregaron el detalle de los pagos a realizar.
               <br>
               <b>b)</b> Estas cuotas deberán pagarse a partir de la fecha: <b>{{$dataCredits[0]->date_firstpayment}}</b>
               <br>
               <b>c)</b> Que tanto el capital adeudado, como los intereses serán pagados en Quetzales, sin necesidad de cobro o requerimiento alguno.
               <br>
               <b>d)</b> Que la falta de pago puntual de un solo vencimiento de intereses, dará derecho al acreedor a exigir ejecutivamente el valor íntegro de la obligación principal, más los interesados adecuados hasta la total cancelación de la obligación.
               <br>
               @if ($credit->description!="Fiduciario")
                 <b>e)</b> Descripción de la/s garantía/s:
                 @foreach ($warranties as $key => $value)
                   <br>
                   <b>- {{$value->name}} </b>, con valor estimado de: <b> Q{{number_format($value->price,2)}}</b>
                 @endforeach
                 <p>La/s cual/es después de vencido el plazo estipulado esta garantía pasará a favor de <b>{{$company->name_company}}</b>, en caso de cobro fallido después del segundo día del cobro fallido.</p>
               @else
                 <b>e)</b> Asimismo por este medio garantizo el cumplimiento del Deudor, con las mismas condiciones anteriores al Fiador: <b>{{$dataCredits[0]->fiador_name}}</b>, con direccion: <b>{{$dataCredits[0]->fiador_address}}</b>, quien se identifica con CUI: <b>{{$dataCredits[0]->fiador_dpi}}</b>, teléfono: <b>{{$dataCredits[0]->fiador_phone}}</b>, quien por este documento acepta las mimas condiciones y consecuencias del Deudor.
               @endif
               <br>
               <b>f)</b> Todos los gastos, inherentes a esta negociación, así como los de su cancelación, correrán a cargo del Deudor, en caso de ejecutarse la obligación.
               <br>
               <b>g)</b> Que el Deudor renuncia al fuero de su domicilio y se somete a los tribunales que elija el Acreedor, y señala para recibir notificaciones la dirección <b>{{$dataCredits[0]->fiador_address}}</b>.
               <br>
               <b>h)</b> Obligándome a comunicar a el Acreedor de cualquier cambio que sufra la misma y acepta desde ya como válidas y bien hechas las notificaciones judiciales o extrajudiciales que se le hagan en la dirección aquí señalada, si no cumple con dar el citado aviso de cambio de dirección.
             </div>

           </td>
         </tr>
       </tbody>
     </table>
     <br><br><br><br><br>
     <div id="details" class="clearfix" style="width: 90%; text-align:center;">
      <div id="project">
        Firma:_____________________________________
        <br>
      </div>
      <div id="company">
        <b>{{$dataCredits[0]->name}}</b>
        <br>
        DPI:{{$customer->dpi}}
      </div>
    </div>
    <br>
    <div id="notices">
      <div>CACAOGT:</div>
      <div class="notice">Tu empresa de desarrollo No. 1</div>
    </div>
  </main>
  <footer>
    Para mas información visite www.cacao.gt
  </footer>
</body>
</html>
