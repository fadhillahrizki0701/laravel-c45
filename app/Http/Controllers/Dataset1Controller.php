<?php

namespace App\Http\Controllers;

use App\Models\Dataset1;
use Illuminate\Http\Request;

class Dataset1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataset1 = Dataset1::all();

        return view('dashboard-dataset1', compact('dataset1'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard-dataset1');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $this->validate($request,[
            'Nama' => 'required',
            'Usia' => 'required',
            'berat_badan_per_usia' => 'required',
            'tinggi_badan_per_usia' => 'required',
            'berat_badan_per_tinggi_badan' => 'required',
        ]);

        Dataset1::create([
            'Nama' => $request->Nama,
            'Usia' => $request->Usia,
            'berat_badan_per_usia' => $request->berat_badan_per_usia,
            'tinggi_badan_per_usia' => $request->tinggi_badan_per_usia,
            'berat_badan_per_tinggi_badan' => $request->berat_badan_per_tinggi_badan,
          ]);
        
 
        return redirect('/dataset1')->with('success', 'Data berhasil ditambahkan!');
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
        $dataset1= Dataset1::find($id);
       return view('dataset1-edit', compact('dataset1'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Validasi input
         $this->validate($request,[
            'Nama' => 'required',
            'Usia' => 'required',
            'berat_badan_per_usia' => 'required',
            'tinggi_badan_per_usia' => 'required',
            'berat_badan_per_tinggi_badan' => 'required',
        ]);

        $dataset1= Dataset1::find($id);
        $dataset1->update([
            'Nama' => $request->Nama,
            'Usia' => $request->Usia,
            'berat_badan_per_usia' => $request->berat_badan_per_usia,
            'tinggi_badan_per_usia' => $request->tinggi_badan_per_usia,
            'berat_badan_per_tinggi_badan' => $request->berat_badan_per_tinggi_badan,
          ]);
        
 
        return redirect('/dataset1')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dataset1= Dataset1::find($id);
        $dataset1->delete();

        
        return redirect('/dataset1')->with('success', 'Data berhasil dihapus!');

    }


    public function mining() {
        $dataset1 = Dataset1::all();
        // dd($dataset1->where('berat_badan_per_usia', 'Normal'),$dataset1->where('berat_badan_per_usia', 'Kurang'));
        $stop=false;

        foreach ($dataset1 as $key => $value) {
            if($stop){
                break;
            }
            // dd($key,$value);

            //total kasus
            $total_kasus=$value->count();
            $total_gizi_baik = $value->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang = $value->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_gizi = (float)$this->calculateEntropy($total_kasus, $total_gizi_baik, $total_gizi_kurang);

            //entropy bb/u normal
            $dataset1_bbu_normal =$value->where('berat_badan_per_usia', 'Normal');
            $total_kasus_bbu_normal = $dataset1_bbu_normal->count();
            $total_gizi_baik_bbu_normal =$value->where('berat_badan_per_usia', 'Normal')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang_bbu_normal =$value->where('berat_badan_per_usia', 'Normal')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_bbu_normal = (float)$this->calculateEntropy($total_kasus_bbu_normal, $total_gizi_baik_bbu_normal, $total_gizi_kurang_bbu_normal);
           
            //entropy bb/u kurang
            $dataset1_bbu_kurang = $value->where('berat_badan_per_usia', 'Kurang');
            $total_kasus_bbu_kurang = $dataset1_bbu_kurang->count();
            $total_gizi_baik_bbu_kurang = $value->where('berat_badan_per_usia', 'Kurang')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang_bbu_kurang = $value->where('berat_badan_per_usia', 'Kurang')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_bbu_kurang = (float)$this->calculateEntropy($total_kasus_bbu_kurang, $total_gizi_baik_bbu_kurang, $total_gizi_kurang_bbu_kurang);

            //entropy bb/u sangat kurang
            $dataset1_bbu_sangat_kurang =$value->where('berat_badan_per_usia', 'Sangat Kurang');
            $total_kasus_bbu_sangat_kurang = $dataset1_bbu_sangat_kurang->count();
            $total_gizi_baik_bbu_sangat_kurang =$value->where('berat_badan_per_usia', 'Sangat Kurang')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang_bbu_sangat_kurang =$value->where('berat_badan_per_usia', 'Sangat Kurang')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_bbu_sangat_kurang = (float)$this->calculateEntropy($total_kasus_bbu_sangat_kurang, $total_gizi_baik_bbu_sangat_kurang, $total_gizi_kurang_bbu_sangat_kurang);

            //gain bb/u
            $gain_bbu = $entropy_total_gizi - (
                (($total_kasus_bbu_normal / $total_kasus) * $entropy_total_bbu_normal) +
                (($total_kasus_bbu_kurang / $total_kasus) * $entropy_total_bbu_kurang) +
                (($total_kasus_bbu_sangat_kurang / $total_kasus) * $entropy_total_bbu_sangat_kurang)
            );
            $gain_bbu=round($gain_bbu,5);

            //entropy tb/u normal
            $dataset1_tbu_normal = $value->where('tinggi_badan_per_usia', 'Normal');
            $total_kasus_tbu_normal = $dataset1_tbu_normal->count();
            $total_gizi_baik_tbu_normal = $value->where('tinggi_badan_per_usia', 'Normal')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang_tbu_normal = $value->where('tinggi_badan_per_usia', 'Normal')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_tbu_normal = (float)$this->calculateEntropy($total_kasus_tbu_normal, $total_gizi_baik_tbu_normal, $total_gizi_kurang_tbu_normal);
           
           //entropy tb/u pendek
           $dataset1_tbu_pendek = $value->where('tinggi_badan_per_usia', 'Pendek');
           $total_kasus_tbu_pendek = $dataset1_tbu_pendek->count();
           $total_gizi_baik_tbu_pendek = $value->where('tinggi_badan_per_usia', 'Pendek')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
           $total_gizi_kurang_tbu_pendek = $value->where('tinggi_badan_per_usia', 'Pendek')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
           $entropy_total_tbu_pendek = (float)$this->calculateEntropy($total_kasus_tbu_pendek, $total_gizi_baik_tbu_pendek, $total_gizi_kurang_tbu_pendek);
   
           //entropy tb/u sangat pendek
            $dataset1_tbu_sangat_pendek =$value->where('tinggi_badan_per_usia', 'Sangat Pendek');
            $total_kasus_tbu_sangat_pendek = $dataset1_tbu_sangat_pendek->count();
            $total_gizi_baik_tbu_sangat_pendek =$value->where('tinggi_badan_per_usia', 'Sangat Pendek')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang_tbu_sangat_pendek =$value->where('tinggi_badan_per_usia', 'Sangat Pendek')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_tbu_sangat_pendek = (float)$this->calculateEntropy($total_kasus_tbu_sangat_pendek, $total_gizi_baik_tbu_sangat_pendek, $total_gizi_kurang_tbu_sangat_pendek);
    

                 // Perhitungan Gain tb/u
             $gain_tbu = $entropy_total_gizi - (
            (($total_kasus_tbu_normal / $total_kasus) * $entropy_total_tbu_normal) +
            (($total_kasus_tbu_pendek / $total_kasus) * $entropy_total_tbu_pendek) +
            (($total_kasus_tbu_sangat_pendek / $total_kasus) * $entropy_total_tbu_sangat_pendek)
             );

              $gain_tbu=round($gain_tbu,5);

             // dd($gain_bbu,$gain_tbu,$dataset1->avg('Usia'));
            $mean_umur= $dataset1->avg('Usia');
            $umur_values = $dataset1->pluck('Usia')->toArray();
            sort($umur_values);
            $count = count($umur_values);
            $median_umur=0;
            if ($count % 2 == 0) {
                $median_umur = ($umur_values[($count / 2) - 1] + $umur_values[$count / 2]) / 2;
            } else {
                $median_umur = $umur_values[floor($count / 2)];
            }

              //entropy usia dibawah mean
            $total_kasus_usia_dbwah_mean = $value->where('Usia', '<=', $mean_umur)->count();
            $total_gizi_baik_usia_dbwah_mean = $value->where('Usia', '<=', $mean_umur)->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang_usia_dbwah_mean = $value->where('Usia', '<=', $mean_umur)->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_usia_dbwah_mean = (float)$this->calculateEntropy($total_kasus_usia_dbwah_mean, $total_gizi_baik_usia_dbwah_mean, $total_gizi_kurang_usia_dbwah_mean);
          
             //entropy usia diatas mean
            $total_kasus_usia_diatas_mean = $value->where('Usia', '>', $mean_umur)->count();
            $total_gizi_baik_usia_diatas_mean = $value->where('Usia', '>', $mean_umur)->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang_usia_diatas_mean = $value->where('Usia', '>', $mean_umur)->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_usia_diatas_mean = (float)$this->calculateEntropy($total_kasus_usia_diatas_mean, $total_gizi_baik_usia_diatas_mean, $total_gizi_kurang_usia_diatas_mean);
            

               //gain usia
            $gain_usia = $entropy_total_gizi - (
                (($total_kasus_usia_dbwah_mean / $total_kasus) * $entropy_total_usia_dbwah_mean) +
                (($total_kasus_usia_diatas_mean / $total_kasus) * $entropy_total_usia_diatas_mean) 
            
            );

            $gain_usia=round($gain_usia,5);
    

            //entropy usia dibawah median
            $total_kasus_usia_dbwah_median = $value->where('Usia', '<=', $median_umur)->count();
            $total_gizi_baik_usia_dbwah_median = $value->where('Usia', '<=', $median_umur)->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang_usia_dbwah_median = $value->where('Usia', '<=', $median_umur)->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_usia_dbwah_median = (float)$this->calculateEntropy($total_kasus_usia_dbwah_median, $total_gizi_baik_usia_dbwah_median, $total_gizi_kurang_usia_dbwah_median);

              //entropy usia diatas median
            $total_kasus_usia_diatas_median = Dataset1::where('Usia', '>', $median_umur)->count();
            $total_gizi_baik_usia_diatas_median = Dataset1::where('Usia', '>', $median_umur)->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
            $total_gizi_kurang_usia_diatas_median = Dataset1::where('Usia', '>', $median_umur)->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
            $entropy_total_usia_diatas_median = (float)$this->calculateEntropy($total_kasus_usia_diatas_median, $total_gizi_baik_usia_diatas_median, $total_gizi_kurang_usia_diatas_median);
 
         //gain usia
           $gain_usia2 = $entropy_total_gizi - (
             (($total_kasus_usia_dbwah_median / $total_kasus) * $entropy_total_usia_dbwah_median) +
             (($total_kasus_usia_diatas_median / $total_kasus) * $entropy_total_usia_diatas_median) 
         
             );
 
         $gain_usia2=round($gain_usia2,5);

              //menentukan gain tertinggi
            $gain_tertinggi=max($gain_bbu,$gain_tbu,$gain_usia,$gain_usia2);
            // dd($gain_tertinggi);
            
            // dd(
            //     $total_kasus,
            //     $total_gizi_baik,
            //     $total_gizi_kurang,
            //     $entropy_total_gizi,
            //     $total_kasus_bbu_normal,
            //     $total_gizi_baik_bbu_normal,
            //     $total_gizi_kurang_bbu_normal,
            //     $entropy_total_bbu_normal,
            // );

        if ($gain_tertinggi==$gain_bbu){
            if ($total_gizi_baik_bbu_normal>0||$total_gizi_kurang_bbu_normal>0) {

                $dataset1_bbu_normal =$value->where('berat_badan_per_usia', 'Normal');
                $total_kasus_bbu_normal = $dataset1_bbu_normal->count();
                $total_gizi_baik_bbu_normal =$value->where('berat_badan_per_usia', 'Normal')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
                $total_gizi_kurang_bbu_normal =$value->where('berat_badan_per_usia', 'Normal')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
                $entropy_total_bbu_normal = (float)$this->calculateEntropy($total_kasus_bbu_normal, $total_gizi_baik_bbu_normal, $total_gizi_kurang_bbu_normal);
               
                
            
            }
            if($total_gizi_baik_bbu_kurang>0||$total_gizi_kurang_bbu_kurang>0){
                $dataset1_bbu_kurang = $value->where('berat_badan_per_usia', 'Kurang');
                $total_kasus_bbu_kurang = $dataset1_bbu_kurang->count();
                $total_gizi_baik_bbu_kurang = $value->where('berat_badan_per_usia', 'Kurang')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
                $total_gizi_kurang_bbu_kurang = $value->where('berat_badan_per_usia', 'Kurang')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
                $entropy_total_bbu_kurang = (float)$this->calculateEntropy($total_kasus_bbu_kurang, $total_gizi_baik_bbu_kurang, $total_gizi_kurang_bbu_kurang);
    
                
            }

            if($total_gizi_baik_bbu_sangat_kurang>0||$total_gizi_sangat_kurang_bbu_sangat_kurang>0){
                $dataset1_bbu_sangat_kurang = $value->where('berat_badan_per_usia', 'Sangat Kurang');
                $total_kasus_bbu_sangat_kurang = $dataset1_bbu_sangat_kurang->count();
                $total_gizi_baik_bbu_sangat_kurang = $value->where('berat_badan_per_usia', 'Sangat Kurang')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
                $total_gizi_kurang_bbu_sangat_kurang = $value->where('berat_badan_per_usia', 'Sangat Kurang')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
                $entropy_total_bbu_sangat_kurang = (float)$this->calculateEntropy($total_kasus_bbu_sangat_kurang, $total_gizi_baik_bbu_sangat_kurang, $total_gizi_kurang_bbu_sangat_kurang);
    
                
            }

            $gain_bbu = $entropy_total_gizi - (
                (($total_kasus_bbu_normal / $total_kasus) * $entropy_total_bbu_normal) +
                (($total_kasus_bbu_kurang / $total_kasus) * $entropy_total_bbu_kurang) +
                (($total_kasus_bbu_sangat_kurang / $total_kasus) * $entropy_total_bbu_sangat_kurang)
            );
            $gain_bbu=round($gain_bbu,5);
        }

        if ($gain_tertinggi==$gain_tbu){
            if($total_gizi_baik_tbu_normal>0||$total_gizi_kurang_tbu_normal>0){
                $dataset1_tbu_normal = $value->where('tinggi_badan_per_usia', 'Normal');
                $total_kasus_tbu_normal = $dataset1_tbu_normal->count();
                $total_gizi_baik_tbu_normal = $value->where('tinggi_badan_per_usia', 'Normal')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
                $total_gizi_kurang_tbu_normal = $value->where('tinggi_badan_per_usia', 'Normal')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
                $entropy_total_tbu_normal = (float)$this->calculateEntropy($total_kasus_tbu_normal, $total_gizi_baik_tbu_normal, $total_gizi_kurang_tbu_normal);
            }

            if($total_gizi_baik_tbu_pendek>0||$total_gizi_kurang_tbu_pendek>0){
                $dataset1_tbu_pendek = $value->where('tinggi_badan_per_usia', 'Pendek');
                $total_kasus_tbu_pendek = $dataset1_tbu_pendek->count();
                $total_gizi_baik_tbu_pendek = $value->where('tinggi_badan_per_usia', 'Pendek')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
                $total_gizi_kurang_tbu_pendek = $value->where('tinggi_badan_per_usia', 'Pendek')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
                $entropy_total_tbu_pendek = (float)$this->calculateEntropy($total_kasus_tbu_pendek, $total_gizi_baik_tbu_pendek, $total_gizi_kurang_tbu_pendek);
        
            }

            if($total_gizi_baik_tbu_sangat_pendek>0||$total_gizi_kurang_tbu_sangat_pendek>0){
                $dataset1_tbu_sangat_pendek =$value->where('tinggi_badan_per_usia', 'Sangat Pendek');
                $total_kasus_tbu_sangat_pendek = $dataset1_tbu_sangat_pendek->count();
                $total_gizi_baik_tbu_sangat_pendek =$value->where('tinggi_badan_per_usia', 'Sangat Pendek')->where('berat_badan_per_tinggi_badan', 'Gizi Baik')->count();
                $total_gizi_kurang_tbu_sangat_pendek =$value->where('tinggi_badan_per_usia', 'Sangat Pendek')->where('berat_badan_per_tinggi_badan', 'Gizi Kurang')->count();
                $entropy_total_tbu_sangat_pendek = (float)$this->calculateEntropy($total_kasus_tbu_sangat_pendek, $total_gizi_baik_tbu_sangat_pendek, $total_gizi_kurang_tbu_sangat_pendek);
        
            }


        }
        }

       
    }
    
    /**
     * Fungsi untuk menghitung entropy dengan pengecekan pembagian nol dan presisi logaritma
     */
    private function calculateEntropy($total, $count1, $count2) {
        if ($total == 0 || ($count1 == 0 && $count2 == 0)) {
            return 0;  // Jika total atau keduanya nol, entropy dianggap nol
        }
        
        $p1 = ($count1 > 0) ? $count1 / $total : 0;
        $p2 = ($count2 > 0) ? $count2 / $total : 0;
    
        $entropy = 0;
        if ($p1 > 0) {
            $entropy += (-$p1) * log($p1, 2);
        }
        if ($p2 > 0) {
            $entropy += (-$p2) * log($p2, 2);
        }
    
        return round($entropy, 7);
    }
    

    
}

// function calculate_entropy($data, $target_attribute) {
//     $values = array_count_values(array_column($data, $target_attribute));
//     $total = count($data);
//     $entropy = 0;

//     foreach ($values as $count) {
//         $probability = $count / $total;
//         $entropy -= $probability * log($probability, 2);
//     }

//     return $entropy;
// }

// function calculate_information_gain($data, $attribute, $target_attribute) {
//     $entropy_before = calculate_entropy($data, $target_attribute);
//     $total = count($data);
    
//     $values = array_unique(array_column($data, $attribute));
//     $entropy_after = 0;

//     foreach ($values as $value) {
//         $subset = array_filter($data, function($row) use ($attribute, $value) {
//             return $row[$attribute] == $value;
//         });

//         $subset_entropy = calculate_entropy($subset, $target_attribute);
//         $entropy_after += (count($subset) / $total) * $subset_entropy;
//     }

//     return $entropy_before - $entropy_after;
// }

// function find_best_split($data, $attributes, $target_attribute) {
//     $best_gain = 0;
//     $best_attribute = null;

//     foreach ($attributes as $attribute) {
//         $gain = calculate_information_gain($data, $attribute, $target_attribute);
        
//         if ($gain > $best_gain) {
//             $best_gain = $gain;
//             $best_attribute = $attribute;
//         }
//     }

//     return $best_attribute;
// }

// // Example usage:
// $dataset = [
//     ['Usia Bln' => 2, 'BB/U' => 'Kurang', 'TB/U' => 'Pendek', 'BB/TB' => 'Gizi Kurang'],
//     ['Usia Bln' => 6, 'BB/U' => 'Kurang', 'TB/U' => 'Normal', 'BB/TB' => 'Gizi Kurang'],
//     ['Usia Bln' => 11, 'BB/U' => 'Sangat Kurang', 'TB/U' => 'Pendek', 'BB/TB' => 'Gizi Kurang'],
//     // Add more rows here
// ];

// $attributes = ['Usia Bln', 'BB/U', 'TB/U'];
// $target_attribute = 'BB/TB';

// $best_attribute = find_best_split($dataset, $attributes, $target_attribute);
// echo "Best attribute to split on: $best_attribute";
