<?php

namespace App\Http\Controllers;

use App\Models\Dataset1;
use App\Models\Dataset2;
use App\Models\Datatrain1;
use App\Models\Datatrain2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Datatrain2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataset2 = Dataset2::all();

        return view('dashboard-datatrain2', compact('dataset2'));
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
            Datatrain2::create([
                'path'=>$path
            ]);
            // Baca file yang telah disimpan
            $data = array_map(function($row) {
                return str_getcsv($row, ';');
            }, file(storage_path('app/' . $path)));
    
            // Skip the header
            array_shift($data);
    
            foreach ($data as $row) {
                Dataset2::create([
                    'Usia' => $row[1],
                    'berat_badan_per_tinggi_badan' => $row[2],
                    'Menu' => $row[3],
                    'Keterangan' => $row[4],
                ]);
            }
        }
    
        // return redirect()->back()->with('success', 'Data imported and file saved successfully.');
        return redirect()->route('datatrain2.index')->with('success',  'Data imported and file saved successfully.');
        
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
        // Temukan record di database berdasarkan ID
        $dataTrain = Datatrain2::findOrFail($id);
    
        // Hapus file dari storage
        if (Storage::exists($dataTrain->path)) {
            Storage::delete($dataTrain->path);
        }
    
        // Hapus data terkait dari Dataset2
        // Misalnya berdasarkan nama file, jika itu menjadi acuan
        Dataset2::where('Usia', 'like', '%' . basename($dataTrain->path) . '%')->delete();
    
        // Hapus record dari Datatrain1
        $dataTrain->delete();
    
        return redirect()->back()->with('success', 'File and associated data deleted successfully.');
    }
    

    public function clear(Request $request)
{
    // Ambil semua record dari tabel Datatrain2
    $dataTrains = Datatrain2::all();

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
            // if (count($row) < 4) {
            //     continue; // Lewati baris yang tidak valid
            // }

            // Validasi dan hapus data dari Dataset2 berdasarkan kecocokan
            Dataset2::where('Usia', $row[1])
                ->where('berat_badan_per_tinggi_badan', $row[2])
                ->where('Menu', $row[3])
                ->where('Keterangan', $row[4])
                ->delete();
        }

        // Hapus file dari storage setelah data dihapus
        if (Storage::exists($dataTrain->path)) {
            Storage::delete($dataTrain->path);
        }

        // Hapus record dari Datatrain2
        $dataTrain->delete();
    }

    return redirect()->back()->with('success', 'Matching data and files have been deleted successfully.');
}


    

}
