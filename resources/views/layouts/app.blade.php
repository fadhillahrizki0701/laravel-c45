<!DOCTYPE html>
<html>
    <head>
        <title>C4.5 - @yield('title')</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Datatables --}}
        <link rel="stylesheet" href="{{ asset('datatables/datatables.min.css') }}">
        <script src="{{ asset('datatables/datatables.min.js') }}"></script>

        {{-- Bootstrap --}}
        <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
        <script defer src="{{ asset('bootstrap/bootstrap.min.js') }}"></script>

        {{-- Addition --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        {{-- AlpineJS --}}
        <script defer src="{{ asset('alpinejs/alpine.min.js') }}"></script>

        <style>
            body {
                margin: 0;
                padding: 0;
                display: flex;
                background-color: #f2f7ff;
            }

            #c45-sidebar {
                min-width: 250px;
                max-width: 250px;
                height: 100vh;
                padding: 20px;
                position: fixed;
                left: 0;
                top: 0;
                overflow-y: auto;
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
                margin-left: 250px;
                padding: 20px;
                width: calc(100% - 250px);
                background-color: #f4f4f4;
            }

            footer {
                color:#7a89bd;
            }
        </style>
    </head>
    <body>
        @include('layouts.sidebar')

        <div id="c45-content" class="d-flex flex-column">
            @yield('content')
            <footer class="p-5 text-end">
                <small >CREATED by @RFR</small>
                <small>2024 © WEB - Klasifikasi C4.5</small>
            </footer>
        </div>

        <script>
            new DataTable('#example');
        </script>
    </body>
</html>
