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
        // Add indexes for frequently queried columns
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('ukm_id');
        });

        Schema::table('ukms', function (Blueprint $table) {
            $table->index('verification_status');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->index('ukm_id');
            $table->index('event_date');
            $table->index(['ukm_id', 'event_date']); // Composite index for common query
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->index(['event_id', 'user_id']); // Composite index for duplicate check
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['ukm_id']);
        });

        Schema::table('ukms', function (Blueprint $table) {
            $table->dropIndex(['verification_status']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['ukm_id']);
            $table->dropIndex(['event_date']);
            $table->dropIndex(['ukm_id', 'event_date']);
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropIndex(['event_id', 'user_id']);
            $table->dropIndex(['status']);
        });
    }
};
