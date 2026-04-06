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
        Schema::create('event_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id')->index('event_id');
            $table->unsignedBigInteger('event_locations_id')->index('event_locations_id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->timestamp('registration_date')->useCurrent();
            $table->text('observations')->nullable();
            $table->enum('status', ['registered', 'attended', 'absent'])->nullable()->default('registered');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_locations_id'], 'event_locations_id_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};
