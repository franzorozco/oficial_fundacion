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
        Schema::table('donation_item_photos', function (Blueprint $table) {
            $table->foreign(['donation_item_id'], 'donation_item_photos_ibfk_1')->references(['id'])->on('donation_items')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_item_photos', function (Blueprint $table) {
            $table->dropForeign('donation_item_photos_ibfk_1');
        });
    }
};
