<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - {{Session::get('empresa')}}</title>
    <meta content='width=device-width' name='viewport'>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <input type="hidden" value="{{Session::get('navbar_color', 'default')}}">
        {{--    DECLARANDO VARIABLES CSS GLOBALES PARA CONFIGURAR COLOR DE DASHBOARD--}}
        <style>
            :root {
                @if(Session::get('navbar_color', 'default')!='default')
                --navbar_color: {{Session::get('navbar_color', 'default')}};
                --leftmenu_color: {{Session::get('leftmenu_color', 'default')}};
                --select_color: {{Session::get('select_color', 'default')}};
                @else
                --navbar_color: #515763;
                --leftmenu_color: #515763;
                --select_color: #414151;
                @endif
            }
        </style>
        <!-- global css -->
        <link href="{{ asset('/css/app1.css') }}" rel="stylesheet" type="text/css" />
        <!-- POS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css') }}" />
        <!-- Toast -->
        <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- end of global css -->
        <!--page level css -->
        <!--end of page level css-->
        @yield('header_styles')
        <link rel="icon" href="{{ asset('favico.ico') }}" type="image/gif" sizes="16x16">
        {{-- loading modal --}}
        <link rel="stylesheet" href="{{asset('assets/css/loading-modal/jquery.loadingModal.css')}}">
    </head>

    <body class="skin-josh">
        <header class="header">
            <a href="/" class="logo">
                <img src="{{ asset('images/system/logo.png') }}" alt="logo" height="55">
            </a>
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <div>
                    <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button" id="col_lb">
                        <div class="responsive_nav"></div>
                    </a>
                </div>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="livicon" data-name="message-flag" data-loop="true" data-color="#42aaca" data-hovercolor="#42aaca" data-size="28"></i>
                                <span class="label label-success">4</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages pull-right">
                                <li class="dropdown-title">4 New Messages</li>
                                <li class="unread message">
                                    <a href="javascript:;" class="message"> <i class="pull-right" data-toggle="tooltip" data-placement="top" title="Mark as Read"><span class="pull-right ol livicon" data-n="adjust" data-s="10" data-c="#287b0b"></span></i>
                                        <img src="{{ asset('img/authors/avatar.jpg') }}" class="img-responsive message-image" alt="icon" />
                                        <div class="message-body"> <strong>Riot Zeast</strong>
                                            <br>Hello, You there?
                                            <br> <small>8 minutes ago</small> </div>
                                        </a>
                                    </li>
                                    <li class="unread message">
                                        <a href="javascript:;" class="message"> <i class="pull-right" data-toggle="tooltip" data-placement="top" title="Mark as Read"><span class="pull-right ol livicon" data-n="adjust" data-s="10" data-c="#287b0b"></span></i>
                                            <img src="{{ asset('img/authors/avatar1.jpg') }}" class="img-responsive message-image" alt="icon" />
                                            <div class="message-body"> <strong>John Kerry</strong>
                                                <br>Can we Meet ?
                                                <br> <small>45 minutes ago</small> </div>
                                            </a>
                                        </li>
                                        <li class="unread message">
                                            <a href="javascript:;" class="message"> <i class="pull-right" data-toggle="tooltip" data-placement="top" title="Mark as Read">                                         <span class="pull-right ol livicon" data-n="adjust" data-s="10" data-c="#287b0b"></span>                                     </i>
                                                <img src="{{ asset('img/authors/avatar5.jpg') }}" class="img-responsive message-image" alt="icon" />
                                                <div class="message-body"> <strong>Jenny Kerry</strong>
                                                    <br>Dont forgot to call...
                                                    <br> <small>An hour ago</small> </div>
                                                </a>
                                            </li>
                                            <li class="unread message">
                                                <a href="javascript:;" class="message"> <i class="pull-right" data-toggle="tooltip" data-placement="top" title="Mark as Read">                                         <span class="pull-right ol livicon" data-n="adjust" data-s="10" data-c="#287b0b"></span>                                     </i>
                                                    <img src="{{ asset('img/authors/avatar4.jpg') }}" class="img-responsive message-image" alt="icon" />
                                                    <div class="message-body"> <strong>Ronny</strong>
                                                        <br>Hey! sup Dude?
                                                        <br> <small>3 Hours ago</small> </div>
                                                    </a>
                                                </li>
                                                <li class="footer">
                                                    <a href="#">View all</a>
                                                </li>
                                            </ul>
                                        </li> -->
                                        <?php
                                        $json_notifications = json_decode(json_encode(Session::get('notifications', 'default')));
                                        // dd($json_notifications);
                                        // $no_notifications = count($json_notifications)
                                        if (is_array($json_notifications))
                                        {
                                            $no_notifications = count($json_notifications);
                                        } else {
                                            $no_notifications=0;
                                        };
                                        ?>
                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="livicon" data-name="bell" data-loop="true" data-color="#e9573f" data-hovercolor="#e9573f" data-size="28"></i>
                                                @if($no_notifications > 0)
                                                <span class="label label-warning" id="notification_counter">{{$no_notifications}}</span>
                                                @endif
                                                {{--                                                <span class="label label-warning" style="display: none;" id="notification_counter">{{$no_notifications}}</span>--}}
                                                {{--                                                <span class="label label-warning" style="display: none;" id="notification_loader"> <img src="{{ asset('img/loading1.gif') }}" alt="logo" height="10"> </span>--}}
                                            </a>
                                            <ul class=" notifications dropdown-menu" style="width: 300px;">
                                                <li class="dropdown-title" id="notification_title">Tiene {{$no_notifications}} notificaciones</li>
                                                <li>
                                                    <ul class="menu" id="notification_list">
                                                        @foreach($json_notifications as $notifi)
                                                        <li>
                                                            <i class="livicon warning" data-n="timer" data-s="20" data-c="white" data-hc="white"></i>
                                                            <a href="{{url($notifi->url)}}">{{$notifi->message}}</a>
                                                            <small class="pull-right">
                                                                <span class="livicon paddingright_10" data-n="timer" data-s="10"></span>
                                                                @if (isset($notifi->proveedor))
                                                                Proveedor: {{$notifi->proveedor}}
                                                                @else
                                                                Cliente: {{$notifi->cliente}}
                                                                @endif

                                                            </small>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                                {{--                                                <li class="footer">--}}
                                                    {{--                                                    <a href="#">View all</a>--}}
                                                    {{--                                                </li>--}}
                                                </ul>
                                            </li>
                                            <li class="dropdown user user-menu">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                    <img src="{{ asset('images/users/'.Auth::user()->avatar) }}" width="35" class="img-circle img-responsive pull-left" height="35" alt="riot">
                                                    <div class="riot">
                                                        <div>
                                                            {{Auth::user()->name}}
                                                            <span>
                                                                <i class="caret"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <!-- User image -->
                                                    <li class="user-header bg-light-blue">
                                                        <img src="{{ asset('images/users/'.Auth::user()->avatar) }}" width="90" class="img-circle img-responsive" height="90" alt="User Image"
                                                        />
                                                        <p class="topprofiletext">{{Auth::user()->name}}</p>
                                                    </li>
                                                    <!-- Menu Body -->
                                                    <!--  <li>
                                                        <a href="view_user.html"> <i class="livicon" data-name="user" data-s="18"></i> My Profile </a>
                                                    </li> -->
                                                    <li role="presentation"></li>
                                                    <li>
                                                        <a href="{{ URL::to('employees/' . Auth::user()->id . '/edit-profile') }}"><i class="livicon" data-name="edit" data-s="18"></i>{{trans('menu.edit_profile')}}</a>
                                                        <a href="{{ URL::to('employees/'.Auth::user()->id) }}"><i class="livicon" data-name="gears" data-s="18"></i>{{trans('menu.profile')}}</a>
                                                    </li>
                                                    <!-- Menu Footer-->
                                                    <li class="user-footer">
                                                        <!-- <div class="pull-left">
                                                            <a href="lockscreen.html"> <i class="livicon" data-name="lock" data-s="18"></i> Lock </a>
                                                        </div> -->
                                                        <div class="pull-right">
                                                            <a href="{{ url('/auth/logout') }}"> <i class="livicon" data-name="sign-out" data-s="18"></i> {{trans('menu.logout')}} </a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </header>
                            <div class="wrapper row-offcanvas row-offcanvas-left">
                                <!-- Left side column. contains the logo and sidebar -->
                                <aside class="left-side sidebar-offcanvas">
                                    <section class="sidebar">
                                        <div class="page-sidebar  sidebar-nav">
                                            <div class="nav_icons">
                                                <ul class="sidebar_threeicons">
                                                    <li>
                                                        <a href="{{ URL::to('/sales/create') }}"> <i class="livicon" data-name="shopping-cart" title="Crear Venta" data-c="#418BCA" data-hc="#418BCA" data-size="25" data-loop="true" data-toggle="tooltip" data-original-title="Nueva venta"></i> </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::to('/receivings/create') }}"> <i class="livicon" data-c="#EF6F6C" title="Crear Compra" data-hc="#EF6F6C" data-name="truck" data-size="25" data-loop="true" data-toggle="tooltip" data-original-title="Nueva compra"></i> </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::to('/customers') }}"> <i class="livicon" data-name="user-add" title="Clientes" data-c="#F89A14" data-hc="#F89A14" data-size="25" data-loop="true" data-toggle="tooltip" data-original-title="Clientes"></i> </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::to('/suppliers') }}"> <i class="livicon" data-name="user-remove" title="Proveedores" data-size="25" data-c="#01bc8c" data-hc="#01bc8c" data-loop="true" data-toggle="tooltip" data-original-title="Proveedores"></i> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="clearfix"></div>
                                            <!-- BEGIN SIDEBAR MENU -->
                                            @include('layouts._left_menu')
                                            <!-- END SIDEBAR MENU -->
                                        </div>
                                    </section>
                                </aside>
                                <aside class="right-side">
                                    <div id="preloaders" class="preloader"></div>
                                    @include('layouts.breadcrumb')
                                    @yield('content')
                                    @include('partials.notification_modal')
                                    <input type="hidden" value="{{json_encode(Session::get('notifications', 'default'))}}" id="notif">
                                    <input type="hidden" id="first_page" value="{{Session::get('first_page')}}">
                                </aside>
                                <!-- right-side -->
                            </div>
                            <a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top" data-toggle="tooltip" data-placement="left">
                                <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
                            </a>
                            <script src="{{ asset('js/app_pos.js') }}" type="text/javascript"></script>
                            <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script>
                            <script type="text/javascript" src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
                            {{-- LOADING MODAL --}}
                            <script src="{{asset('assets/js/notifications.js')}}"></script>

                            {{--NOTIFICACIONES DESPUES DE LOGIN--}}
                            <script src="{{asset('assets/js/loading-modal/jquery.loadingModal.js')}}"></script>
                            {{--    CUSTOM CACAO.GT    --}}
                            <script src="{{asset('assets/js/cacao/general_functions.js')}}"></script>
                            <script type="text/javascript">
                                var APP_URL = {!! json_encode(url('/')) !!}

                                $(document).ready(function(){
                                    $(".navbar .menu").slimscroll({
                                        height: "200px",
                                        alwaysVisible: true,
                                        size: "3px"
                                    }).css("width", "100%");
                                    /* Inicializar Tooltip para todos los forms donde se necesite*/
                                    $(function () {
                                        $('[data-toggle="tooltip"]').tooltip()
                                    })

                                    /*
                                    * INICIALIZANDO FUNCION QUE VERIFICA NOTIFICACIONES
                                    * */
                                    verifyNotifications();
                                    /**
                                     * Hacer logout si una petición de ajax devuelve el status 401: Unauthorized
                                    */
                                    $(document).ajaxError(function(event, jqxhr, settings, exception) {
                                        if (exception == 'Unauthorized') {
                                            // Prompt user if they'd like to be redirected to the login page
                                            toastr.options = {
                                                "closeButton": true,
                                                "timeOut": "0",
                                                "extendedTimeOut": "0",
                                                "preventDuplicates": true,
                                            };
                                            toastr.options.onHidden = function () { location.reload(); };
                                            toastr.error('Su sesión ha expirado, debe volver a autenticase!<br>Haga click aqui.');
                                        }
                                    });

                                    // disable datatables error prompt
                                    $.fn.dataTable.ext.errMode = 'none';
                                });
                                $(window).load(function() {
                                    $("#preloaders").fadeOut(1000);
                                    //setInterval('getNotifications()',20000);
                                });
                                function goBack() {
                                    window.history.back();
                                }

                                function showLoading(message){
                                    $('body').loadingModal({
                                        text: message
                                    });
                                    $('body').loadingModal('show');
                                }
                                function hideLoading(){
                                    $('body').loadingModal('hide');
                                    $('body').loadingModal('destroy');
                                }

                                @if(Session::has('message'))
                                var type = "{{ Session::get('alert-class', 'info') }}";
                                switch(type){
                                    case 'info':
                                    case 'alert-info':
                                    toastr.info("{{ Session::get('message') }}");
                                    break;

                                    case 'warning':
                                    case 'alert-warning':
                                    toastr.warning("{{ Session::get('message') }}");
                                    break;

                                    case 'success':
                                    case 'alert-success':
                                    toastr.success("{{ Session::get('message') }}");
                                    break;

                                    case 'error':
                                    case 'alert-error':
                                    case 'alert-danger':
                                    toastr.error("{{ Session::get('message') }}");
                                    break;
                                }
                                @endif

                                function cleanNumber(val) {
                                    let original = val;
                                    if(typeof val == "string"){
                                        val = val.replace(" ", "");
                                        val = val.replace("Q", "");
                                        val = val.replace("%", "");
                                        val = val.replace(new RegExp(",", "g"), '');
                                    }
                                    if (Number.isNaN(Number.parseFloat(val))) {
                                        return 0;
                                    }
                                    let x = parseFloat(val).toFixed(2);
                                    return parseFloat(x);
                                };
                                /**/
                                function getFechaHoraActual () {
                                    var date = new Date();
                                    var aaaa = date.getFullYear();
                                    var gg = date.getDate();
                                    var mm = (date.getMonth() + 1);

                                    if (gg < 10)
                                    gg = "0" + gg;

                                    if (mm < 10)
                                    mm = "0" + mm;

                                    var cur_day = gg + "-" + mm + "-" + aaaa;

                                    var hours = date.getHours()
                                    var minutes = date.getMinutes()
                                    var seconds = date.getSeconds();

                                    if (hours < 10)
                                    hours = "0" + hours;

                                    if (minutes < 10)
                                    minutes = "0" + minutes;

                                    if (seconds < 10)
                                    seconds = "0" + seconds;

                                    return cur_day + " " + hours + ":" + minutes + ":" + seconds;
                                }
                                function formato_moneda(x) {
                                    return SoloQuetzales(formatter.format(x));
                                }
                                function SoloQuetzales(x) {
                                    return x.toString().replace('GT', "");
                                }
                                var formatter = new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'GTQ',
                                });
                            </script>
                            <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
                                <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> -->
                                <!-- end of global js -->
                                <!-- begining of page level js -->
                                <!-- end of page level js -->
                                @yield('footer_scripts')
                            </body>

                            </html>
