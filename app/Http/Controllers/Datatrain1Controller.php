<?php

namespace App\Http\Controllers;

use App\Models\Dataset1;
use App\Models\Datatrain1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Datatrain1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataset1 = Dataset1::all();

        return view('dashboard-datatrain1', compact('dataset1'));
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
        if ($request->hasFile('file')) {
            // Simpan file yang diunggah ke dalam direktori 'uploads'
            $path = $request->file('file')->store('uploads');
    
            // Baca file yang telah disimpan
            $data = array_map(function($row) {
                return str_getcsv($row, ';');
            }, file(storage_path('app/' . $path)));
    
            // Skip the header
            array_shift($data);
    
            foreach ($data as $row) {
                Dataset1::create([
                    'Nama' => $row[1],
                    'Usia' => $row[2],
                    'berat_badan_per_usia' => $row[3],
                    'tinggi_badan_per_usia' => $row[4],
                    'berat_badan_per_tinggi_badan' => $row[5],
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Data imported and file saved successfully.');
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
        
    }
    

    public function clear(Request $request)
    {
        // Ambil semua record dari tabel Datatrain1
        $dataTrains = Datatrain1::all();
    
        foreach ($dataTrains as $dataTrain) {
            // Baca file dari path yang tersimpan
            $data = array_map(function($row) {
                return str_getcsv($row, ';');
            }, file(storage_path('app/' . $dataTrain->path)));
    
            // Skip the header
            array_shift($data);
    
            // Iterasi setiap baris dari file yang diunggah
            foreach ($data as $row) {
                // Pastikan baris memiliki minimal 5 kolom
                if (count($row) < 5) {
                    continue; // Lewati baris yang tidak valid
                }
    
                // Validasi dan hapus data dari Dataset1 berdasarkan kecocokan
                Dataset1::where('Nama', $row[1])
                    ->where('Usia', $row[2])
                    ->where('berat_badan_per_usia', $row[3])
                    ->where('tinggi_badan_per_usia', $row[4])
                    ->where('berat_badan_per_tinggi_badan', $row[5]) // Nama kolom diperbaiki
                    ->delete();
            }
    
            // Hapus file dari storage setelah data dihapus
            if (Storage::exists($dataTrain->path)) {
                Storage::delete($dataTrain->path);
            }
    
            // Hapus record dari Datatrain1
            $dataTrain->delete();
        }
    
        return redirect()->back()->with('success', 'Matching data and files have been deleted successfully.');
    }
    

    

}
