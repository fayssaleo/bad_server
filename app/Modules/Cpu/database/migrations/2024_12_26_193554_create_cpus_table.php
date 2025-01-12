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
        Schema::create('cpus', function (Blueprint $table) {
            $table->id();
            $table->string('cpuId')->nullable();
            $table->string('driver')->nullable();
            $table->string('flatTruck')->nullable();
            $table->string('holder')->nullable();
            $table->string('nounce')->nullable();
            $table->string('objectType')->nullable();
            $table->string('owner')->nullable();
            $table->string('securityCode')->nullable();
            $table->string('status')->nullable();
            $table->string('termAuthAssoc')->nullable();
            $table->string('terminal')->nullable();
            $table->string('transpAuthAssoc')->nullable();
            $table->string('truck')->nullable();
            $table->string('unit')->nullable();
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
        Schema::dropIfExists('cpus');
    }
};
