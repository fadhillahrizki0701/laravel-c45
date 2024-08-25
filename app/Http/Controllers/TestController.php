<?php

namespace App\Http\Controllers;

use App\Models\Dataset1;
use App\Utils\C45;
use Illuminate\Http\Request;

class TestController extends Controller
{
	public function index()
	{
		$total_normal = $this->getCount("berat_badan_per_usia", "Normal");
		$total_kurang = $this->getCount("berat_badan_per_usia", "Kurang");
		$total_sangat_kurang = $this->getCount(
			"berat_badan_per_usia",
			"Sangat Kurang"
		);

		$total_tbu_normal = $this->getCount("tinggi_badan_per_usia", "Normal");
		$total_tbu_normal_gizi_baik = $this->getCount(
			"tinggi_badan_per_usia",
			"Normal",
			"berat_badan_per_tinggi_badan",
			"Gizi Baik"
		);

		// return compact('total_normal', 'total_kurang', 'total_sangat_kurang', 'total_tbu_normal', 'total_tbu_normal_gizi_baik');
		return $this->getEntropy();
	}

	private function getCount(
		string $field,
		string $attr,
		string $field2 = "",
		string $attr2 = ""
	) {
		$query = Dataset1::where($field, $attr);

		if (!empty($attr2)) {
			$query->where($field2, $attr2)->count();
		}

		return $query->count();
	}

	private function getEntropy(string $attr = "")
	{
		// jika total nilai attribute ada salah satunya 0, maka langsung kita kasih nol, jika tidak
		// maka calculate rumus cari entropy
		$jumlah = $this->getCount("berat_badan_per_usia", $attr);
		$gizi_baik = $this->getCount(
			"berat_badan_per_usia",
			"Normal",
			"berat_badan_per_tinggi_badan",
			"Gizi Baik"
		);
		$gizi_kurang = $this->getCount(
			"berat_badan_per_usia",
			"Normal",
			"berat_badan_per_tinggi_badan",
			"Gizi Kurang"
		);

		if ($jumlah == 0 || $gizi_baik == 0 || $gizi_kurang == 0) {
			return 0;
		}

		$nilai =
			(-$gizi_baik / $jumlah) * log($gizi_baik / $jumlah, 2) +
			(-$gizi_kurang / $jumlah) * log($gizi_kurang / $jumlah, 2);
		return $nilai;
	}
}
