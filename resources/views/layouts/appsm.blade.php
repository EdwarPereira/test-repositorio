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

        <style type="text/css">
            body {
                font-family: 'Lato';
            }

            .fa-btn {
                margin-right: 6px;
            }
        </style>
    </head>
    <body id="app-layout">
        @yield('content')
        <script type="text/javascript">
            $(function() {
                $("#datepicker").datepicker( $.datepicker.regional["pt-br"]);
            });
        </script>
    </body>
</html>
