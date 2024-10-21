<?php

namespace App\Http\Controllers;

use App\Http\Controllers\C45\C45Controller;
use App\Models\Dataset2;
use App\Models\Datatrain2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory as PhpSpreadsheet;

class Datatrain2Controller extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		if (Dataset2::all()->isEmpty()) {
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
		$tree = $c45->fetchTreeDataset2Internal();

		$data = Dataset2::select([
			'usia',
			'berat_badan_per_tinggi_badan',
			'menu',
			'keterangan'
		])->get()->toArray();

		$metrices = $c45->calculate($data, [
			'usia',
			'berat_badan_per_tinggi_badan',
			'menu',
		], 'keterangan', 0.73);

		$rules = $c45->extractRules($tree);

		return view("pages.datatrain2-index", compact(
			'metrices',
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
