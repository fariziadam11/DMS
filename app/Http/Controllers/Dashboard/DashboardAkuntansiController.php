<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DashboardStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAkuntansiController extends Controller
{
    use DashboardStatistics;

    protected $tables = [
        'akuntansi_aturan_kebijakan',
        'akuntansi_jurnal_umum',
        'akuntansi_laporan_audit_investasi',
        'akuntansi_laporan_audit_keuangan',
        'akuntansi_laporan_bulanan',
    ];

    protected $labels = [
        'Aturan Kebijakan',
        'Jurnal Umum',
        'Laporan Audit Investasi',
        'Laporan Audit Keuangan',
        'Laporan Bulanan',
    ];

    public function index()
    {
        $user = Auth::user();

        // Get statistics
        $stats = [
            'total_documents' => $this->getTotalDocuments($this->tables),
            'documents_this_month' => $this->getDocumentsThisMonth($this->tables),
            'by_sifat' => $this->getDocumentsBySifat($this->tables),
            'per_submodule' => $this->getDocumentsPerSubModule($this->tables, $this->labels),
            'recent_documents' => $this->getRecentDocuments($this->tables, $this->labels, 10),
            'monthly_trend' => $this->getMonthlyTrend($this->tables),
        ];

        return view('dashboards.akuntansi', compact('stats'));
    }
}
