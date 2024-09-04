<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;
use App\Models\Dataset1;

class C45Controller extends Controller
{
    // Function to calculate the mean of a given attribute
    public function calculateMean($data, $attribute)
    {
        $values = array_column($data, $attribute);
        return array_sum($values) / count($values);
    }

    // Function to calculate the median of a given attribute
    public function calculateMedian($data, $attribute)
    {
        $values = array_column($data, $attribute);
        sort($values);
        $count = count($values);
        $middle = floor(($count - 1) / 2);
        if ($count % 2) {
            return $values[$middle];
        } else {
            return ($values[$middle] + $values[$middle + 1]) / 2.0;
        }
    }

    // Function to group 'Usia' values based on mean and median
    public function groupByCustomAttributes($data, $mean, $median)
    {
        foreach ($data as &$row) {
            if ($row['usia'] <= $mean) {
                $row['usia_group'] = 'below_mean';
            } else {
                $row['usia_group'] = 'above_mean';
            }

            if ($row['usia'] <= $median) {
                $row['usia_group_median'] = 'below_median';
            } else {
                $row['usia_group_median'] = 'above_median';
            }
        }
        return $data;
    }

    // Function to calculate entropy for a given set of data
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

    // Function to calculate Split Info for a given attribute
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

    // Function to calculate Gain for a given attribute
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

    // Function to calculate Gain Ratio for a given attribute
    public function calculateGainRatio($data, $attribute, $labelAttribute)
    {
        $gain = $this->calculateGain($data, $attribute, $labelAttribute);
        $splitInfo = $this->calculateSplitInfo($data, $attribute);

        if ($splitInfo == 0) {
            return 0; // Avoid division by zero
        }

        return $gain / $splitInfo;
    }

    // Main function to process the node based on given data and attributes
    public function processNode($data, $attributes, $labelAttribute)
{
    $bestAttribute = null;
    $bestGainRatio = -INF;

    // Calculate mean and median for Usia
    $mean = $this->calculateMean($data, 'usia');
    $median = $this->calculateMedian($data, 'usia');

    // Group Usia based on mean and median
    $groupedData = $this->groupByCustomAttributes($data, $mean, $median);

    // Iterate through attributes to find the best one based on Gain Ratio
    foreach ($attributes as $attribute) {
        $gain = $this->calculateGain($groupedData, $attribute, $labelAttribute);
        $splitInfo = $this->calculateSplitInfo($groupedData, $attribute);
        $gainRatio = $this->calculateGainRatio($groupedData, $attribute, $labelAttribute);

        // Select the best attribute based on Gain Ratio
        if ($gainRatio > $bestGainRatio) {
            $bestGainRatio = $gainRatio;
            $bestAttribute = $attribute;
        }
    }

    // Create the decision tree node
    if ($bestAttribute) {
        $rootNode = new DecisionTreeNodeController($bestAttribute);

        // Define $values here
        $values = array_unique(array_column($groupedData, $bestAttribute));

        // Create child nodes for each value of the best attribute
        foreach ($values as $value) {
            $subset = array_filter($groupedData, function ($row) use ($bestAttribute, $value) {
                return $row[$bestAttribute] == $value;
            });

            // Recursively process the subset to create child nodes
            $childNode = $this->processNode($subset, array_diff($attributes, [$bestAttribute]), $labelAttribute);
            $rootNode->addChild($childNode);
        }

        return $rootNode;
    } else {
        // If no attribute has a gain ratio above the threshold, set the node as a leaf
        $labelCounts = array_count_values(array_column($groupedData, $labelAttribute));
        $mostFrequentLabel = array_keys($labelCounts)[0]; // Assuming the most frequent label as the prediction
        $rootNode = new DecisionTreeNodeController();
        $rootNode->setAsLeaf($mostFrequentLabel);
        return $rootNode;
    }
}

    // Function to fetch and process the dataset for tree construction
    public function fetchTreeDataset1()
    {
        $data = Dataset1::all()->toArray();
        $attributes = ["usia_group", "usia_group_median", "berat_badan_per_usia", "tinggi_badan_per_usia"];
        $labelAttribute = "berat_badan_per_tinggi_badan"; // Specify the label attribute
        $rootNodeData = $this->processNode($data, $attributes, $labelAttribute);
        return response()->json($rootNodeData);
    }
}
