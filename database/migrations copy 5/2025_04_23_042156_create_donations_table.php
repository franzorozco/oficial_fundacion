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
        Schema::create('donations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('external_donor_id')->nullable()->index('external_donor_id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->unsignedBigInteger('received_by_id')->index('received_by_id');
            $table->unsignedBigInteger('status_id')->index('status_id');
            $table->unsignedBigInteger('during_campaign_id')->nullable()->index('during_campaign_id');
            $table->date('donation_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
