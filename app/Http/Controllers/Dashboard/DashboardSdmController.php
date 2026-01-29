<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DashboardStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardSdmController extends Controller
{
    use DashboardStatistics;

    protected $tables = [
        'sdm_pks',
        'sdm_rarus',
        'sdm_peraturan',
        'sdm_rekrut_masuk',
        'sdm_promosi_mutasi',
        'sdm_naik_gaji',
        'sdm_surat_masuk',
        'sdm_surat_keluar',
        'sdm_capeg_pegrus',
        'sdm_penghargaan',
        'sdm_ikut_organisasi',
        'sdm_aspurjab',
        'sdm_rekon',
    ];

    protected $labels = [
        'PKS',
        'RARUS',
        'Peraturan',
        'Rekrutmen Masuk',
        'Promosi & Mutasi',
        'Kenaikan Gaji',
        'Surat Masuk',
        'Surat Keluar',
        'CAPEG & PEGRUS',
        'Penghargaan',
        'Ikut Organisasi',
        'ASPURJAB',
        'Rekon',
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

        return view('dashboards.sdm', compact('stats'));
    }
}
