<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mt-4 p-4  ">
    <span class="fs-3 " style="color:#435EBE">Dashboard Statistic</span>
    <div class="row">

        <div class="col-6 col-lg-4 col-md-6 ">
            <div class="card " style="background-color: rgb(255, 255, 255)">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon p-2 rounded text-light mb-2 " style="background-color: #b283fd ">
                                <i class="bi-bar-chart-line-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Data 1</h6>
                            <h6 class="card-title">{{ $totaldataset1 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 col-md-6">
            <div class="card bg-white text-dark btn-warning">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon p-2 rounded text-light mb-2 " style="background-color: #47b8ff ">
                                <i class="bi-bar-chart-line-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Data 2</h6>
                            <h6 class="card-title">{{ $totaldataset2}} </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-4 col-md-6">
            <div class="card bg-white text-dark">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon p-2 rounded text-light mb-2 " style="background-color: #ff2088 ">
                                <i class="bi-people"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">User</h6>
                            <h6 class="card-title">{{ $totalusers}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
