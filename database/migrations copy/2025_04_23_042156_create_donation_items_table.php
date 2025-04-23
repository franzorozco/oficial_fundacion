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
        Schema::create('donation_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('donation_id')->index('donation_id');
            $table->unsignedBigInteger('donation_type_id')->index('donation_type_id');
            $table->string('item_name', 150);
            $table->unsignedInteger('quantity')->nullable()->default(1);
            $table->string('unit', 50)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_items');
    }
};
