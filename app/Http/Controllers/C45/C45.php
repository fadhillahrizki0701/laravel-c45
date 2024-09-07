<?php

namespace App\Http\Controllers\C45;

use InvalidArgumentException;

class C45
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
    public function filterDataOnAttribute(array $data, string $attribute, string|int|float $filter, string $operator = 'strict equal'): array
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

    public function defineMeanOnAttribute(array $data, string $attribute): int|float
    {
        return array_sum(array_column($data, $attribute)) / count(array_column($data, $attribute));
    }

    public function defineMedianOnAttribute(array $data, string $attribute): int|float
    {
        $values = array_column($data, $attribute);
        $total = count($values);
        sort($values);

        return ($total % 2 === 0) ? ($values[$total / 2 - 1] + $values[$total / 2]) / 2 : $values[floor($total / 2)];
    }

    public function resetArrayKeys(array $data): array
    {
        return array_values($data);
    }

    public function mergeDataOnAttribute(string $key = 'id', array ...$data): array
    {
        $_ = array_merge(...$data);

        // Sort merged array based on their corresponding $key value
        usort($_, function ($a, $b) use ($key) {
            return $a[$key] <=> $b[$key];
        });

        $_ = $this->resetArrayKeys($_);

        return $_;
    }

    public function entropy(array $data, string $labelAttribute = 'berat_badan_per_tinggi_badan'): float
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

    public function gain(array $data, string $label, string $attribute): float
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

    public function gainOnNumericalWithComparison(array $data, array $filteredData, string $label = 'berat_badan_per_tinggi_badan', string $attribute, array $comparisons = [
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
}
