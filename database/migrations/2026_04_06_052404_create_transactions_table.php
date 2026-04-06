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
            $table->enum('type', ['ingreso', 'gasto', 'transferencia', 'donacion', 'transferencia-gasto', 'transferencia-ingreso', 'reembolso', 'ajuste']);
            $table->decimal('amount', 12);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('related_campaign_id')->nullable()->index('related_campaign_id');
            $table->date('transaction_date')->default('CURRENT_DATE');
            $table->unsignedBigInteger('created_by')->nullable()->index('created_by');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('related_event_id')->nullable()->index('fk_related_event');
            $table->unsignedBigInteger('related_event_location_id')->nullable()->index('fk_related_event_location');
            $table->timestamp('transaction_time')->nullable();
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
