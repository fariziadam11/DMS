<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DashboardStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAnggaranController extends Controller
{
    use DashboardStatistics;

    protected $tables = [
        'anggaran_aturan_kebijakan',
        'anggaran_dokumen_rra',
        'anggaran_laporan_prbc',
        'anggaran_rencana_kerja_direktorat',
        'anggaran_rencana_kerja_tahunan',
        'anggaran_rencana_kerja_triwulan',
    ];

    protected $labels = [
        'Aturan Kebijakan',
        'Dokumen RRA',
        'Laporan PRBC',
        'Rencana Kerja Direktorat',
        'Rencana Kerja Tahunan',
        'Rencana Kerja Triwulan',
    ];

    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_documents' => $this->getTotalDocuments($this->tables),
            'documents_this_month' => $this->getDocumentsThisMonth($this->tables),
            'by_sifat' => $this->getDocumentsBySifat($this->tables),
            'per_submodule' => $this->getDocumentsPerSubModule($this->tables, $this->labels),
            'recent_documents' => $this->getRecentDocuments($this->tables, $this->labels, 10),
            'monthly_trend' => $this->getMonthlyTrend($this->tables),
        ];

        return view('dashboards.anggaran', compact('stats'));
    }
}
