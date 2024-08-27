<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\C45\DecisionTreeNodeController as DecisionTreeNode;
use App\Models\Dataset1;
use App\Models\Dataset2;

class C45Controller extends Controller
{
	public function calculateEntropy($data, $labelAttribute)
	{
		$total = count($data);
		$labels = array_column($data, $labelAttribute);
		$labelCounts = array_count_values($labels);
		$entropy = 0.0;

		foreach ($labelCounts as $count) {
			$probability = $count / $total;
			$entropy -= $probability * log($probability, 2);
		}

		return $entropy;
	}

	public function calculateGain($data, $attribute, $labelAttribute)
	{
		$totalEntropy = $this->calculateEntropy($data, $labelAttribute);
		$values = array_unique(array_column($data, $attribute));
		$subsetEntropy = 0.0;

		foreach ($values as $value) {
			$subset = array_filter($data, function ($row) use (
				$attribute,
				$value
			) {
				return $row[$attribute] == $value;
			});
			$subsetProbability = count($subset) / count($data);
			$subsetEntropy +=
				$subsetProbability *
				$this->calculateEntropy($subset, $labelAttribute);
		}

		return $totalEntropy - $subsetEntropy;
	}

	public function chooseBestAttribute($data, $attributes, $labelAttribute)
	{
		$bestAttribute = null;
		$bestGain = -INF;

		foreach ($attributes as $attribute) {
			$gain = $this->calculateGain($data, $attribute, $labelAttribute);
			if ($gain > $bestGain) {
				$bestGain = $gain;
				$bestAttribute = $attribute;
			}
		}

		return $bestAttribute;
	}

	public function buildTree($data, $attributes, $labelAttribute)
	{
		$labels = array_column($data, $labelAttribute);
		if (count(array_unique($labels)) === 1) {
			$leaf = new DecisionTreeNode();
			$leaf->isLeaf = true;
			$leaf->label = $labels[0];
			return $leaf;
		}

		if (empty($attributes)) {
			$leaf = new DecisionTreeNode();
			$leaf->isLeaf = true;
			$leaf->label = array_search(
				max(array_count_values($labels)),
				array_count_values($labels)
			);
			return $leaf;
		}

		$bestAttribute = $this->chooseBestAttribute(
			$data,
			$attributes,
			$labelAttribute
		);
		$tree = new DecisionTreeNode($bestAttribute);

		$values = array_unique(array_column($data, $bestAttribute));
		foreach ($values as $value) {
			$subset = array_filter($data, function ($row) use (
				$bestAttribute,
				$value
			) {
				return $row[$bestAttribute] == $value;
			});

			if (empty($subset)) {
				$leaf = new DecisionTreeNode();
				$leaf->isLeaf = true;
				$leaf->label = array_search(
					max(array_count_values($labels)),
					array_count_values($labels)
				);
				$tree->children[$value] = $leaf;
			} else {
				$newAttributes = array_diff($attributes, [$bestAttribute]);
				$tree->children[$value] = $this->buildTree(
					$subset,
					$newAttributes,
					$labelAttribute
				);
			}
		}

		return $tree;
	}

	public function fetchTreeDataset1()
	{
		$data = Dataset1::all()->toArray();
		$attributes = ["usia", "berat_badan_per_usia", "tinggi_badan_per_usia"];
		$labelAttribute = "berat_badan_per_tinggi_badan"; // Specify the label attribute
		$tree = $this->buildTree($data, $attributes, $labelAttribute);
		return response()->json($tree);
	}

	public function fetchTreeDataset2()
	{
		$data = Dataset2::all()->toArray();
		$attributes = ["usia", "menu"];
		$labelAttribute = "keterangan"; // Specify the label attribute
		$tree = $this->buildTree($data, $attributes, $labelAttribute);
		return response()->json($tree);
	}
}
