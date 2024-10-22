<?php

namespace App\Http\Controllers;

use App\Http\Controllers\C45\C45Controller;
use App\Models\Dataset1;
use App\Models\Datatrain1;
use Illuminate\Http\Request;

class DatasetClassification1Controller extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		if (Dataset1::all()->isEmpty()) {
			return view("pages.classification1-index", [
				"data" => [],
				"metrices" => [
					"accuracy" => 0,
					"precision" => 0,
					"recall" => 0,
					"f1_score" => 0,
					"correct_predictions" => 0,
					"total_test_data" => 0,
				],
				"rules" => [],
			]);
		}

		$c45 = new C45Controller();

		$dataTrain = Datatrain1::select([
			"usia",
			"berat_badan_per_usia",
			"tinggi_badan_per_usia",
			"berat_badan_per_tinggi_badan",
		])
			->get()
			->toArray();

		$tree = $c45->fetchTree(
			$dataTrain,
			[
				"usia",
				"berat_badan_per_usia",
				"tinggi_badan_per_usia",
				"berat_badan_per_tinggi_badan",
			],
			"berat_badan_per_tinggi_badan"
		);

		$data = Dataset1::select([
			"nama",
			"usia",
			"berat_badan_per_usia",
			"tinggi_badan_per_usia",
			"berat_badan_per_tinggi_badan",
		])
			->get()
			->toArray();

		$metrices = $c45->calculateMetricesWithTreeInput(
			$data,
			["berat_badan_per_usia", "tinggi_badan_per_usia", "usia"],
			"berat_badan_per_tinggi_badan",
			$tree
		);

		$rules = $c45->extractRules($tree);

		return view(
			"pages.classification1-index",
			compact("metrices", "rules", "data")
		);
	}
}
