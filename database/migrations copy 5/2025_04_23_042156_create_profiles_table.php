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
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->text('bio')->nullable();
            $table->string('document_number', 50)->nullable();
            $table->string('photo')->nullable();
            $table->date('birthdate')->nullable();
            $table->text('skills')->nullable();
            $table->text('interests')->nullable();
            $table->string('availability_days', 100)->nullable();
            $table->string('availability_hours', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->boolean('transport_available')->nullable()->default(false);
            $table->enum('experience_level', ['básico', 'intermedio', 'avanzado'])->nullable()->default('básico');
            $table->enum('physical_condition', ['buena', 'moderada', 'limitada'])->nullable()->default('buena');
            $table->text('preferred_tasks')->nullable();
            $table->string('languages_spoken')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
