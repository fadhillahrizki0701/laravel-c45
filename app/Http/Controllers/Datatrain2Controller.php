<?php

namespace App\Http\Controllers;

use App\Models\Dataset2;
use App\Models\Datatrain2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory as PhpSpreadsheet;

class Datatrain2Controller extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$dataset2 = Dataset2::all();

		return view("pages.datatrain2-index", compact("dataset2"));
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
		if ($request->hasFile("file")) {
			// Save the uploaded file into the 'uploads' directory
			$path = $request->file("file")->store("uploads");

			// Save the file path into the Datatrain2 table
			Datatrain2::create([
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

				// Ensure the row has at least 4 columns
				if (count($rowData) < 4) {
					continue; // Skip invalid rows
				}

				// Save data to the database
				Dataset2::create([
					"usia" => $rowData[1],
					"berat_badan_per_tinggi_badan" => ucwords($rowData[2]),
					"menu" => ucwords($rowData[3]),
					"keterangan" => ucwords($rowData[4]),
				]);
			}
		}

		return redirect()
			->back()
			->with(["success", "Data imported and file saved successfully."]);
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
	public function destroy($id)
	{
		//
	}

	public function clear()
	{
		// Retrieve all records from the Datatrain2 table
		$dataTrains = Datatrain2::all();

		foreach ($dataTrains as $dataTrain) {
			// Load the spreadsheet file using PhpSpreadsheet
			$spreadsheet = PhpSpreadsheet::load(
				storage_path("app/" . $dataTrain->path)
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

				// Ensure the row has at least 4 columns (adjust based on your requirements)
				if (count($rowData) < 4) {
					continue; // Skip invalid rows
				}

				// Delete matching data in Dataset2
				Dataset2::where("usia", $rowData[1])
					->where("berat_badan_per_tinggi_badan", $rowData[2])
					->where("menu", $rowData[3])
					->where("keterangan", $rowData[4])
					->delete();
			}

			// Delete the file from storage after processing
			if (Storage::exists($dataTrain->path)) {
				Storage::delete($dataTrain->path);
			}

			// Delete the record from Datatrain2
			$dataTrain->delete();
		}

		return redirect()
			->back()
			->with([
				"success",
				"All files and associated data deleted successfully.",
			]);
	}
}
