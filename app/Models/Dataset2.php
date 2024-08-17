<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset2 extends Model
{
    protected $fillable = [
        'Usia',
        'berat_badan_per_tinggi_badan',
        'Menu',
        'Keterangan',
    ];

    public $timestamps = false;
}
