<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DashboardStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardKeuanganController extends Controller
{
    use DashboardStatistics;

    protected $tables = [
        'keuangan_bendaharapajak_surat',
        'keuangan_bendaharapajak_spb',
        'keuangan_bendaharapajak_sppb',
        'keuangan_bendaharapajak_cashflow',
        'keuangan_bendaharapajak_penempatan',
        'keuangan_bendaharapajak_pemindahbukuan',
        'keuangan_bendaharapajak_pajak',
    ];

    protected $labels = [
        'Surat Bayar',
        'SPB',
        'SPPB',
        'Cashflow',
        'Penempatan',
        'Pemindahbukuan',
        'Pajak',
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

        return view('dashboards.keuangan', compact('stats'));
    }
}
