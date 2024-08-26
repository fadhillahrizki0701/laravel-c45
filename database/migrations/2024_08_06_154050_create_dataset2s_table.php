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
        Schema::create('dataset2s', function (Blueprint $table) {
            $table->id();
            $table->integer('usia');
            $table->enum('berat_badan_per_tinggi_badan', [
                'Gizi Baik',
                'Gizi Kurang'
            ]);
            $table->enum('menu', [
                'M1',
                'M2',
                'M3',
                'M4',
                'M5',
                'M6',
                'M7',
                'M8',
                'M9',
                'M10',
                'M11',
                'M12',
                'M13',
                'M14',
                'M15',
                'M16',
                'M17',
                'M18',
            ]);
            $table->enum('keterangan', [
                'Baik',
                'Tidak Baik',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataset2s');
    }
};
