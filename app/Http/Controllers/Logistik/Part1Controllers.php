<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class ProcurementController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\Procurement::class;
    protected $viewPath = 'logistik.procurement';
    protected $routePrefix = 'logistik.procurement';
    protected $moduleName = 'Logistik - Procurement';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'no_drp' => 'nullable|string|max:100',
            'nama_kegiatan' => 'required|string|max:255',
            'direktorat' => 'nullable|string|max:100',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class CleaningServiceController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\CleaningService::class;
    protected $viewPath = 'logistik.cleaning-service';
    protected $routePrefix = 'logistik.cleaning-service';
    protected $moduleName = 'Logistik - Cleaning Service';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer',
            'bulan' => 'nullable|integer|min:1|max:12',
            'nama_kegiatan' => 'required|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class KeamananController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\Keamanan::class;
    protected $viewPath = 'logistik.keamanan';
    protected $routePrefix = 'logistik.keamanan';
    protected $moduleName = 'Logistik - Keamanan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer',
            'bulan' => 'nullable|integer|min:1|max:12',
            'nama_kegiatan' => 'required|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class KendaraanController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\Kendaraan::class;
    protected $viewPath = 'logistik.kendaraan';
    protected $routePrefix = 'logistik.kendaraan';
    protected $moduleName = 'Logistik - Kendaraan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer',
            'bulan' => 'nullable|integer|min:1|max:12',
            'nama_kegiatan' => 'required|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class SaranaPenunjangController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\SaranaPenunjang::class;
    protected $viewPath = 'logistik.sarana-penunjang';
    protected $routePrefix = 'logistik.sarana-penunjang';
    protected $moduleName = 'Logistik - Sarana Penunjang';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer',
            'bulan' => 'nullable|integer|min:1|max:12',
            'nama_kegiatan' => 'required|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class PelaporanPrbcController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\PelaporanPrbc::class;
    protected $viewPath = 'logistik.pelaporan-prbc';
    protected $routePrefix = 'logistik.pelaporan-prbc';
    protected $moduleName = 'Logistik - Pelaporan PRBC';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'bulan' => 'nullable|integer|min:1|max:12',
            'tanggal' => 'nullable|date',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}
