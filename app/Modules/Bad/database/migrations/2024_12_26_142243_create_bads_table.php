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
        Schema::create('bads', function (Blueprint $table) {
            $table->id();
            $table->string('badId')->unique();
            $table->string('consignee')->nullable();
            $table->string('creationDate')->nullable();
            $table->string('dateExpiration')->nullable();
            $table->string('holder')->nullable();
            $table->string('holderName')->nullable();
            $table->string('issuer')->nullable();
            $table->string('issuerName')->nullable();
            $table->string('numCpu')->nullable();
            $table->string('owner')->nullable();
            $table->string('OwnerName')->nullable();
            $table->string('connaissementReference')->nullable();
            $table->string('port')->nullable();
            $table->string('dateReceptionDouane')->nullable();
            $table->string('dateReceptionEai')->nullable();
            $table->string('numeroVoyage')->nullable();
            $table->string('nomNavire')->nullable();
            $table->string('dateVoyage')->nullable();
            $table->string('etd')->nullable();
            $table->string('imoNumber')->nullable();
            $table->string('armateurProprietaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bads');
    }
};
