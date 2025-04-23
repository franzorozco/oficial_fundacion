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
        Schema::table('campaign_finances', function (Blueprint $table) {
            $table->foreign(['manager_id'], 'campaign_finances_ibfk_1')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['campaign_id'], 'campaign_finances_ibfk_2')->references(['id'])->on('campaigns')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_finances', function (Blueprint $table) {
            $table->dropForeign('campaign_finances_ibfk_1');
            $table->dropForeign('campaign_finances_ibfk_2');
        });
    }
};
