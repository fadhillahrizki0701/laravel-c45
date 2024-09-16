<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;
use App\Models\Dataset1;

class C45Controller extends Controller
{
    private function gain(array $data, string $label, string $attribute): float
    {
        $parentEntropy = $this->entropy($data, $label);
        $values = array_unique(array_column($data, $attribute));
        $subsetEntropy = 0.0;

        foreach ($values as $value) {
            $subset = array_filter($data, function ($row) use ($attribute, $value) {
                return $row[$attribute] == $value;
            });

            $subsetProbability = count($subset) / count($data);
            $subsetEntropy += $subsetProbability * $this->entropy($subset, $label);
        }

        return round($parentEntropy - round($subsetEntropy, 10), 10);
    }

    private function entropy(array $data, string $label): float
    {
        $total = count($data);
        $labelCount = array_count_values(array_column($data, $label));
        $entropy = 0.0;

        foreach ($labelCount as $count) {
            $probability = $count / $total;
            $entropy -= $probability * log($probability, 2);
        }

        return round($entropy, 10);
    }

    private function calculateGain(array $data, string $label, array $attributes): array
    {
        $gains = [];
        $highestGain = 0;
        $bestAttribute = null;

        foreach ($attributes as $attribute) {
            $gains[$attribute] = $this->gain($data, $label, $attribute);

            if ($gains[$attribute] > $highestGain) {
                $highestGain = $gains[$attribute];
                $bestAttribute = $attribute;
            }
        }

        return [
            'highest_gain' => $highestGain,
            'best_attribute' => $bestAttribute,
            'gains' => $gains,
        ];
    }

    private function buildTree(array $data, string $label, array $attributes): array
    {
        // Base case: if all data have the same label or no more attributes to split
        if (count(array_unique(array_column($data, $label))) === 1 || empty($attributes)) {
            // Return the value of 'berat_badan_per_tinggi_badan' in the leaf node
            return [
                'name' => array_values(array_unique(array_column($data, $label)))[0],
                'isLeaf' => true,
            ];
        }

        // Calculate gain for each attribute and get the best attribute
        $gainInfo = $this->calculateGain($data, $label, $attributes);
        $bestAttribute = $gainInfo['best_attribute'];

        if (!$bestAttribute) {
            // If no best attribute, treat this as a leaf node and display the label value
            return [
                'name' => array_values(array_unique(array_column($data, $label)))[0],
                'isLeaf' => true,
            ];
        }

        // Split data by the best attribute
        $values = array_unique(array_column($data, $bestAttribute));
        $tree = [
            'name' => $bestAttribute,
            'isLeaf' => false,
            'children' => [],
        ];

        foreach ($values as $value) {
            // Filter the data based on the attribute's value
            $subset = array_filter($data, function ($row) use ($bestAttribute, $value) {
                return $row[$bestAttribute] == $value;
            });

            // Remove the used attribute and recursively build the subtree
            $remainingAttributes = array_diff($attributes, [$bestAttribute]);
            $child = $this->buildTree($subset, $label, $remainingAttributes);

            $tree['children'][] = [
                'attribute_value' => $value,
                'node' => $child,
            ];
        }

        return $tree;
    }


    // Function to fetch and process the dataset for tree construction
    public function fetchTreeDataset1()
    {
        $data = Dataset1::select([
            'id',
            'usia',
            'berat_badan_per_usia',
            'tinggi_badan_per_usia',
            'berat_badan_per_tinggi_badan',
        ])->get()->toArray();

        $attributes = [
            'berat_badan_per_usia',
            'tinggi_badan_per_usia',
            'usia',
        ];
        $label = 'berat_badan_per_tinggi_badan';

        // Build the decision tree
        $tree = $this->buildTree($data, $label, $attributes);

        return response()->json($tree);
    }
}
