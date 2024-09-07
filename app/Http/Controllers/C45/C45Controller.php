<?php

namespace App\Http\Controllers\C45;

use App\Http\Controllers\Controller;
use App\Models\Dataset1;

class C45Controller extends Controller
{
    // Function to fetch and process the dataset for tree construction
    public function fetchTreeDataset1()
    {
        $c45 = new C45();

        $data = Dataset1::select([
            'id',
            'usia',
            'berat_badan_per_usia',
            'tinggi_badan_per_usia',
            'berat_badan_per_tinggi_badan',
        ])->get()->toArray();

        // Berat Badan
        $weightNormal = $c45->filterDataOnAttribute($data, 'berat_badan_per_usia', 'normal');
        $weightLess = $c45->filterDataOnAttribute($data, 'berat_badan_per_usia', 'kurang');
        $weightLesser = $c45->filterDataOnAttribute($data, 'berat_badan_per_usia', 'sangat kurang');
        
        // Tinggi Badan
        $heightNormal = $c45->filterDataOnAttribute($data, 'tinggi_badan_per_usia', 'normal');
        $heightLess = $c45->filterDataOnAttribute($data, 'tinggi_badan_per_usia', 'pendek');
        $heightLesser = $c45->filterDataOnAttribute($data, 'tinggi_badan_per_usia', 'sangat pendek');

        // Usia
        $mean = $c45->defineMeanOnAttribute($data, 'usia');
        $median = $c45->defineMedianOnAttribute($data, 'usia');

        // Mean
        $belowOrEqualMean = $c45->filterDataOnAttribute($data, 'usia', $mean, 'less than or equal');
        $aboveMean = $c45->filterDataOnAttribute($data, 'usia', $mean, 'greater than');

        // Median
        $belowOrEqualMedian = $c45->filterDataOnAttribute($data, 'usia', $median, 'less than or equal');
        $aboveMedian = $c45->filterDataOnAttribute($data, 'usia', $median, 'greater than');
        
        $attributeKeys = [
            'berat badan',
            'tinggi badan',
            'usia',
        ];

        $weights = [
            $attributeKeys[0] => [
                'normal' => $c45->resetArrayKeys($weightNormal),
                'kurang' => $c45->resetArrayKeys($weightLess),
                'sangat kurang' => $c45->resetArrayKeys($weightLesser),
            ]
        ];
        $heights = [
            $attributeKeys[1] => [
                'normal' => $c45->resetArrayKeys($heightNormal),
                'pendek' => $c45->resetArrayKeys($heightLess),
                'sangat pendek' => $c45->resetArrayKeys($heightLesser),
            ]
        ];
        $ages = [
            $attributeKeys[2] => [
                '<= mean' => $c45->resetArrayKeys($belowOrEqualMean),
                '> mean' => $c45->resetArrayKeys($aboveMean),
                '<= median' => $c45->resetArrayKeys($belowOrEqualMedian),
                '> median' => $c45->resetArrayKeys($aboveMedian),
            ]
        ];

        $filteredData = [
            $attributeKeys[0] => $weights[$attributeKeys[0]],
            $attributeKeys[1] => $heights[$attributeKeys[1]],
            $attributeKeys[2] => $ages[$attributeKeys[2]],
        ];

        $weightsGain = $c45->gain(
            $c45->mergeDataOnAttribute(
                'id',
                $filteredData['berat badan']['normal'],
                $filteredData['berat badan']['kurang'],
                $filteredData['berat badan']['sangat kurang'],
            ),
            'berat_badan_per_tinggi_badan',
            'berat_badan_per_usia'
        );
        $heightsGain = $c45->gain(
            $c45->mergeDataOnAttribute(
                'id',
                $filteredData['tinggi badan']['normal'],
                $filteredData['tinggi badan']['pendek'],
                $filteredData['tinggi badan']['sangat pendek'],
            ),
            'berat_badan_per_tinggi_badan',
            'tinggi_badan_per_usia'
        );
        $ageMeanGain = $c45->gainOnNumericalWithComparison($data, $filteredData, 'berat_badan_per_tinggi_badan', 'usia', [
            '<= mean',
            '> mean',
        ]);
        $ageMedianGain = $c45->gainOnNumericalWithComparison($data, $filteredData, 'berat_badan_per_tinggi_badan', 'usia', [
            '<= median',
            '> median',
        ]);
        $highestGain = max($weightsGain, $heightsGain, $ageMeanGain, $ageMedianGain);

        $tree = [];
        if ($highestGain == $weightsGain) {
            $tree[0] = [
                'parentNode' => null,
                'node' => 'Berat Badan',
                'attribute' => null,
                'isLeaf' => false,
            ];

            if ($c45->entropy($filteredData['berat badan']['normal']) == 0) {
                $_leaf_1 = array_unique(array_column($filteredData['berat badan']['normal'], 'berat_badan_per_tinggi_badan'))[0];

                $tree[0]['children'][] = [
                    'parentNode' => $tree[0]['node'],
                    'node' => $_leaf_1,
                    'attribute' => 'Normal',
                    'isLeaf' => true,
                ];
            }

            if ($c45->entropy($filteredData['berat badan']['kurang']) == 0) {
                $_leaf_2 = array_unique(array_column($filteredData['berat badan']['kurang'], 'berat_badan_per_tinggi_badan'));

                $tree[0]['children'][] = [
                    'name' => $_leaf_2,
                    'attribute' => 'Kurang',
                ];
            } else {
                $v = $filteredData['berat badan']['kurang'];
                $mean = $c45->defineMeanOnAttribute($v, 'usia');
                $median = $c45->defineMedianOnAttribute($v, 'usia');

                $heightsGain = $c45->gain(
                    $c45->mergeDataOnAttribute(
                        'id',
                        $filteredData['tinggi badan']['normal'],
                        $filteredData['tinggi badan']['pendek'],
                        $filteredData['tinggi badan']['sangat pendek'],
                    ),
                    'berat_badan_per_tinggi_badan',
                    'tinggi_badan_per_usia'
                );

                // Berat Badan
                $weightNormal = $c45->filterDataOnAttribute($v, 'berat_badan_per_usia', 'normal');
                $weightLess = $c45->filterDataOnAttribute($v, 'berat_badan_per_usia', 'kurang');
                $weightLesser = $c45->filterDataOnAttribute($v, 'berat_badan_per_usia', 'sangat kurang');

                // Tinggi Badan
                $heightNormal = $c45->filterDataOnAttribute($v, 'tinggi_badan_per_usia', 'normal');
                $heightLess = $c45->filterDataOnAttribute($v, 'tinggi_badan_per_usia', 'pendek');
                $heightLesser = $c45->filterDataOnAttribute($v, 'tinggi_badan_per_usia', 'sangat pendek');

                // Usia
                $belowOrEqualMean = $c45->filterDataOnAttribute($v, 'usia', $mean, 'less than or equal');
                $aboveMean = $c45->filterDataOnAttribute($v, 'usia', $mean, 'greater than');
                $belowOrEqualMedian = $c45->filterDataOnAttribute($v, 'usia', $median, 'less than or equal');
                $aboveMedian = $c45->filterDataOnAttribute($v, 'usia', $median, 'greater than');

                $weights = [
                    $attributeKeys[0] => [
                        'normal' => $c45->resetArrayKeys($weightNormal),
                        'kurang' => $c45->resetArrayKeys($weightLess),
                        'sangat kurang' => $c45->resetArrayKeys($weightLesser),
                    ]
                ];
                $heights = [
                    $attributeKeys[1] => [
                        'normal' => $c45->resetArrayKeys($heightNormal),
                        'pendek' => $c45->resetArrayKeys($heightLess),
                        'sangat pendek' => $c45->resetArrayKeys($heightLesser),
                    ]
                ];
                $ages = [
                    $attributeKeys[2] => [
                        '<= mean' => $c45->resetArrayKeys($belowOrEqualMean),
                        '> mean' => $c45->resetArrayKeys($aboveMean),
                        '<= median' => $c45->resetArrayKeys($belowOrEqualMedian),
                        '> median' => $c45->resetArrayKeys($aboveMedian),
                    ]
                ];
                $filteredDataProcess2 = [
                    $attributeKeys[0] => $weights[$attributeKeys[0]],
                    $attributeKeys[1] => $heights[$attributeKeys[1]],
                    $attributeKeys[2] => $ages[$attributeKeys[2]],
                ];

                $weightsGain = $c45->gain($v, 'berat_badan_per_tinggi_badan', 'berat_badan_per_usia');
                $heightsGain = $c45->gain($v, 'berat_badan_per_tinggi_badan', 'tinggi_badan_per_usia');
                $ageMeanGain = $c45->gainOnNumericalWithComparison($v, $filteredDataProcess2, 'berat_badan_per_tinggi_badan', 'usia', [
                    '<= mean',
                    '> mean',
                ]);
                $ageMedianGain = $c45->gainOnNumericalWithComparison($v, $filteredDataProcess2, 'berat_badan_per_tinggi_badan', 'usia', [
                    '<= median',
                    '> median',
                ]);
                $highestGain = max($weightsGain, $heightsGain, $ageMeanGain, $ageMedianGain);

                if ($highestGain == $heightsGain) {
                    $tree[0]['children'][] = [
                        'parentNode' => $tree[0]['node'],
                        'node' => 'Tinggi Badan',
                        'attribute' => 'Kurang',
                        'isLeaf' => false,
                    ];
                    
                    if ($c45->entropy($filteredDataProcess2['tinggi badan']['normal']) == 0) {
                        $_v = array_unique(array_column($filteredDataProcess2['tinggi badan']['normal'], 'berat_badan_per_tinggi_badan'))[0];

                        $tree[0]['children'][1]['children'][] = [
                            'parentNode' => $tree[0]['children'][1]['node'],
                            'node' => $_v,
                            'attribute' => 'Normal',
                            'isLeaf' => true,
                        ];
                        // dd($tree);
                    }

                    if ($c45->entropy($filteredDataProcess2['tinggi badan']['pendek']) == 0) {
                        $__v = array_unique(array_column($filteredDataProcess2['tinggi badan']['normal'], 'berat_badan_per_tinggi_badan'))[0];

                        $tree[0]['children'][1]['children'][] = [
                            'parentNode' => $tree[0]['children'][1]['node'],
                            'node' => $__v,
                            'attribute' => 'Pendek',
                            'isLeaf' => true,
                        ];
                    }

                    if ($c45->entropy($filteredDataProcess2['tinggi badan']['sangat pendek']) == 0) {
                    } else {
                        $w = $c45->filterDataOnAttribute($v, 'tinggi_badan_per_usia', 'sangat pendek');
                        $mean = $c45->defineMeanOnAttribute($w, 'usia');
                        $median = $c45->defineMedianOnAttribute($w, 'usia');

                        $heightsGain = $c45->gain(
                            $c45->mergeDataOnAttribute(
                                'id',
                                $filteredDataProcess2['tinggi badan']['normal'],
                                $filteredDataProcess2['tinggi badan']['pendek'],
                                $filteredDataProcess2['tinggi badan']['sangat pendek'],
                            ),
                            'berat_badan_per_tinggi_badan',
                            'tinggi_badan_per_usia'
                        );

                        // Berat Badan
                        $weightNormal = $c45->filterDataOnAttribute($w, 'berat_badan_per_usia', 'normal');
                        $weightLess = $c45->filterDataOnAttribute($w, 'berat_badan_per_usia', 'kurang');
                        $weightLesser = $c45->filterDataOnAttribute($w, 'berat_badan_per_usia', 'sangat kurang');

                        // Tinggi Badan
                        $heightNormal = $c45->filterDataOnAttribute($w, 'tinggi_badan_per_usia', 'normal');
                        $heightLess = $c45->filterDataOnAttribute($w, 'tinggi_badan_per_usia', 'pendek');
                        $heightLesser = $c45->filterDataOnAttribute($w, 'tinggi_badan_per_usia', 'sangat pendek');

                        // Usia
                        $belowOrEqualMean = $c45->filterDataOnAttribute($w, 'usia', $mean, 'less than or equal');
                        $aboveMean = $c45->filterDataOnAttribute($w, 'usia', $mean, 'greater than');
                        $belowOrEqualMedian = $c45->filterDataOnAttribute($w, 'usia', $median, 'less than or equal');
                        $aboveMedian = $c45->filterDataOnAttribute($w, 'usia', $median, 'greater than');

                        $weights = [
                            $attributeKeys[0] => [
                                'normal' => $c45->resetArrayKeys($weightNormal),
                                'kurang' => $c45->resetArrayKeys($weightLess),
                                'sangat kurang' => $c45->resetArrayKeys($weightLesser),
                            ]
                        ];
                        $heights = [
                            $attributeKeys[1] => [
                                'normal' => $c45->resetArrayKeys($heightNormal),
                                'pendek' => $c45->resetArrayKeys($heightLess),
                                'sangat pendek' => $c45->resetArrayKeys($heightLesser),
                            ]
                        ];
                        $ages = [
                            $attributeKeys[2] => [
                                '<= mean' => $c45->resetArrayKeys($belowOrEqualMean),
                                '> mean' => $c45->resetArrayKeys($aboveMean),
                                '<= median' => $c45->resetArrayKeys($belowOrEqualMedian),
                                '> median' => $c45->resetArrayKeys($aboveMedian),
                            ]
                        ];
                        $filteredDataProcess2 = [
                            $attributeKeys[0] => $weights[$attributeKeys[0]],
                            $attributeKeys[1] => $heights[$attributeKeys[1]],
                            $attributeKeys[2] => $ages[$attributeKeys[2]],
                        ];

                        $weightsGain = $c45->gain($w, 'berat_badan_per_tinggi_badan', 'berat_badan_per_usia');
                        $heightsGain = $c45->gain($w, 'berat_badan_per_tinggi_badan', 'tinggi_badan_per_usia');
                        $ageMeanGain = $c45->gainOnNumericalWithComparison($w, $filteredDataProcess2, 'berat_badan_per_tinggi_badan', 'usia', [
                            '<= mean',
                            '> mean',
                        ]);
                        $ageMedianGain = $c45->gainOnNumericalWithComparison($w, $filteredDataProcess2, 'berat_badan_per_tinggi_badan', 'usia', [
                            '<= median',
                            '> median',
                        ]);

                        $highestGain = max($weightsGain, $heightsGain, $ageMeanGain, $ageMedianGain);

                        if ($highestGain == 1.0) {
                            $tree[0]['children'][1]['children'][] = [
                                'parentNode' => $tree[0]['children'][1]['node'],
                                'node' => 'Usia',
                                'attribute' => 'Sangat Pendek',
                                'isLeaf' => false,
                            ];

                            $tree[0]['children'][1]['children'][2]['children'] = [
                                [
                                    'parentNode' => $tree[0]['children'][1]['children'][2]['node'],
                                    'node' => 'Usia',
                                    'attribute' => "<= {$mean}",
                                    'isLeaf' => true,
                                ],
                                [
                                    'parentNode' => $tree[0]['children'][1]['children'][2]['node'],
                                    'node' => 'Usia',
                                    'attribute' => "> {$median}",
                                    'isLeaf' => true,
                                ],
                            ];
                        }
                    }
                }
            }

            if ($c45->entropy($filteredData['berat badan']['sangat kurang']) == 0) {
                $_leaf_3 = array_unique(array_column($filteredData['berat badan']['sangat kurang'], 'berat_badan_per_tinggi_badan'))[0];

                $tree[0]['children'][] = [
                    'parentNode' => $tree[0]['node'],
                    'node' => $_leaf_3,
                    'attribute' => 'Sangat Kurang',
                    'isLeaf' => true,
                ];
            }
        }

        dd($tree);
    }
}