<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\C45\C45Controller;
use App\Models\Dataset2;
use PhpOffice\PhpSpreadsheet\IOFactory as PhpSpreadsheet;

class Datatest2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Dataset2::all()->isEmpty()) {
            return view("pages.datatrain2-index", [
                'accuracy' => [
                    'data' => [
                        'train' => [],
                        'test' => [],
                    ]
                ]
            ]);
        }

        $c45 = new C45Controller();
        $tree = $c45->fetchTreeDataset2Internal();

        $data = Dataset2::select([
            'usia',
            'berat_badan_per_tinggi_badan',
            'menu',
            'keterangan'
        ])->get()->toArray();

        $accuracy = $c45->calculate($data, [
            'usia',
            'berat_badan_per_tinggi_badan',
            'menu',
        ], 'keterangan', 0.73);

        $rules = $c45->extractRules($tree);

        return view("pages.datatest2-index", compact(
            'accuracy',
            'rules',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $c45 = new C45Controller();
        $tree = $c45->fetchTreeDataset2Internal();
        $data = Dataset2::select([
            'usia',
            'berat_badan_per_tinggi_badan',
            'menu',
            'keterangan'
        ])->get()->toArray();

        $accuracy = $c45->calculate($data, [
            'usia',
            'berat_badan_per_tinggi_badan',
            'menu',
        ], 'keterangan', 0.73);

        $rules = $c45->extractRules($tree);

        if (!$request->hasFile("file")) {
            $data = $this->validate($request, [
                "usia" => "required",
                "berat_badan_per_tinggi_badan" => "required",
                "menu" => "required",
            ]);

            // Prediksi label berdasarkan pohon keputusan
            $predictedLabel = $c45->predict($tree, $data);

            // Tampilkan hasil prediksi di view
            return view('pages.datatest2-index', compact(
                'predictedLabel',
                'data',
                'accuracy',
            ));
        }

        // Save the uploaded file temporarily without saving its path in the model
        $path = $request->file("file")->storeAs("temp", "uploaded_file.xlsx");

        // Read the Excel file that has been uploaded
        $spreadsheet = PhpSpreadsheet::load(storage_path("app/" . $path));
        $worksheet = $spreadsheet->getActiveSheet();

        $classificationResults = [];

        // Iterate over each row in the worksheet
        foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
            // Skip header (assuming first row is the header)
            if ($rowIndex == 1) {
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Ensure the row has at least 4 columns
            if (count($rowData) < 4) {
                continue; // Skip invalid rows
            }

            // Classify the data using your existing classification logic
            $data = [
                "usia" => ucwords($rowData[1]),
                "berat_badan_per_tinggi_badan" => ucwords($rowData[2]),
                "menu" => ucwords($rowData[3]),
            ];

            // Assuming you have a method to classify the data, e.g., `predict()`
            $predictedLabel = $c45->predict($tree, $data);

            // Store the results to display in a table
            $classificationResults[] = [
                "usia" => ucwords($rowData[1]),
                "berat_badan_per_tinggi_badan" => ucwords($rowData[2]),
                "menu" => ucwords($rowData[3]),
                "predicted_label" => $predictedLabel,
            ];
        }

        // Pass the classification results to the view
        return view('pages.datatest2-index', [
            'predictedLabels' => $classificationResults,
            'accuracy' => $accuracy,
            'rules' => $rules,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
