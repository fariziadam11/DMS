<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DashboardStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardSekretariatController extends Controller
{
    use DashboardStatistics;

    protected $tables = [
        'sekretariat_risalah_rapat',
        'sekretariat_materi',
        'sekretariat_laporan',
        'sekretariat_surat',
        'sekretariat_pengadaan',
        'sekretariat_remunerasi_pedoman',
        'sekretariat_remunerasi_dokumen',
    ];

    protected $labels = [
        'Risalah Rapat',
        'Materi',
        'Laporan',
        'Surat',
        'Pengadaan',
        'Remunerasi Pedoman',
        'Remunerasi Dokumen',
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

        return view('dashboards.sekretariat', compact('stats'));
    }
}
