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
        Schema::table('file_access_requests', function (Blueprint $table) {
            $table->dateTime('valid_till')->nullable()->after('permissions');
            $table->integer('download_limit')->nullable()->after('valid_till');
            $table->integer('download_count')->default(0)->after('download_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_access_requests', function (Blueprint $table) {
            $table->dropColumn(['valid_till', 'download_limit', 'download_count']);
        });
    }
};
