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
        Schema::create('lignes_marchandises', function (Blueprint $table) {
            $table->id();
            $table->string('ctr_id')->nullable();
            $table->string('isoType')->nullable();
            $table->string('ctr_length')->nullable();
            $table->string('linesite_id')->nullable();
            $table->string('ctr_fe_ind')->nullable();
            $table->unsignedBigInteger('bad_id')->nullable();
            $table->foreign('bad_id')->references('id')->on('bads')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lignes_marchandises');
    }
};
