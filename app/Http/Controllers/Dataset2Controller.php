<?php

namespace App\Http\Controllers;

use App\Models\Dataset2;
use Illuminate\Http\Request;

class Dataset2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataset2 = Dataset2::all();

        return view('dashboard-dataset2', compact('dataset2'));
    }

    /** 
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard-dataset2');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $this->validate($request,[
            'Usia' => 'required',
            'berat_badan_per_tinggi_badan' => 'required',
            'Menu' => 'required',
            'Keterangan' => 'required',
        ]);

        Dataset2::create([
            'Usia' => $request->Usia,
            'berat_badan_per_tinggi_badan' => $request->berat_badan_per_tinggi_badan,
            'Menu' => $request->Menu,
            'Keterangan' => $request->Keterangan,
          ]);
        
 
        return redirect('/dataset2')->with('success', 'Data berhasil ditambahkan!');
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
        $dataset2= Dataset2::find($id);
        return view('dataset2-edit', compact('dataset2'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Validasi input
         $this->validate($request,[
            'Usia' => 'required',
            'berat_badan_per_tinggi_badan' => 'required',
            'Menu' => 'required',
            'Keterangan' => 'required',
        ]);

        $dataset2= Dataset2::find($id);
        $dataset2->update([
            'Usia' => $request->Usia,
            'berat_badan_per_tinggi_badan' => $request->berat_badan_per_tinggi_badan,
            'Menu' => $request->Menu,
            'Keterangan' => $request->Keterangan,
          ]);
        
 
        return redirect('/dataset2')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dataset2= Dataset2::find($id);
        $dataset2->delete();

        
        return redirect('/dataset2')->with('success', 'Data berhasil dihapus!');

    }
}
