<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>C4.5 - @yield('title')</title>
        <link rel="stylesheet" href="{{asset('datatables/datatables.min.css')}}">
        <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}">
        <script src="{{asset('datatables/datatables.min.js')}}"></script>
        <script defer src="{{asset('bootstrap/bootstrap.min.js')}}"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body style="background-color: #f2f7ff;">     
        <div class="d-flex" style="height: 100vh;">
            @include('layouts.sidebar')

            <div class="d-flex flex-column" style="width: 100%; height:100%;">
                @yield('content')
                <footer class="p-5" style="color:#7a89bd">
                    <small >CREATED by @RFR</small>
                    <small>2024 © WEB - Klasifikasi C4.5</small>
                </footer>
            </div>
        </div>

        <script>
            new DataTable('#example');
        </script>
    </body>
</html>
