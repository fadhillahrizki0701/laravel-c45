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

        <style>
            body {
                margin: 0;
                padding: 0;
                display: flex;
            }

            #c45-sidebar {
                width: 210px;
                height: 100vh;
                padding: 15px;
                position: fixed;
                left: 0;
                top: 0;
                overflow-y: auto;
            }

            #c45-sidebar h2 {
                text-align: center;
            }

            @media (max-width: 575.98px) {
                #c45-sidebar {
                    background-color: #333;
                    color: #fff;
                }

                #c45-sidebar a {
                    display: block;
                    color: #fff;
                    padding: 10px;
                    text-decoration: none;
                }

                #c45-sidebar a:hover {
                    background-color: #575757;
                }
            }

            #c45-content {
                margin-left: 210px;
                padding: 20px;
                width: calc(100% - 210px);
                background-color: #f4f4f4;
            }
        </style>
    </head>
    <body style="background-color: #f2f7ff;">
        @include('layouts.sidebar')

        <div id="c45-content" class="d-flex flex-column">
            @yield('content')
            <footer class="p-5" style="color:#7a89bd">
                <small >CREATED by @RFR</small>
                <small>2024 © WEB - Klasifikasi C4.5</small>
            </footer>
        </div>

        <script>
            new DataTable('#example');
        </script>
    </body>
</html>
