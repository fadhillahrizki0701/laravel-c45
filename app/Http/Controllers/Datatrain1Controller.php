<?php

namespace App\Http\Controllers;

use App\Http\Controllers\C45\C45Controller;
use App\Models\Dataset1;
use App\Models\Datatrain1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory as PhpSpreadsheet;

class Datatrain1Controller extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		if (Dataset1::all()->isEmpty()) {
			return view("pages.datatrain2-index", [
				'accuracy' => [
					'data' => [
						'train' => [],
						'test' => [],
					]
				]
			]);
		}

		$c45 = new C45Controller();
		$tree = $c45->fetchTreeDataset1Internal();

		$data = Datatrain1::select([
			'nama',
			'usia',
			'berat_badan_per_usia',
			'tinggi_badan_per_usia',
			'berat_badan_per_tinggi_badan',
		])->get()->toArray();

		$metrices = $c45->calculateMetrices($data, [
			'berat_badan_per_usia',
			'tinggi_badan_per_usia',
			'usia',
		], 'berat_badan_per_tinggi_badan');

		$rules = $c45->extractRules($tree);

		return view("pages.datatrain1-index", compact(
			'metrices',
			'data',
			'rules',
		));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		
	}


	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
	}

	public function clear()
	{
		
	}

	public function mining()
	{
		return view('pages.datatrain1-mining');
	}
}
