<?php

namespace App\Http\Controllers;

use App\Models\Dataset1;
use Illuminate\Http\Request;

class Dataset1Controller extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$dataset1 = Dataset1::all();

		return view("pages.dataset1-index", compact("dataset1"));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view("pages.dataset1-index");
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			"nama" => "required",
			"usia" => "required",
			"berat_badan_per_usia" => "required",
			"tinggi_badan_per_usia" => "required",
			"berat_badan_per_tinggi_badan" => "required",
		]);

		Dataset1::create([
			"nama" => $request->nama,
			"usia" => $request->usia,
			"berat_badan_per_usia" => $request->berat_badan_per_usia,
			"tinggi_badan_per_usia" => $request->tinggi_badan_per_usia,
			"berat_badan_per_tinggi_badan" =>
				$request->berat_badan_per_tinggi_badan,
		]);

		return redirect()
			->route("dataset1.index")
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
		$dataset1 = Dataset1::find($id);

		return view("pages.dataset1-edit", compact("dataset1"));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		$this->validate($request, [
			"nama" => "required",
			"usia" => "required",
			"berat_badan_per_usia" => "required",
			"tinggi_badan_per_usia" => "required",
			"berat_badan_per_tinggi_badan" => "required",
		]);

		$dataset1 = Dataset1::find($id);

		$dataset1->update([
			"nama" => $request->nama,
			"usia" => $request->usia,
			"berat_badan_per_usia" => $request->berat_badan_per_usia,
			"tinggi_badan_per_usia" => $request->tinggi_badan_per_usia,
			"berat_badan_per_tinggi_badan" =>
				$request->berat_badan_per_tinggi_badan,
		]);

		return redirect()
			->route("dataset1.index")
			->with([
				"success" => "Data berhasil diubah!",
			]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$dataset1 = Dataset1::find($id);
		$dataset1->delete();

		return redirect()
			->route("dataset1.index")
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
