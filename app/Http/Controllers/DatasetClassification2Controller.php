<?php

namespace App\Http\Controllers;

use App\Http\Controllers\C45\C45Controller;
use App\Models\Dataset2;
use App\Models\Datatrain2;
use Illuminate\Http\Request;

class DatasetClassification2Controller extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		if (Dataset2::all()->isEmpty()) {
			return view("pages.classification2-index", [
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

		$dataTrain = Datatrain2::select([
			"usia",
			"berat_badan_per_tinggi_badan",
			"menu",
			"keterangan",
		])
			->get()
			->toArray();

		$tree = $c45->fetchTree(
			$dataTrain,
			["usia", "berat_badan_per_tinggi_badan", "menu"],
			"keterangan"
		);

		$data = Dataset2::select([
			"usia",
			"berat_badan_per_tinggi_badan",
			"menu",
			"keterangan",
		])
			->get()
			->toArray();

		$metrices = $c45->calculateMetricesWithTreeInput(
			$data,
			["usia", "berat_badan_per_tinggi_badan", "menu"],
			"keterangan",
			$tree
		);

		$rules = $c45->extractRules($tree);

		return view(
			"pages.classification2-index",
			compact("metrices", "rules", "data")
		);
	}
}
