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
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->foreign(['supervisor'], 'task_assignments_ibfk_1')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['task_id'], 'task_assignments_ibfk_2')->references(['id'])->on('tasks')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'], 'task_assignments_ibfk_3')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->dropForeign('task_assignments_ibfk_1');
            $table->dropForeign('task_assignments_ibfk_2');
            $table->dropForeign('task_assignments_ibfk_3');
        });
    }
};
