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
        Schema::table('donation_request_descriptions', function (Blueprint $table) {
            $table->foreign(['donation_request_id'], 'donation_request_descriptions_ibfk_1')->references(['id'])->on('donation_requests')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_request_descriptions', function (Blueprint $table) {
            $table->dropForeign('donation_request_descriptions_ibfk_1');
        });
    }
};
