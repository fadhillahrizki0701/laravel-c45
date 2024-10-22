<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\C45\C45Controller;
use App\Models\Dataset1;
use App\Models\DataTest1;
use App\Models\Datatrain1;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory as PhpSpreadsheet;

class Datatest1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (DataTest1::all()->isEmpty()) {
			return view("pages.datatrain2-index", [
				'data' => [],
				'metrices' => [
					'accuracy' => 0,
					'precision' => 0,
					'recall' => 0,
					'f1_score' => 0,
				],
			]);
		}

		$c45 = new C45Controller();

        $dataTrain = Datatrain1::select([
			'usia',
			'berat_badan_per_usia',
			'tinggi_badan_per_usia',
			'berat_badan_per_tinggi_badan',
		])->get()->toArray();

		$tree = $c45->fetchTree($dataTrain, [
			'usia',
			'berat_badan_per_usia',
			'tinggi_badan_per_usia',
			'berat_badan_per_tinggi_badan',
		], 'berat_badan_per_tinggi_badan');

		$data = DataTest1::select([
			'nama',
			'usia',
			'berat_badan_per_usia',
			'tinggi_badan_per_usia',
			'berat_badan_per_tinggi_badan',
		])->get()->toArray();

		$metrices = $c45->calculateMetricesWithTreeInput($data, [
			'berat_badan_per_usia',
			'tinggi_badan_per_usia',
			'usia',
		], 'berat_badan_per_tinggi_badan', $tree);

		$rules = $c45->extractRules($tree);

		return view("pages.datatest1-index", compact(
			'metrices',
			'rules',
            'data',
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
        $tree = $c45->fetchTreeDataset1Internal();
        $data = Dataset1::select([
            'nama',
            'usia',
            'berat_badan_per_usia',
            'tinggi_badan_per_usia',
            'berat_badan_per_tinggi_badan',
        ])->get()->toArray();

        $metrices = $c45->calculate($data, [
            'berat_badan_per_usia',
            'tinggi_badan_per_usia',
            'usia',
        ], 'berat_badan_per_tinggi_badan', 0.72);

        $rules = $c45->extractRules($tree);

        if (!$request->hasFile("file")) {
            $data = $this->validate($request, [
                "nama" => "nullable",
                "usia" => "required",
                "berat_badan_per_usia" => "required",
                "tinggi_badan_per_usia" => "required",
            ]);

            // Prediksi label berdasarkan pohon keputusan
            $predictedLabel = $c45->predict($tree, $data);

            // Tampilkan hasil prediksi di view
            return view('pages.datatest1-index', compact(
                'predictedLabel',
                'data',
                'rules',
                'metrices',
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

            // Ensure the row has at least 5 columns and 'nama' is not null
            if (count($rowData) < 5 || empty($rowData[1])) {
                continue; // Skip invalid rows
            }

            // Classify the data using your existing classification logic
            $data = [
                "usia" => ucwords($rowData[2]),
                "berat_badan_per_usia" => ucwords($rowData[3]),
                "tinggi_badan_per_usia" => ucwords($rowData[4]),
            ];

            // Assuming you have a method to classify the data, e.g., `predict()`
            $predictedLabel = $c45->predict($tree, $data);

            // Store the results to display in a table
            $classificationResults[] = [
                "nama" => $rowData[1],
                "usia" => ucwords($rowData[2]),
                "berat_badan_per_usia" => ucwords($rowData[3]),
                "tinggi_badan_per_usia" => ucwords($rowData[4]),
                "predicted_label" => $predictedLabel,
            ];
        }

        // Pass the classification results to the view
        return view('pages.datatest1-index', [
            'predictedLabels' => $classificationResults,
            'metrices' => $metrices,
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
