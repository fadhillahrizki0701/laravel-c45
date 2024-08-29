<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function calculateSplitInfo($data, $attribute)
    {
        $total = count($data);
        $values = array_column($data, $attribute);
        $valueCounts = array_count_values($values);
        $splitInfo = 0.0;

        foreach ($valueCounts as $count) {
            $probability = $count / $total;
            $splitInfo -= $probability * log($probability, 2);
        }

        return $splitInfo;
    }

    public function calculateGain($data, $attribute, $labelAttribute)
    {
        $totalEntropy = $this->calculateEntropy($data, $labelAttribute);
        $values = array_unique(array_column($data, $attribute));
        $subsetEntropy = 0.0;

        foreach ($values as $value) {
            $subset = array_filter($data, function ($row) use ($attribute, $value) {
                return $row[$attribute] == $value;
            });
            $subsetProbability = count($subset) / count($data);
            $subsetEntropy += $subsetProbability * $this->calculateEntropy($subset, $labelAttribute);
        }

        return $totalEntropy - $subsetEntropy;
    }

    public function calculateGainRatio($data, $attribute, $labelAttribute)
    {
        $gain = $this->calculateGain($data, $attribute, $labelAttribute);
        $splitInfo = $this->calculateSplitInfo($data, $attribute);

        if ($splitInfo == 0) {
            return 0; // Avoid division by zero
        }

        return $gain / $splitInfo;
    }

    public function processNode($data, $attributes, $labelAttribute)
    {
        $output = [];
        $bestAttribute = null;
        $bestGainRatio = -INF;

        foreach ($attributes as $attribute) {
            $gain = $this->calculateGain($data, $attribute, $labelAttribute);
            $splitInfo = $this->calculateSplitInfo($data, $attribute);
            $gainRatio = $this->calculateGainRatio($data, $attribute, $labelAttribute);

            // Prepare the structure of the output based on the Excel calculation format
            $attributeData = [
                'GAIN' => $gain,
                'SPLIT_INFO' => $splitInfo,
                'GAIN_RATIO' => $gainRatio,
            ];

            $values = array_unique(array_column($data, $attribute));
            foreach ($values as $value) {
                $subset = array_filter($data, function ($row) use ($attribute, $value) {
                    return $row[$attribute] == $value;
                });
                $labelCounts = array_count_values(array_column($subset, $labelAttribute));
                $entropy = $this->calculateEntropy($subset, $labelAttribute);

                // Add details to attribute data
                $attributeData[$value] = [
                    'label_counts' => $labelCounts,
                    'entropy' => $entropy,
                    'probability' => count($subset) / count($data),
                ];
            }

            $output[$attribute] = $attributeData;

            // Select the best attribute based on Gain Ratio
            if ($gainRatio > $bestGainRatio) {
                $bestGainRatio = $gainRatio;
                $bestAttribute = $attribute;
            }
        }

        // Add the best attribute to the output
        $output['best_attribute'] = $bestAttribute;

        return $output;
    }

    public function fetchTreeDataset1()
    {
        $data = Dataset1::all()->toArray();
        $attributes = ["usia", "berat_badan_per_usia", "tinggi_badan_per_usia"];
        $labelAttribute = "berat_badan_per_tinggi_badan"; // Specify the label attribute
        $rootNodeData = $this->processNode($data, $attributes, $labelAttribute);
        return response()->json($rootNodeData);
    }

    public function fetchTreeDataset2()
    {
        $data = Dataset2::all()->toArray();
        $attributes = ["usia", "menu"];
        $labelAttribute = "keterangan"; // Specify the label attribute
        $rootNodeData = $this->processNode($data, $attributes, $labelAttribute);
        return response()->json($rootNodeData);
    }
}
