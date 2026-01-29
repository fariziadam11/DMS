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
        $tables = [
            'investasi_perencanaan_transaksi',
            'investasi_perencanaan_surat',
            'investasi_propensa_transaksi',
            'investasi_propensa_surat'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('type', 50)->nullable()->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'investasi_perencanaan_transaksi',
            'investasi_perencanaan_surat',
            'investasi_propensa_transaksi',
            'investasi_propensa_surat'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->integer('type')->nullable()->change();
                });
            }
        }
    }
};
