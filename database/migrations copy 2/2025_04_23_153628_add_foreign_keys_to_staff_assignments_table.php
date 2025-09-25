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
        Schema::table('staff_assignments', function (Blueprint $table) {
            $table->foreign(['user_id'], 'staff_assignments_ibfk_1')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['Campaign_id'], 'staff_assignments_ibfk_2')->references(['id'])->on('campaigns')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['event_id'], 'staff_assignments_ibfk_3')->references(['id'])->on('events')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['assigned_by_id'], 'staff_assignments_ibfk_4')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_assignments', function (Blueprint $table) {
            $table->dropForeign('staff_assignments_ibfk_1');
            $table->dropForeign('staff_assignments_ibfk_2');
            $table->dropForeign('staff_assignments_ibfk_3');
            $table->dropForeign('staff_assignments_ibfk_4');
        });
    }
};
