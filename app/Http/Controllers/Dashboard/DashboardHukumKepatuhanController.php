<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DashboardStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardHukumKepatuhanController extends Controller
{
    use DashboardStatistics;

    protected $tables = [
        'hukumkepatuhan_kajian_hukum',
        'hukumkepatuhan_legal_memo',
        'hukumkepatuhan_regulasi_internal',
        'hukumkepatuhan_regulasi_external',
        'hukumkepatuhan_kontrak',
        'hukumkepatuhan_putusan',
        'hukumkepatuhan_compliance_check',
        'hukumkepatuhan_executive_summary',
        'hukumkepatuhan_lembar_keputusan',
        'hukumkepatuhan_lembar_rekomendasi',
        'hukumkepatuhan_penomoran',
    ];

    protected $labels = [
        'Kajian Hukum',
        'Legal Memo',
        'Regulasi Internal',
        'Regulasi External',
        'Kontrak',
        'Putusan',
        'Compliance Check',
        'Executive Summary',
        'Lembar Keputusan',
        'Lembar Rekomendasi',
        'Penomoran',
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

        return view('dashboards.hukum-kepatuhan', compact('stats'));
    }
}
