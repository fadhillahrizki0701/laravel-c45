<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;
use App\Models\Dataset1;
use InvalidArgumentException;

class C45Controller extends Controller
{
    // Function to calculate the mean of a given attribute
    private function calculateMean($data, $attribute)
    {
        $values = array_column($data, $attribute);
        return array_sum($values) / count($values);
    }

    // Function to calculate the median of a given attribute
    private function calculateMedian($data, $attribute)
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
    private function groupByCustomAttributes($data, $mean, $median)
    {
        foreach ($data as &$row) {
            if ($row['usia'] <= $mean) {
                $row['usia_group_mean'] = 'below_mean';
            } else {
                $row['usia_group_mean'] = 'above_mean';
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
    private function calculateEntropy($data, $labelAttribute)
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
    private function calculateSplitInfo($data, $attribute)
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
    private function calculateGain($data, $attribute, $labelAttribute)
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
    private function calculateGainRatio($data, $attribute, $labelAttribute)
    {
        $gain = $this->calculateGain($data, $attribute, $labelAttribute);
        $splitInfo = $this->calculateSplitInfo($data, $attribute);

        if ($splitInfo == 0) {
            return 0; // Avoid division by zero
        }

        return $gain / $splitInfo;
    }

    // Main function to process the node based on given data and attributes
    private function processNode($data, $attributes, $labelAttribute)
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

        // Add the best attribute to the output
        $output['best_attribute'] = $bestAttribute;

        return $output;
    }

    /**
     * Filters a dataset based on a specific attribute and comparison operator.
     *
     * @param array $data The dataset to filter.
     * @param string $attribute The attribute to compare.
     * @param string|int|float $filter The value to compare against.
     * @param string $operator The comparison operator (default: 'strict equal'). Available operators:
     *     - 'strict equal'
     *     - 'equal'
     *     - 'not equal'
     *     - 'strict not equal'
     *     - 'greater than'
     *     - 'greater than or equal'
     *     - 'less than'
     *     - 'less than or equal'
     * @return array The filtered dataset.
     * @throws InvalidArgumentException If an invalid operator or filter type is provided.
     */
    private function filterDataOnAttribute(array $data, string $attribute, string|int|float $filter, string $operator = 'strict equal'): array
    {
        if (!is_string($filter) && !is_numeric($filter)) {
            throw new InvalidArgumentException('Filter must be a string, integer, or float.');
        }

        return array_filter($data, function ($child) use ($attribute, $filter, $operator) {
            switch ($operator) {
                case 'strict equal':
                    return strtolower($child[$attribute]) === (is_string($filter) ? strtolower($filter) : $filter);
                case 'equal':
                    return strtolower($child[$attribute]) == (is_string($filter) ? strtolower($filter) : $filter);
                case 'not equal':
                    return strtolower($child[$attribute]) != (is_string($filter) ? strtolower($filter) : $filter);
                case 'strict not equal':
                    return strtolower($child[$attribute]) !== (is_string($filter) ? strtolower($filter) : $filter);
                case 'greater than':
                    return $child[$attribute] > $filter;
                case 'greater than or equal':
                    return $child[$attribute] >= $filter;
                case 'less than':
                    return $child[$attribute] < $filter;
                case 'less than or equal':
                    return $child[$attribute] <= $filter;
                default:
                    throw new InvalidArgumentException('Invalid operator: ' . $operator);
            }
        });
    }

    private function defineMeanOnAttribute(array $data, string $attribute): int|float
    {
        return array_sum(array_column($data, $attribute)) / count(array_column($data, $attribute));
    }

    private function defineMedianOnAttribute(array $data, string $attribute): int|float
    {
        $values = array_column($data, $attribute);
        $total = count($values);
        sort($values);

        return ($total % 2 === 0) ? ($values[$total / 2 - 1] + $values[$total / 2]) / 2 : $values[floor($total / 2)];
    }

    private function resetArrayKeys(array $data): array
    {
        return array_values($data);
    }

    private function mergeDataOnAttribute(string $key = 'id', array ...$data): array
    {
        $_ = array_merge(...$data);

        // Sort merged array based on their corresponding $key value
        usort($_, function ($a, $b) use ($key) {
            return $a[$key] <=> $b[$key];
        });

        $_ = $this->resetArrayKeys($_);

        return $_;
    }

    private function entropy(array $data, string $labelAttribute): float
    {
        $total = count($data);
        $labels = array_column($data, 'berat_badan_per_tinggi_badan');
        $labelCounts = array_count_values($labels);
        $entropy = 0.0;

        foreach ($labelCounts as $count) {
            $probability = $count / $total;
            $entropy -= $probability * log($probability, 2);
        }

        return round($entropy, 10);
    }

    private function gain(array $data, string $label, string $attribute)
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

        $subsetEntropy = round($subsetEntropy, 10);

        return round($parentEntropy - $subsetEntropy, 10);
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

        // Berat Badan
        $weightNormal = $this->filterDataOnAttribute($data, 'berat_badan_per_usia', 'normal');
        $weightLess = $this->filterDataOnAttribute($data, 'berat_badan_per_usia', 'kurang');
        $weightLesser = $this->filterDataOnAttribute($data, 'berat_badan_per_usia', 'sangat kurang');
        
        // Tinggi Badan
        $heightNormal = $this->filterDataOnAttribute($data, 'tinggi_badan_per_usia', 'normal');
        $heightLess = $this->filterDataOnAttribute($data, 'tinggi_badan_per_usia', 'pendek');
        $heightLesser = $this->filterDataOnAttribute($data, 'tinggi_badan_per_usia', 'sangat pendek');

        // Usia
        $mean = $this->defineMeanOnAttribute($data, 'usia');
        $median = $this->defineMedianOnAttribute($data, 'usia');

        // Mean
        $belowOrEqualMean = $this->filterDataOnAttribute($data, 'usia', $mean, 'less than or equal');
        $aboveMean = $this->filterDataOnAttribute($data, 'usia', $mean, 'greater than');

        // Median
        $belowOrEqualMedian = $this->filterDataOnAttribute($data, 'usia', $median, 'less than or equal');
        $aboveMedian = $this->filterDataOnAttribute($data, 'usia', $median, 'greater than');
        
        $attributeKeys = [
            'berat badan',
            'tinggi badan',
            'usia',
        ];

        $weights = [
            $attributeKeys[0] => [
                'normal' => $this->resetArrayKeys($weightNormal),
                'kurang' => $this->resetArrayKeys($weightLess),
                'sangat kurang' => $this->resetArrayKeys($weightLesser),
            ]
        ];
        $heights = [
            $attributeKeys[1] => [
                'normal' => $this->resetArrayKeys($heightNormal),
                'pendek' => $this->resetArrayKeys($heightLess),
                'sangat pendek' => $this->resetArrayKeys($heightLesser),
            ]
        ];
        $ages = [
            $attributeKeys[2] => [
                '<= mean' => $this->resetArrayKeys($belowOrEqualMean),
                '> mean' => $this->resetArrayKeys($aboveMean),
                '<= median' => $this->resetArrayKeys($belowOrEqualMedian),
                '> median' => $this->resetArrayKeys($aboveMedian),
            ]
        ];

        $filteredData = [
            $weights,
            $heights,
            $ages,
        ];

        $merged = $this->mergeDataOnAttribute(
            'id',
            $filteredData[0]['berat badan']['normal'],
            $filteredData[0]['berat badan']['kurang'],
            $filteredData[0]['berat badan']['sangat kurang'],
        );

        dd($this->gain($merged, 'berat_badan_per_tinggi_badan', 'berat_badan_per_usia'));

        // dd($filteredData[0]['berat badan']);
        // dd($parentEntropy, $values);
    }
}