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
        Schema::create('campaign_finances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('campaign_id')->index('campaign_id');
            $table->unsignedBigInteger('manager_id')->index('manager_id');
            $table->decimal('income', 15)->nullable()->default(0);
            $table->decimal('expenses', 15)->nullable()->default(0);
            $table->decimal('net_balance', 15)->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_finances');
    }
};
