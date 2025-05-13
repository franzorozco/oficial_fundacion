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
        Schema::table('external_donor', function (Blueprint $table) {
            // Agrega la columna 'deleted_at' para el SoftDeletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_donor', function (Blueprint $table) {
            // Elimina la columna 'deleted_at' si se hace un rollback
            $table->dropSoftDeletes();
        });
    }
};
