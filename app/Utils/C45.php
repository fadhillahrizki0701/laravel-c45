<?php
namespace App\Utils;

class C45
{
    private $data;
    private $attributes;
    private $target;

    public function __construct($data, $attributes, $target)
    {
        $this->data = $data;
        $this->attributes = $attributes;
        $this->target = $target;
    }

    public function calculateEntropy($data)
    {
        $total = count($data);
        $counts = array_count_values(array_column($data, $this->target));

        $entropy = 0.0;
        foreach ($counts as $count) {
            $p = $count / $total;
            $entropy -= $p * log($p, 2);
        }

        return $entropy;
    }

    public function calculateGain($data, $attribute)
    {
        $total = count($data);
        $values = array_unique(array_column($data, $attribute));

        $weightedEntropy = 0.0;
        foreach ($values as $value) {
            $subset = array_filter($data, function ($row) use ($attribute, $value) {
                return $row[$attribute] == $value;
            });

            $weightedEntropy += (count($subset) / $total) * $this->calculateEntropy($subset);
        }

        return $this->calculateEntropy($data) - $weightedEntropy;
    }

    public function findBestAttribute($data, $attributes)
    {
        $bestGain = 0.0;
        $bestAttribute = null;

        foreach ($attributes as $attribute) {
            $gain = $this->calculateGain($data, $attribute);
            if ($gain > $bestGain) {
                $bestGain = $gain;
                $bestAttribute = $attribute;
            }
        }

        return $bestAttribute;
    }

    public function buildTree($data, $attributes)
    {
        if (count(array_unique(array_column($data, $this->target))) === 1) {
            return array_values(array_unique(array_column($data, $this->target)))[0];
        }

        if (empty($attributes)) {
            return array_values(array_count_values(array_column($data, $this->target)))[0];
        }

        $bestAttribute = $this->findBestAttribute($data, $attributes);
        $tree = [$bestAttribute => []];

        $values = array_unique(array_column($data, $bestAttribute));
        foreach ($values as $value) {
            $subset = array_filter($data, function ($row) use ($bestAttribute, $value) {
                return $row[$bestAttribute] == $value;
            });

            $subset = array_map(function ($row) use ($bestAttribute) {
                unset($row[$bestAttribute]);
                return $row;
            }, $subset);

            $tree[$bestAttribute][$value] = $this->buildTree($subset, array_diff($attributes, [$bestAttribute]));
        }

        return $tree;
    }
}