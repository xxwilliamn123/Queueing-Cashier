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
        Schema::table('tickets', function (Blueprint $table) {
            // Add indexes for frequently queried columns
            $table->index(['status', 'ticket_date'], 'idx_status_date');
            $table->index(['teller_id', 'status', 'ticket_date'], 'idx_teller_status');
            $table->index(['type', 'status', 'ticket_date'], 'idx_type_status_date');
        });

        Schema::table('sessions', function (Blueprint $table) {
            // Add index for active teller queries
            if (Schema::hasColumn('sessions', 'user_id') && Schema::hasColumn('sessions', 'last_activity')) {
                $table->index(['user_id', 'last_activity'], 'idx_user_activity');
            }
        });

        Schema::table('servings', function (Blueprint $table) {
            // Add index for ticket-ended queries
            if (Schema::hasColumn('servings', 'ticket_id') && Schema::hasColumn('servings', 'ended_at')) {
                $table->index(['ticket_id', 'ended_at'], 'idx_ticket_ended');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('idx_status_date');
            $table->dropIndex('idx_teller_status');
            $table->dropIndex('idx_type_status_date');
        });

        Schema::table('sessions', function (Blueprint $table) {
            if (Schema::hasColumn('sessions', 'user_id') && Schema::hasColumn('sessions', 'last_activity')) {
                $table->dropIndex('idx_user_activity');
            }
        });

        Schema::table('servings', function (Blueprint $table) {
            if (Schema::hasColumn('servings', 'ticket_id') && Schema::hasColumn('servings', 'ended_at')) {
                $table->dropIndex('idx_ticket_ended');
            }
        });
    }
};
