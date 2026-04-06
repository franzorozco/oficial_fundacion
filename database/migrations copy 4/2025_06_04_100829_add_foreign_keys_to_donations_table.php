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
        Schema::table('donations', function (Blueprint $table) {
            $table->foreign(['user_id'], 'donations_ibfk_1')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['external_donor_id'], 'donations_ibfk_2')->references(['id'])->on('external_donor')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['received_by_id'], 'donations_ibfk_3')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['status_id'], 'donations_ibfk_4')->references(['id'])->on('donation_statuses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['during_campaign_id'], 'donations_ibfk_5')->references(['id'])->on('campaigns')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign('donations_ibfk_1');
            $table->dropForeign('donations_ibfk_2');
            $table->dropForeign('donations_ibfk_3');
            $table->dropForeign('donations_ibfk_4');
            $table->dropForeign('donations_ibfk_5');
        });
    }
};
