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
        Schema::create('donations_cash', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('donor_id')->nullable()->index('donor_id');
            $table->unsignedBigInteger('external_donor_id')->nullable()->index('external_donor_id');
            $table->decimal('amount', 12);
            $table->string('method', 50)->nullable();
            $table->date('donation_date');
            $table->unsignedBigInteger('campaign_id')->nullable()->index('campaign_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations_cash');
    }
};
