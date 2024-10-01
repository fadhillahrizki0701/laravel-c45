<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;
use App\Models\Dataset1;
use App\Models\Dataset2;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory as PhpSpreadsheet;

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

    private function countOnLabel(array $data, string $label, string $labelValue)
    {
        $filter = array_filter($data, function ($_data) use ($label, $labelValue) {
            return $_data[$label] == $labelValue;
        });

        return count($filter);
    }

    private function tableProcess(array $data, string $label, int $depth = 0, array $attributes): array
    {
        $table = [];
        $_data = $data;

        // Check if data is empty
        if (empty($_data)) {
            return $table; // Return an empty table if no data is provided
        }

        // Calculate the overall entropy for the current node
        $entropy = $this->entropy($_data, $label);
        $total = count($_data);

        // Get unique values of the label
        $labelValues = array_unique(array_column($_data, $label));

        // Initialize label value data for the current depth
        $labelValueData = [];

        // Count occurrences of each label value
        foreach ($labelValues as $labelValue) {
            $labelValueData[$labelValue] = $this->countOnLabel($_data, $label, $labelValue);
        }

        // Add the current depth entry to the table
        $table[] = [
            'depth' => $depth,
            'total' => $total,
            'entropy' => $entropy,
            'labelValues' => $labelValueData,
        ];

        // If all instances have the same label, this is a leaf node
        if (count($labelValues) === 1) {
            return $table; // No need to process further, it's a pure node
        }

        $bestGain = 0;
        $bestAttribute = null;

        foreach ($attributes as $attribute) {
            $gain = $this->gain($_data, $label, $attribute);
            if ($gain > $bestGain) {
                $bestGain = $gain;
                $bestAttribute = $attribute;
            }

            // Add gain calculations to the table
            $attributeValues = array_unique(array_column($_data, $attribute));
            foreach ($attributeValues as $attributeValue) {
                // Create a detailed entry for this attribute's processing
                $subset = array_filter($_data, function ($row) use ($attribute, $attributeValue) {
                    return $row[$attribute] == $attributeValue;
                });
                $subsetEntropy = $this->entropy($subset, $label);
                $subsetCount = count($subset);
                $subsetProbability = $subsetCount / $total;

                $table[] = [
                    'depth' => $depth,
                    'attribute' => $attribute,
                    'attribute_value' => $attributeValue,
                    'subset_count' => $subsetCount,
                    'subset_entropy' => $subsetEntropy,
                    'subset_probability' => round($subsetProbability, 4),
                    'gain' => round($gain, 10),
                ];
            }
        }

        // Recursively process deeper nodes using the best attribute for splitting
        if ($bestAttribute) {
            $attributeValues = array_unique(array_column($_data, $bestAttribute));
            foreach ($attributeValues as $attributeValue) {
                $subset = array_filter($_data, function ($row) use ($bestAttribute, $attributeValue) {
                    return $row[$bestAttribute] == $attributeValue;
                });

                if (!empty($subset)) {
                    // Recursively process this subset at a deeper level
                    $table = array_merge($table, $this->tableProcess($subset, $label, $depth + 1, $attributes));
                }
            }
        }

        return $table;
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

    public function extractRules(array $tree, string $parentRule = ''): array
    {
        $rules = [];

        // Check if the current node is not a leaf
        if (!$tree['isLeaf']) {
            $root = $tree['name'];

            // Loop through the children of the current node
            foreach ($tree['children'] as $child) {
                // Build the current rule
                $currentRule = $parentRule . "IF '{$root}' IS '{$child['attribute_value']}'\n";

                if (!$child['node']['isLeaf']) {
                    // If the child node is not a leaf, recursively append rules from the subtree
                    $subRules = $this->extractRules($child['node'], $currentRule);
                    $rules = array_merge($rules, $subRules); // Merge the recursive results
                } else {
                    // For leaf nodes, append the final classification rule
                    $rules[] = $currentRule . "  THEN '{$child['node']['name']}'";
                }
            }
        }

        return $rules;
    }

    public function fetchTreeDataset1Internal()
    {
        $data = Dataset1::select([
            'usia',
            'berat_badan_per_usia',
            'tinggi_badan_per_usia',
            'berat_badan_per_tinggi_badan',
        ])->get()->toArray();

        if ($data == []) {
            return [];
        }

        $attributes = [
            'berat_badan_per_usia',
            'tinggi_badan_per_usia',
            'usia',
        ];
        $label = 'berat_badan_per_tinggi_badan';

        $tree = $this->buildTree($data, $label, $attributes);
        return $tree;
    }

    public function fetchTreeDataset2Internal()
    {
        $data = Dataset2::select([
            'usia',
            'berat_badan_per_tinggi_badan',
            'menu',
            'keterangan'
        ])->get()->toArray();

        if ($data == []) {
            return [];
        }

        $attributes = [
            'usia',
            'berat_badan_per_tinggi_badan',
            'menu',
        ];
        $label = 'keterangan';

        $tree = $this->buildTree($data, $label, $attributes);

        return $tree;
    }

    public function fetchTreeDataset1()
    {
        $data = Dataset1::select([
            'usia',
            'berat_badan_per_usia',
            'tinggi_badan_per_usia',
            'berat_badan_per_tinggi_badan',
        ])->get()->toArray();

        if ($data == []) {
            return response()->json([]);
        }

        $attributes = [
            'berat_badan_per_usia',
            'tinggi_badan_per_usia',
            'usia',
        ];
        $label = 'berat_badan_per_tinggi_badan';

        $table = $this->tableProcess($data, $label, 0, $attributes);
        $tree = $this->buildTree($data, $label, $attributes);
        $rules = $this->extractRules($tree);

        $data = array_merge($tree, ['rules' => $rules], $table);

        return response()->json($data);
    }

    public function fetchTreeDataset2()
    {
        $data = Dataset2::select([
            'usia',
            'berat_badan_per_tinggi_badan',
            'menu',
            'keterangan'
        ])->get()->toArray();

        if ($data == []) {
            return response()->json([]);
        }

        $attributes = [
            'usia',
            'berat_badan_per_tinggi_badan',
            'menu',
        ];
        $label = 'keterangan';

        $tree = $this->buildTree($data, $label, $attributes);
        $rules = $this->extractRules($tree);

        $data = array_merge($tree, ['rules' => $rules]);

        return response()->json($data);
    }

    public function predict(array $tree, array $data)
    {
        if ($tree['isLeaf']) {
            return $tree['name']; // Jika sudah mencapai leaf, return label
        }

        foreach ($tree['children'] as $child) {
            if ($data[$tree['name']] == $child['attribute_value']) {
                return $this->predict($child['node'], $data); // Rekursif untuk node berikutnya
            }
        }

        return null;
    }

    public function calculateAccuracy(array $dataTrain, array $dataTest, array $attributes, string $label)
    {
        if (empty($dataTrain) || empty($dataTest)) {
            return response()->json([
                'accuracy' => 0,
                'message' => 'No data available for testing.',
            ]);
        }

        // $splitRatio = 0.1;
        // $trainSize = (int) (count($data) * $splitRatio);
        $trainData = $dataTrain;
        $testData = $dataTest;

        // Bangun pohon keputusan menggunakan data training
        $tree = $this->buildTree($trainData, $label, $attributes);

        // Lakukan prediksi pada data testing
        $correctPredictions = 0;
        foreach ($testData as $testRow) {
            $predictedLabel = $this->predict($tree, $testRow);
            $actualLabel = $testRow['predicted_label'];

            if ($predictedLabel == $actualLabel) {
                $correctPredictions++;
            }
        }

        // Hitung akurasi
        $totalTestData = count($testData);
        $accuracy = ($correctPredictions / $totalTestData) * 100;

        $table = $this->tableProcess($dataTrain, $label, 0, $attributes);

        return [
            'accuracy' => round($accuracy, 2),
            'correct_predictions' => $correctPredictions,
            'total_test_data' => $totalTestData,
            'table' => $table,
        ];
    }
}
