<?php

namespace App\Http\Controllers;

use App\Models\Dataset1;
use Illuminate\Http\Request;

class Klasifikasi1Controller extends Controller
{
	public function index()
	{
		$dataset1 = Dataset1::all();

		return view("pages.klasifikasi1", compact("dataset1"));
	}
}
