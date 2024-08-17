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
            $table->integer('Usia');
            $table->string('berat_badan_per_tinggi_badan');
            $table->string('Menu');
            $table->string('Keterangan');
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
