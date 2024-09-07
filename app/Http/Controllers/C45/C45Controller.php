<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;
use App\Models\Dataset1;
use InvalidArgumentException;

class C45Controller extends Controller
{
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

    private function entropy(array $data, string $labelAttribute = 'berat_badan_per_tinggi_badan'): float
    {
        $total = count($data);
        $labels = array_column($data, $labelAttribute);
        $labelCounts = array_count_values($labels);
        $entropy = 0.0;

        foreach ($labelCounts as $count) {
            $probability = $count / $total;
            $entropy -= $probability * log($probability, 2);
        }

        return round($entropy, 10);
    }

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

        $subsetEntropy = round($subsetEntropy, 10);

        return round($parentEntropy - $subsetEntropy, 10);
    }

    private function gainOnNumericalWithComparison(array $data, array $filteredData, string $label = 'berat_badan_per_tinggi_badan', string $attribute, array $comparisons = [
        '<= mean',
        '> mean',
    ]): float
    {
        $parentEntropy = $this->entropy($data, $label);
        $subsetEntropy = 0.0;

        foreach ($comparisons as $comparison) {
            $subset = $filteredData[$attribute][$comparison];
            $subsetProbability = count($subset) / count($data);
            $subsetEntropy += $subsetProbability * $this->entropy($subset, $label);
        }

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
            $attributeKeys[0] => $weights[$attributeKeys[0]],
            $attributeKeys[1] => $heights[$attributeKeys[1]],
            $attributeKeys[2] => $ages[$attributeKeys[2]],
        ];

        $weightsGain = $this->gain(
            $this->mergeDataOnAttribute(
                'id',
                $filteredData['berat badan']['normal'],
                $filteredData['berat badan']['kurang'],
                $filteredData['berat badan']['sangat kurang'],
            ),
            'berat_badan_per_tinggi_badan',
            'berat_badan_per_usia'
        );
        $heightsGain = $this->gain(
            $this->mergeDataOnAttribute(
                'id',
                $filteredData['tinggi badan']['normal'],
                $filteredData['tinggi badan']['pendek'],
                $filteredData['tinggi badan']['sangat pendek'],
            ),
            'berat_badan_per_tinggi_badan',
            'tinggi_badan_per_usia'
        );

        $a_1 = $this->gainOnNumericalWithComparison($data, $filteredData, 'berat_badan_per_tinggi_badan', 'usia', [
            '<= mean',
            '> mean',
        ]);
        $a_2 = $this->gainOnNumericalWithComparison($data, $filteredData, 'berat_badan_per_tinggi_badan', 'usia', [
            '<= median',
            '> median',
        ]);

        dd($a_1, $a_2);
    }
}