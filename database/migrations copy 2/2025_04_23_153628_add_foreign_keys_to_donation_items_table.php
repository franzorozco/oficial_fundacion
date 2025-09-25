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
        Schema::table('donation_items', function (Blueprint $table) {
            $table->foreign(['donation_id'], 'donation_items_ibfk_1')->references(['id'])->on('donations')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['donation_type_id'], 'donation_items_ibfk_2')->references(['id'])->on('donation_types')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_items', function (Blueprint $table) {
            $table->dropForeign('donation_items_ibfk_1');
            $table->dropForeign('donation_items_ibfk_2');
        });
    }
};
