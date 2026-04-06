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
        Schema::create('donation_request_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('donation_request_id')->index('donation_request_id');
            $table->string('recipient_name');
            $table->text('recipient_address')->nullable();
            $table->string('recipient_contact', 100)->nullable();
            $table->enum('tipo_beneficiario', ['individual', 'organización', 'comunidad', 'otro'])->nullable()->default('individual');
            $table->text('reason')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('extra_instructions')->nullable();
            $table->text('supporting_documents')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_request_descriptions');
    }
};
