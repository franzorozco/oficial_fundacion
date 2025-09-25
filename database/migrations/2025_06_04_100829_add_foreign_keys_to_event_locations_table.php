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
        Schema::table('event_locations', function (Blueprint $table) {
            $table->foreign(['event_id'], 'event_locations_ibfk_1')->references(['id'])->on('events')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_locations', function (Blueprint $table) {
            $table->dropForeign('event_locations_ibfk_1');
        });
    }
};
