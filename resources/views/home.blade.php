@extends('layouts/default')

@section('title',"Bienvenido")
@section('page_parent',"Home")

@section('header_styles')
<script type="text/css">

</script>
@endsection
@section('content')
@if (Auth::check())
<!-- Permisos -->
<?php $permisos=Session::get('permisions');
 $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?>
@else
<?php 
$array_p=array_column ();
?>
@endif
<section class="content">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="panel-heading">{{ trans('dashboard.welcome') }}</div>
        </div>
        <div class="col-lg-3"></div>
    </div>
    <div class="row">

        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig" data-toggle="tooltip" data-original-title="Nueva venta" @if (!in_array('sales?id=0',$array_p)) style="display: none" @endif>
            <a href="{{ URL::to('/sales') }}">
                <div class="lightbluebg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 text-right">
                                    <span>Ventas del Dia Q</span>
                                    <div class="number" id="total_sales">Q</div>
                                </div>
                                <i class="livicon  pull-right" data-name="shopping-cart" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">Mes Actual</small>
                                    <h4 id="sales_current">Q</h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">Mes Pasado</small>
                                    <h4 id="sales_last">Q</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInUpBig" data-toggle="tooltip" data-original-title="Nueva compra" @if (!in_array('receivings',$array_p)) style="display: none" @endif>
            <a href="{{ URL::to('/receivings') }}">
                <div class="redbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>Compras del Día Q</span>
                                    <div class="number" id="total_receivings"></div>
                                </div>
                                <i class="livicon pull-right" data-name="truck" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">Mes Actual</small>
                                    <h4 id="receivings_current"></h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">Mes Pasado</small>
                                    <h4 id="receivings_last"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInRightBig" data-toggle="tooltip" data-original-title="Creditos" @if (!in_array('credit',$array_p)) style="display: none" @endif>
            <a href="{{ URL::to('/credit') }}">
                <div class="palebluecolorbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>Créditos Q</span>
                                    <div class="number" id="credits_unpaid"></div>
                                </div>
                                <i class="livicon pull-right" data-name="money" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">Pagados</small>
                                    <h4 id="credits_paid"></h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">Emitidos</small>
                                    <h4 id="total_credits"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Segunda fila -->
        {{-- <hr> --}}

        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInRightBig" data-toggle="tooltip" data-original-title="Clientes" @if (!in_array('customers',$array_p)) style="display: none" @endif>
            <a href="{{ URL::to('/customers') }}">
                <div class="goldbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>Clientes Totales</span>
                                    <div class="number" id="customers"></div>
                                </div>
                                <i class="livicon pull-right" data-name="user-add" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">&nbsp;</small>
                                    <h4 id="myTargetElement3.1">&nbsp;</h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">&nbsp;</small>
                                    <h4 id="myTargetElement3.2">&nbsp;</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInRightBig" data-toggle="tooltip" data-original-title="Proveedores" @if (!in_array('suppliers',$array_p)) style="display: none" @endif>
            <a href="{{ URL::to('/suppliers') }}">
                <div class="palebluecolorbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>Proveedores Totales</span>
                                    <div class="number" id="suppliers"></div>
                                </div>
                                <i class="livicon pull-right" data-name="user-remove" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">&nbsp;</small>
                                    <h4 id="myTargetElement4.1">&nbsp;</h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">&nbsp;</small>
                                    <h4 id="myTargetElement4.2">&nbsp;</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInRightBig" data-toggle="tooltip" data-original-title="Articulos" @if (!in_array('ítems',$array_p)) style="display: none" @endif>
            <a href="{{ URL::to('/items') }}">
                <div class="goldbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 pull-left">
                                    <span>Total Productos</span>
                                    <div class="number" id="items"></div>
                                </div>
                                <i class="livicon pull-right" data-name="list-ul" data-l="true" data-c="#fff" data-hc="#fff" data-s="70"></i>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <small class="stat-label">&nbsp;</small>
                                    <h4 id="myTargetElement4.1">&nbsp;</h4>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <small class="stat-label">&nbsp;</small>
                                    <h4 id="myTargetElement4.2">&nbsp;</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <div class="row">


    </div>
</section>
@endsection


@section('footer_scripts')
<script type="text/javascript">
    $(document).ready(function(){    
    $("[data-toggle='tooltip']").tooltip();
    change_sales();
    change_bills();
    change_credits();
    change_other();
});

function change_sales() {

  var options = {
            useEasing: false,
            useGrouping: true,
            separator: ',',
            decimal: '.'
        }

    var newCust = <?php echo $sales==null?0:$sales ?>;
    var newCust_curr = <?php echo $sales_current==null?0:$sales_current ?>;
    var newCust_last = <?php echo $sales_last==null?0:$sales_last?>;
    var startNew =  0;

    var demo = new countUp1("total_sales", newCust/4 , newCust, 2, 3, options);
        demo.start();

    var demo = new countUp1("sales_current", startNew , newCust_curr, 2, 4, options);
            demo.start();

    var demo = new countUp1("sales_last", startNew , newCust_last, 2, 4 , options);
            demo.start();
}

function change_bills() {

  var options = {
            useEasing: false,
            useGrouping: true,
            separator: ',',
            decimal: '.'
        }

    var newCust = <?php echo $receivings==null?0:$receivings ?>;
    var newCust_curr = <?php echo $receivings_current==null?0:$receivings_current ?>;
    var newCust_last = <?php echo $receivings_last==null?0:$receivings_last?>;
    var startNew =  0;

    var demo = new countUp1("total_receivings", newCust/5 , newCust, 2, 3, options);
        demo.start();

    var demo = new countUp1("receivings_current", startNew , newCust_curr, 2, 4, options);
            demo.start();

    var demo = new countUp1("receivings_last", startNew , newCust_last, 2, 4 , options);
            demo.start();
}

function change_credits() {

  var options = {
            useEasing: false,
            useGrouping: true,
            separator: ',',
            decimal: '.'
        }

    var credits = <?php echo $credits==null?0:$credits ?>;
    var credits_paid = <?php echo $credits_paid==null?0:$credits_paid ?>;
    var credits_unpaid = <?php echo $credits_unpaid==null?0:$credits_unpaid?>;
    var startNew =  0;

    var demo = new countUp1("total_credits", credits/5 , credits, 2, 3, options);
        demo.start();

    var demo = new countUp1("credits_paid", credits_paid/5 , credits_paid, 2, 4, options);
            demo.start();

    var demo = new countUp1("credits_unpaid", credits_unpaid/5 , credits_unpaid, 2, 4 , options);
            demo.start();
}

function change_other() {

  var options = {
            useEasing: false,
            useGrouping: false,
            separator: ',',
            decimal: '.'
        }

    var customers = <?php echo $customers==null?0:$customers ?>;
    var suppliers = <?php echo $suppliers==null?0:$suppliers ?>;
    var items = <?php echo $items==null?0:$items?>;
    var startNew =  0;

    var demo = new countUp1("customers", customers/5 , customers, 0, 4, options);
        demo.start();

    var demo = new countUp1("suppliers", suppliers/5 , suppliers, 0, 4, options);
            demo.start();

    var demo = new countUp1("items", items/5 , items, 2, 4 , options);
            demo.start();
}


function countUp1(target, startVal, endVal, decimals, duration, options) {

    // make sure requestAnimationFrame and cancelAnimationFrame are defined
    // polyfill for browsers without native support
    // by Opera engineer Erik Möller
    var lastTime = 0;
    var vendors = ['webkit', 'moz', 'ms'];
    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
        window.cancelAnimationFrame =
                window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
    }
    if (!window.requestAnimationFrame) {
        window.requestAnimationFrame = function(callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                    timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        }
    }
    if (!window.cancelAnimationFrame) {
        window.cancelAnimationFrame = function(id) {
            clearTimeout(id);
        }
    }

    // default options
    this.options = options || {
        useEasing : true, // toggle easing
        useGrouping : true, // 1,000,000 vs 1000000
        separator : ',', // character to use as a separator
        decimal : '.' // character to use as a decimal
    }
    if (this.options.separator == '') this.options.useGrouping = false;

    var self = this;

    this.d = (typeof target === 'string') ? document.getElementById(target) : target;
    this.startVal = Number(startVal);
    this.endVal = Number(endVal);
    this.countDown = (this.startVal > this.endVal) ? true : false;
    this.startTime = null;
    this.timestamp = null;
    this.remaining = null;
    this.frameVal = this.startVal;
    this.rAF = null;
    this.decimals = Math.max(0, decimals || 0);
    this.dec = Math.pow(10, this.decimals);
    this.duration = duration * 1000 || 2000;

    this.version = function () { return '1.1.2' }

    // Robert Penner's easeOutExpo
    this.easeOutExpo = function(t, b, c, d) {
        return c * (-Math.pow(2, -10 * t / d) + 1) * 1024 / 1023 + b;
    }
    this.count = function(timestamp) {

        if (self.startTime === null) self.startTime = timestamp;

        self.timestamp = timestamp;

        var progress = timestamp - self.startTime;
        self.remaining = self.duration - progress;

        // to ease or not to ease
        if (self.options.useEasing) {
            if (self.countDown) {
                var i = self.easeOutExpo(progress, 0, self.startVal - self.endVal, self.duration);
                self.frameVal = self.startVal - i;
            } else {
                self.frameVal = self.easeOutExpo(progress, self.startVal, self.endVal - self.startVal, self.duration);
            }
        } else {
            if (self.countDown) {
                var i = (self.startVal - self.endVal) * (progress / self.duration);
                self.frameVal = self.startVal - i;
            } else {
                self.frameVal = self.startVal + (self.endVal - self.startVal) * (progress / self.duration);
            }
        }

        // decimal
        self.frameVal = Math.round(self.frameVal*self.dec)/self.dec;

        // don't go past endVal since progress can exceed duration in the last frame
        if (self.countDown) {
            self.frameVal = (self.frameVal < self.endVal) ? self.endVal : self.frameVal;
        } else {
            self.frameVal = (self.frameVal > self.endVal) ? self.endVal : self.frameVal;
        }

        // format and print value
        self.d.innerHTML = self.formatNumber(self.frameVal.toFixed(self.decimals));

        // whether to continue
        if (progress < self.duration) {
            self.rAF = requestAnimationFrame(self.count);
        } else {
            if (self.callback != null) self.callback();
        }
    }
    this.start = function(callback) {
        self.callback = callback;
        // make sure values are valid
        if (!isNaN(self.endVal) && !isNaN(self.startVal)) {
            self.rAF = requestAnimationFrame(self.count);
        } else {
            console.log('countUp error: startVal or endVal is not a number');
            self.d.innerHTML = '--';
        }
        return false;
    }
    this.stop = function() {
        cancelAnimationFrame(self.rAF);
    }
    this.reset = function() {
        self.startTime = null;
        self.startVal = startVal;
        cancelAnimationFrame(self.rAF);
        self.d.innerHTML = self.formatNumber(self.startVal.toFixed(self.decimals));
    }
    this.resume = function() {
        self.startTime = null;
        self.duration = self.remaining;
        self.startVal = self.frameVal;
        requestAnimationFrame(self.count);
    }
    this.formatNumber = function(nStr) {
        nStr += '';
        var x, x1, x2, rgx;
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? self.options.decimal + x[1] : '';
        rgx = /(\d+)(\d{3})/;
        if (self.options.useGrouping) {
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + self.options.separator + '$2');
            }
        }
        return x1 + x2;
    }

    // format startVal on initialization
    self.d.innerHTML = self.formatNumber(self.startVal.toFixed(self.decimals));
}

/*jramirez funcion para formatear numeros*/
function formatoMoneda(n, currency) {
  return currency + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
}



</script>
@stop