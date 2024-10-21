<?php

namespace App\Http\Controllers;

use App\Models\Dataset1;
use App\Models\DatasetFileUpload1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory as PhpSpreadsheet;

class DatasetFileUpload1Controller extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile("file")) {
			// Save the uploaded file into the 'uploads' directory
			$path = $request->file("file")->store("uploads");

			// Save the file path into the Datatrain1 table
			DatasetFileUpload1::create([
				"path" => $path,
			]);

			// Read the Excel file that has been saved
			$spreadsheet = PhpSpreadsheet::load(storage_path("app/" . $path));
			$worksheet = $spreadsheet->getActiveSheet();

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

				// Save data to the database
				Dataset1::create([
					"nama" => str_replace("\x00", '', $rowData[1]),
					"usia" => $rowData[2],
					"berat_badan_per_usia" => ucwords($rowData[3]),
					"tinggi_badan_per_usia" => ucwords($rowData[4]),
					"berat_badan_per_tinggi_badan" => ucwords($rowData[5]),
				]);
			}
		}

		return redirect()
			->back()
			->with(["success" => "Data berhasil diimpor dan disimpan ke database."]);
    }

    /**
     * Remove the resources from storage.
     */
    public function clear()
    {
        // Retrieve all records from the Datatrain1 table
		$datasetFileUpload = DatasetFileUpload1::all();

		foreach ($datasetFileUpload as $dfus) {
			// Load the spreadsheet file using PhpSpreadsheet
			$spreadsheet = PhpSpreadsheet::load(
				storage_path("app/" . $dfus->path)
			);

			// Select the first worksheet
			$sheet = $spreadsheet->getActiveSheet();

			// Iterate through each row, starting from the second row to skip the header
			foreach ($sheet->getRowIterator(2) as $row) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

				$rowData = [];
				foreach ($cellIterator as $cell) {
					$rowData[] = $cell->getValue();
				}

				// Ensure the row has at least 5 columns (adjust based on your requirements)
				if (count($rowData) < 5) {
					continue; // Skip invalid rows
				}

				// Delete matching data in Dataset1
				Dataset1::where("nama", $rowData[1])
					->where("usia", $rowData[2])
					->where("berat_badan_per_usia", $rowData[3])
					->where("tinggi_badan_per_usia", $rowData[4])
					->where("berat_badan_per_tinggi_badan", $rowData[5])
					->delete();
			}

			// Delete the file from storage after processing
			if (Storage::exists($dfus->path)) {
				Storage::delete($dfus->path);
			}

			// Delete the record from Datatrain1
			$dfus->delete();
		}

		return redirect()
			->back()
			->with([
				"success",
				"All files and associated data deleted successfully.",
			]);
    }
}
