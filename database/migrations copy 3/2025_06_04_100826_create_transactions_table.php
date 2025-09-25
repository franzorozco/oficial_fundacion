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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id')->index('account_id');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 12);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('related_campaign_id')->nullable()->index('related_campaign_id');
            $table->unsignedBigInteger('related_payment_id')->nullable();
            $table->date('transaction_date');
            $table->unsignedBigInteger('created_by')->nullable()->index('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
