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
        Schema::table('volunteer_verifications', function (Blueprint $table) {
            $table->foreign(['user_id'], 'volunteer_verifications_ibfk_1')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_resp_id'], 'volunteer_verifications_ibfk_2')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteer_verifications', function (Blueprint $table) {
            $table->dropForeign('volunteer_verifications_ibfk_1');
            $table->dropForeign('volunteer_verifications_ibfk_2');
        });
    }
};
