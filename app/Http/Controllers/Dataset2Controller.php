<?php

namespace App\Http\Controllers;

use App\Models\Dataset2;
use Illuminate\Http\Request;

class Dataset2Controller extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$dataset2 = Dataset2::all();

		return view("pages.dataset2-index", compact("dataset2"));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view("pages.dataset2-index");
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			"usia" => "required",
			"berat_badan_per_tinggi_badan" => "required",
			"menu" => "required",
			"keterangan" => "required",
		]);

		Dataset2::create([
			"usia" => $request->usia,
			"berat_badan_per_tinggi_badan" =>
				$request->berat_badan_per_tinggi_badan,
			"menu" => $request->menu,
			"keterangan" => $request->keterangan,
		]);

		return redirect()
			->route("dataset2.index")
			->with([
				"success" => "Data berhasil ditambahkan!",
			]);
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
		$dataset2 = Dataset2::find($id);

		return view("pages.dataset2-edit", compact("dataset2"));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		$this->validate($request, [
			"usia" => "required",
			"berat_badan_per_tinggi_badan" => "required",
			"menu" => "required",
			"keterangan" => "required",
		]);

		$dataset2 = Dataset2::find($id);
		$dataset2->update([
			"usia" => $request->usia,
			"berat_badan_per_tinggi_badan" =>
				$request->berat_badan_per_tinggi_badan,
			"menu" => $request->menu,
			"keterangan" => $request->keterangan,
		]);

		return redirect()
			->route("dataset2.index")
			->with([
				"success" => "Data berhasil diubah!",
			]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$dataset2 = Dataset2::find($id);
		$dataset2->delete();

		return redirect()
			->route("dataset2.index")
			->with([
				"success" => "Data berhasil dihapus!",
			]);
	}

	/**
	 * Process data split
	 */
	public function split(Request $request)
	{
		return redirect()->route('dataset1.index')->with([
			'success' => 'Split Dataset berhasil dilakukan',
		]);
	}
}
