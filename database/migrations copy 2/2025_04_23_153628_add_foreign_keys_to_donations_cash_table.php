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
        Schema::table('donations_cash', function (Blueprint $table) {
            $table->foreign(['external_donor_id'], 'donations_cash_ibfk_1')->references(['id'])->on('external_donor')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['donor_id'], 'donations_cash_ibfk_2')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['campaign_id'], 'donations_cash_ibfk_3')->references(['id'])->on('campaigns')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations_cash', function (Blueprint $table) {
            $table->dropForeign('donations_cash_ibfk_1');
            $table->dropForeign('donations_cash_ibfk_2');
            $table->dropForeign('donations_cash_ibfk_3');
        });
    }
};
