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
        Schema::create('donation_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_user__id')->index('applicant_user__id');
            $table->unsignedBigInteger('user_in_charge_id')->nullable()->index('user_in_charge_id');
            $table->unsignedBigInteger('donation_id')->index('donation_id');
            $table->timestamp('request_date')->useCurrent();
            $table->text('notes')->nullable();
            $table->enum('state', ['pendiente', 'en revision', 'aceptado', 'rechazado', 'finalizado'])->nullable()->default('pendiente');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_requests');
    }
};
