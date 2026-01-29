<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DashboardStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardInvestasiController extends Controller
{
    use DashboardStatistics;

    protected $tables = [
        'transaksi',
        'surat',
        'investasi_perencanaan_transaksi',
        'investasi_perencanaan_surat',
        'investasi_propensa_transaksi',
        'investasi_propensa_surat',
    ];

    protected $labels = [
        'Transaksi',
        'Surat',
        'Perencanaan Transaksi',
        'Perencanaan Surat',
        'Propensa Transaksi',
        'Propensa Surat',
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

        return view('dashboards.investasi', compact('stats'));
    }
}
