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

            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('event_locations_id')->index();
            $table->unsignedBigInteger('user_id')->index();

            $table->timestamp('registration_date')->useCurrent();
            $table->text('observations')->nullable();
            $table->enum('status', ['registered', 'attended', 'absent'])->default('registered');

            $table->timestamps();
            $table->softDeletes();


            $table->foreign('event_locations_id')
                ->references('id')->on('event_locations')
                ->onDelete('cascade');
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
