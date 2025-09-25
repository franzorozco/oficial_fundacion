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
        Schema::create('external_donor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('names', 100);
            $table->string('paternal_surname', 100)->nullable();
            $table->string('maternal_surname', 100)->nullable();
            $table->string('email', 150)->unique()->nullable();

            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();

            // Agregar la columna para el SoftDeletes
            $table->softDeletes(); // Esto agrega la columna 'deleted_at' de tipo TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_donor');
    }
};
