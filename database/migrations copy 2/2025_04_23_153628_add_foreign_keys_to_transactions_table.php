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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign(['account_id'], 'transactions_ibfk_1')->references(['id'])->on('financial_accounts')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['related_campaign_id'], 'transactions_ibfk_2')->references(['id'])->on('campaigns')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['created_by'], 'transactions_ibfk_3')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_ibfk_1');
            $table->dropForeign('transactions_ibfk_2');
            $table->dropForeign('transactions_ibfk_3');
        });
    }
};
