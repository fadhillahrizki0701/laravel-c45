<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dataset1 extends Model
{
	protected $fillable = [
		"nama",
		"usia",
		"berat_badan_per_usia",
		"tinggi_badan_per_usia",
		"berat_badan_per_tinggi_badan",
	];
	public $timestamps = false;
}
