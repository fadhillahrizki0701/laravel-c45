<?php

namespace App\Http\Controllers;

use App\Http\Controllers\C45\C45Controller;
use App\Models\Datatrain2;
use Illuminate\Http\Request;

class Datatrain2Controller extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		if (Datatrain2::all()->isEmpty()) {
			return view("pages.datatrain2-index", [
				'data' => [],
				'metrices' => [
					'accuracy' => 0,
					'precision' => 0,
					'recall' => 0,
					'f1_score' => 0,
					'correct_predictions' => 0,
                    'total_test_data' => 0,
				],
				'rules' => [],
			]);
		}

		$c45 = new C45Controller();
		$tree = $c45->fetchTreeDataset2Internal();

		$data = Datatrain2::select([
			'usia',
			'berat_badan_per_tinggi_badan',
			'menu',
			'keterangan'
		])->get()->toArray();

		$metrices = $c45->calculateMetrices($data, [
			'usia',
			'berat_badan_per_tinggi_badan',
			'menu',
		], 'keterangan');

		$rules = $c45->extractRules($tree);

		return view("pages.datatrain2-index", compact(
			'metrices',
			'rules',
			'data',
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
		//
	}

	public function clear()
	{

	}

	public function mining()
	{
		return view('pages.datatrain2-mining');
	}
}
