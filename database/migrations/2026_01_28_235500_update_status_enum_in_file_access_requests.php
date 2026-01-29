<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the status enum to include 'expired'
        DB::statement("ALTER TABLE file_access_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'expired') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum (WARNING: this might truncate 'expired' values if any exist)
        // For safety, we usually don't strictly revert enums if data loss is possible, but strict implementation:
        // We'll update 'expired' to 'rejected' (or something else) before reverting?
        // Or just leave it. But for correctness:

        // Update expired to rejected first to avoid truncation error during revert
        DB::table('file_access_requests')->where('status', 'expired')->update(['status' => 'rejected']);

        DB::statement("ALTER TABLE file_access_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
