<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset2 extends Model
{
	protected $fillable = [
		"usia",
		"berat_badan_per_tinggi_badan",
		"menu",
		"keterangan",
	];
	public $timestamps = false;
}
