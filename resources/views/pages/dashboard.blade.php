<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Dashboard</h2>

    @include('pages.partials.session-notification')

    <div class="row">
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card bg-white text-dark">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="d-flex justify-content-center align-items-center p-2 rounded text-light" style="background-color: #b283fd; min-width: 50px;">
                                <i class="bi-bar-chart-line-fill fs-1"></i>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8 mt-3 d-md-none text-center">
                            <h5 class="card-title font-semibold" style="color: #995bfd;">Dataset 1</h5>
                            <hr class="my-2">
                            <h6 class="fs-5 text-muted">{{ $totaldataset1 }}</h6>
                        </div>
                        <div class="col-sm-12 col-md-8 d-none d-md-block">
                            <h5 class="card-title font-semibold" style="color: #995bfd;">Data 1</h5>
                            <hr class="my-2">
                            <h6 class="fs-5 text-muted">{{ $totaldataset1 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card bg-white text-dark">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="d-flex justify-content-center align-items-center p-2 rounded text-light" style="background-color: #47b8ff; min-width: 50px;">
                                <i class="bi-bar-chart-line-fill fs-1"></i>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8 mt-3 d-md-none text-center">
                            <h5 class="card-title font-semibold" style="color: #26acff;">Dataset 2</h5>
                            <hr class="my-2">
                            <h6 class="fs-5 text-muted">{{ $totaldataset2 }}</h6>
                        </div>
                        <div class="col-sm-12 col-md-8 d-none d-md-block">
                            <h5 class="card-title font-semibold" style="color: #26acff;">Dataset 2</h5>
                            <hr class="my-2">
                            <h6 class="fs-5 text-muted">{{ $totaldataset2 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card bg-white text-dark">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="d-flex justify-content-center align-items-center p-2 rounded text-light" style="background-color: #ff2088; min-width: 50px;">
                                <i class="bi-people fs-1"></i>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8 mt-3 d-md-none text-center">
                            <h5 class="card-title font-semibold" style="color: #ff2088;">Pengguna</h5>
                            <hr class="my-2">
                            <h6 class="fs-5 text-muted">{{ $totalusers }}</h6>
                        </div>
                        <div class="col-sm-12 col-md-8 d-none d-md-block">
                            <h5 class="card-title font-semibold" style="color: #ff2088;">Pengguna</h5>
                            <hr class="my-2">
                            <h6 class="fs-5 text-muted">{{ $totalusers }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
