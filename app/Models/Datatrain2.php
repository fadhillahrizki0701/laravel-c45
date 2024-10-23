<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datatrain2 extends Model
{
	use HasFactory;

	public $table = "datatrain2";
	protected $fillable = [
		"usia",
		"berat_badan_per_tinggi_badan",
		"menu",
		"keterangan",
	];
	public $timestamps = false;
}
