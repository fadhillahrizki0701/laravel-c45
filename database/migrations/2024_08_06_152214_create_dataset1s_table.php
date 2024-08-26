<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dataset1s', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('usia'); 
            $table->enum('berat_badan_per_usia', [
                'Normal',
                'Kurang',
                'Sangat Kurang',
            ]);
            $table->enum('tinggi_badan_per_usia', [
                'Normal',
                'Pendek',
                'Sangat Pendek',
            ]);
            $table->enum('berat_badan_per_tinggi_badan', [
                'Gizi Baik',
                'Gizi Kurang'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataset1s');
        
    }
};
