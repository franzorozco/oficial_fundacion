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
        Schema::table('donation_requests', function (Blueprint $table) {
            $table->foreign(['applicant_user__id'], 'donation_requests_ibfk_1')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_in_charge_id'], 'donation_requests_ibfk_2')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['donation_id'], 'donation_requests_ibfk_3')->references(['id'])->on('donations')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_requests', function (Blueprint $table) {
            $table->dropForeign('donation_requests_ibfk_1');
            $table->dropForeign('donation_requests_ibfk_2');
            $table->dropForeign('donation_requests_ibfk_3');
        });
    }
};
