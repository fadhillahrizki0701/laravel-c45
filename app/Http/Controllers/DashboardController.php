<?php

namespace App\Http\Controllers;

use App\Models\Dataset1;
use App\Models\Dataset2;
use App\Models\User;
class DashboardController extends Controller
{
    public function index()
    {
        $totaldataset1 = Dataset1::count();
        $totaldataset2 = Dataset2::count();
        $totalusers= User::count();

        return view('dashboard', compact('totaldataset1', 'totaldataset2','totalusers'));
    }
}
