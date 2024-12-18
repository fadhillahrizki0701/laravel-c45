<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datatrain1 extends Model
{
	use HasFactory;

	public $table = "datatrain1";
	protected $fillable = [
		"nama",
		"usia",
		"berat_badan_per_usia",
		"tinggi_badan_per_usia",
		"berat_badan_per_tinggi_badan",
	];
	public $timestamps = false;
}
