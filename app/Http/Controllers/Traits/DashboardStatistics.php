<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait DashboardStatistics
{
    /**
     * Get total documents count for given tables
     */
    protected function getTotalDocuments(array $tables): int
    {
        $total = 0;
        foreach ($tables as $table) {
            $total += DB::table($table)->whereNull('deleted_at')->count();
        }
        return $total;
    }

    /**
     * Get documents count for current month
     */
    protected function getDocumentsThisMonth(array $tables): int
    {
        $total = 0;
        $startOfMonth = Carbon::now()->startOfMonth();

        foreach ($tables as $table) {
            $total += DB::table($table)
                ->whereNull('deleted_at')
                ->where('created_at', '>=', $startOfMonth)
                ->count();
        }
        return $total;
    }

    /**
     * Get documents count by sifat_dokumen
     */
    protected function getDocumentsBySifat(array $tables): array
    {
        $stats = [
            'Umum' => 0,
            'Internal' => 0,
            'Rahasia' => 0
        ];

        foreach ($tables as $table) {
            $results = DB::table($table)
                ->select('sifat_dokumen', DB::raw('count(*) as total'))
                ->whereNull('deleted_at')
                ->groupBy('sifat_dokumen')
                ->get();

            foreach ($results as $result) {
                if (isset($stats[$result->sifat_dokumen])) {
                    $stats[$result->sifat_dokumen] += $result->total;
                }
            }
        }

        return $stats;
    }

    /**
     * Get documents count per sub-module
     */
    protected function getDocumentsPerSubModule(array $tables, array $labels): array
    {
        $data = [];

        foreach ($tables as $index => $table) {
            $count = DB::table($table)->whereNull('deleted_at')->count();
            $data[] = [
                'label' => $labels[$index] ?? $table,
                'count' => $count
            ];
        }

        return $data;
    }

    /**
     * Get recent documents from multiple tables
     */
    protected function getRecentDocuments(array $tables, array $labels, int $limit = 10): array
    {
        $documents = [];

        foreach ($tables as $index => $table) {
            $records = DB::table($table)
                ->select(
                    'id',
                    'created_at',
                    'updated_at',
                    DB::raw("'{$labels[$index]}' as sub_module"),
                    DB::raw("'{$table}' as table_name")
                )
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            foreach ($records as $record) {
                $documents[] = $record;
            }
        }

        // Sort by created_at and limit
        usort($documents, function($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        return array_slice($documents, 0, $limit);
    }

    /**
     * Get monthly trend data for last 6 months
     */
    protected function getMonthlyTrend(array $tables): array
    {
        $months = [];
        $data = [];

        // Generate last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $data[$date->format('Y-m')] = 0;
        }

        foreach ($tables as $table) {
            $results = DB::table($table)
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
                ->whereNull('deleted_at')
                ->where('created_at', '>=', Carbon::now()->subMonths(6))
                ->groupBy('month')
                ->get();

            foreach ($results as $result) {
                if (isset($data[$result->month])) {
                    $data[$result->month] += $result->total;
                }
            }
        }

        return [
            'labels' => $months,
            'data' => array_values($data)
        ];
    }

    /**
     * Apply division filter if user has division-based access
     */
    protected function applyDivisionFilter($query, $user)
    {
        // Check if user has global access
        $hasGlobalAccess = $user->roles()->whereHas('divisionAccess', function($q) {
            $q->where('is_global', true);
        })->exists();

        if (!$hasGlobalAccess && $user->divisi_id) {
            $query->where('id_divisi', $user->divisi_id);
        }

        return $query;
    }
}
