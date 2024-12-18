<!DOCTYPE html>
<html>
    <head>
        <title>Klasifikasi C4.5 | @yield('title')</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Datatables --}}
        <link rel="stylesheet" href="{{ asset('datatables/datatables.min.css') }}">
        <script src="{{ asset('datatables/datatables.min.js') }}"></script>

        {{-- Bootstrap --}}
        <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
        <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
        {{-- <script src="{{ asset('bootstrap/popper.min.js') }}"></script> --}}

        {{-- Addition --}}
        <link rel="stylesheet" href="{{ asset('datatables/datatables.datatables.css') }}">
        <link rel="stylesheet" href="{{ asset('bootstrap-icons/bootstrap-icons.min.css') }}">

        {{-- AlpineJS --}}
        <script defer src="{{ asset('alpinejs/alpine.min.js') }}"></script>

        {{-- D3js --}}
        <script defer src="{{ asset('d3js/d3.min.js') }}"></script>

        <style>
            body {
                margin: 0;
                padding: 0;
                display: flex;
                background-color: #f2f7ff;
            }
        
            #c45-sidebar {
                background-color: #d9e7ff;
                min-width: 250px;
                max-width: 250px;
                height: 100vh;
                padding: 20px;
                position: fixed;
                left: 0;
                top: 0;
                overflow-y: auto;
                transition: all 0.3s ease;
                z-index: 90;
            }

            #c45-sidebar .nav-link {
                transition: 0.25s linear;
            }

            #c45-sidebar button.nav-link {
                display: flex;
                gap: 6px;
                width: 100%;
            }

            #c45-sidebar .nav-link:hover {
                background-color: #76a8ffc0;
            }
        
            /* Sidebar hidden */
            #c45-sidebar.sidebar-open {
                transform: translateX(-250px);
            }
        
            #c45-content {
                margin-left: 250px;
                padding: 20px;
                width: calc(100% - 250px);
                background-color: #f4f4f4;
                transition: margin-left 0.3s ease;
                height: max-content;
            }
        
            /* Content adjustment when sidebar is hidden */
            .sidebar-open ~ #c45-content {
                margin-left: 0;
                width: 100%;
            }

            #sidebar-button {
                display: none;
                border: 0;
                outline: 0;
                width: 100%;
                height: 40px;
                position: fixed;
                z-index: 90;
                background-color: #d9e7ff;
                box-shadow: 0 0 6px #0000002d;
            }

            #c45-content section.container {
                min-height: calc(100vh - 160px);
            }
        
            @media (max-width: 575.98px) {
                #c45-sidebar {
                    background-color: #f2f7ffef;
                    margin-top: 40px;
                    left: -250px;
                    box-shadow: 0 0 6px #0000002d;
                }
        
                #c45-sidebar.sidebar-open {
                    transform: translateX(250px);
                }
        
                #c45-content {
                    margin-left: 0px;
                    padding: 0;
                    padding-top: 40px;
                    width: 100%;
                }

                #c45-content.sidebar-open {
                    margin-left: 0;
                    width: 100%;
                }

                #sidebar-button {
                    display: block;
                }
            }

            footer {
                width: 100%;
                color:#7a89bd;
            }
        </style>
    </head>
    <body x-data="{ open: false }">
        @include('layouts.sidebar')

        <div id="c45-content" class="d-flex flex-column justify-content-between" :class="{ 'sidebar-open': open }">
            @yield('content')
            <footer class="p-5 text-center align-self-end">
                <small>Created by @RFR | © <span x-text="new Date().getFullYear()"></span> | Klasifikasi C4.5</small>
            </footer>
        </div>

        <script>
            new DataTable('#example', {
                language: {
                    url: '{{ asset('datatables/i18n/id.json') }}'
                }
            });

            // Enable Bootstrap Tooltip
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        </script>
    </body>
</html>
