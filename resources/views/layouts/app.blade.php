<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GlobalSat Protocolos</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- JavaScripts -->
        <script src="{{ asset('js/vendor.js') }}"></script>
        <script src="{{ asset('js/bootstrap-fileinput/pt-br.js') }}"></script>
        <script src="{{ asset('js/bootstrap-fileinput/es.js') }}"></script>
        <script src="{{ asset('js/bootstrap-fileinput/piexif.js') }}"></script>

        <style type="text/css">
            body {
                font-family: 'Lato';
            }

            .fa-btn {
                margin-right: 6px;
            }

            .protocol-data .col-md-2, .protocol-data .col-md-4, .protocol-data .col-md-10 {
                padding-top: 5px;
                padding-bottom: 5px;
            }

            .header-no-margin, .header-no-margin h1 {
                margin-top: 0 !important;
            }

            .btn-header-margin {
                margin-top: 5px;
            }

            .ui-datepicker table {
                font-size: .8em !important;
            }
        </style>
    </head>
    <body id="app-layout">
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#spark-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ url('/protocolo/?status=pendentes') }}">
                        PROTOCOLOS
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    @if (!Auth::guest())
                        <ul class="nav navbar-nav navbar-left">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ trans('menu.cadastro') }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    @if (Auth::user()->isSuperAdmin())
                                        <li><a href="{{ url('/setor') }}"><i class="fa fa-btn fa-sign-out"></i>{{ trans('menu.setor') }}</a></li>
                                        <li><a href="{{ url('/setorusuario') }}"><i class="fa fa-btn fa-sign-out"></i>{{ trans('menu.setorusuario') }}</a></li>
                                    @endif
                                    <li><a href="{{ url('/protocolo') }}"><i class="fa fa-btn fa-sign-out"></i>{{ trans('menu.protocolo') }}</a></li>
                                </ul>
                            </li>
                            @if (Auth::user()->isSuperAdmin())
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ trans('menu.relatorio') }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('/relatorio/') }}"><i class="fa fa-btn fa-sign-out"></i>{{trans('menu.listadeprotocolos')}}</a></li>
                                </ul>
                            </li>
                            @endif
                        </ul>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->username }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('/home/es') }}"><i class="fa fa-btn fa-sign-out"></i>{{trans('menu.espanhol')}}</a></li>
                                    <li><a href="{{ url('/home/pt_BR') }}"><i class="fa fa-btn fa-sign-out"></i>{{trans('menu.portugues')}}</a></li>
                                    <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>{{trans('menu.sair')}}</a></li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            @yield('content')
        </div>
        <footer class="container">
            <hr />
            <p>
                GlobalSat PROTOCOLOS &nbsp;&nbsp;<strong>v0.1</strong>
            </p>
        </footer>
        <script type="text/javascript">
            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '<Ant',
                nextText: 'Sig>',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };

            $(function() {
                $("#datepicker").datepicker( $.datepicker.regional["{{ trans('protocolo-lista.lingua') }}"]);
                $("#datepicker2").datepicker( $.datepicker.regional["{{ trans('protocolo-lista.lingua') }}"]);
            });

            $("#arquivo").fileinput({
                dropZoneEnabled: false,
                maxFileSize: 5000,
                language: '{{ trans("protocolo-lista.lingua") }}',
                autoOrientImage: true
            });


            $('#abas a').on('click', changeClass);

            function changeClass() {
                $('#abas a').removeClass('active');
                $(this).addClass('active');
            }

            function blink(selector){
                $(selector).fadeOut('slow', function(){
                    $(this).fadeIn('slow', function(){
                        blink(this);
                    });
                });
            }

            blink('.blink');
        </script>
    </body>
</html>
