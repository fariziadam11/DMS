<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DashboardStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardLogistikController extends Controller
{
    use DashboardStatistics;

    protected $tables = [
        'logistiksarpen_procurement',
        'logistiksarpen_cleaning_service',
        'logistiksarpen_keamanan',
        'logistiksarpen_kendaraan',
        'logistiksarpen_sarana_penunjang',
        'logistiksarpen_smk3',
        'logistiksarpen_polis_asuransi',
        'logistiksarpen_jaminan',
        'logistiksarpen_pelaporan_prbc',
        'logistiksarpen_user_satisfaction',
        'logistiksarpen_vendor_satisfaction',
    ];

    protected $labels = [
        'Procurement',
        'Cleaning Service',
        'Keamanan',
        'Kendaraan',
        'Sarana Penunjang',
        'SMK3',
        'Polis Asuransi',
        'Jaminan',
        'Pelaporan PRBC',
        'User Satisfaction',
        'Vendor Satisfaction',
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

        return view('dashboards.logistik', compact('stats'));
    }
}
