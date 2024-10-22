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
        Schema::create('datatrain2', function (Blueprint $table) {
            $table->id();
            $table->enum('usia', [
                'Fase 1',
                'Fase 2',
                'Fase 3',
                'Fase 4',
            ]);
            $table->enum('berat_badan_per_tinggi_badan', [
                'Gizi Baik',
                'Gizi Kurang'
            ]);
            $table->enum('menu', [
                'M1',
                'M2',
                'M3',
                'M4',
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
        Schema::dropIfExists('datatrain2');
    }
};
