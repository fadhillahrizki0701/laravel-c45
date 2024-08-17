<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dataset1 extends Model
{
    // The attributes that are mass assignable.
    protected $fillable = [
        'Nama',
        'Usia',
        'berat_badan_per_usia',
        'tinggi_badan_per_usia',
        'berat_badan_per_tinggi_badan',
    ];

    public $timestamps = false;
    // Other model properties and methods
}
