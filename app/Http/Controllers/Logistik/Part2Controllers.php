<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class UserSatisfactionController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\UserSatisfaction::class;
    protected $viewPath = 'logistik.user-satisfaction';
    protected $routePrefix = 'logistik.user-satisfaction';
    protected $moduleName = 'Logistik - User Satisfaction';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer',
            'tanggal' => 'nullable|date',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class VendorSatisfactionController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\VendorSatisfaction::class;
    protected $viewPath = 'logistik.vendor-satisfaction';
    protected $routePrefix = 'logistik.vendor-satisfaction';
    protected $moduleName = 'Logistik - Vendor Satisfaction';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer',
            'tanggal' => 'nullable|date',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class Smk3Controller extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\Smk3::class;
    protected $viewPath = 'logistik.smk3';
    protected $routePrefix = 'logistik.smk3';
    protected $moduleName = 'Logistik - SMK3';

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

class PolisAsuransiController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\PolisAsuransi::class;
    protected $viewPath = 'logistik.polis-asuransi';
    protected $routePrefix = 'logistik.polis-asuransi';
    protected $moduleName = 'Logistik - Polis Asuransi';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer',
            'berlaku_mulai' => 'nullable|date',
            'berlaku_akhir' => 'nullable|date',
            'nilai' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class JaminanController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\Jaminan::class;
    protected $viewPath = 'logistik.jaminan';
    protected $routePrefix = 'logistik.jaminan';
    protected $moduleName = 'Logistik - Jaminan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'jenis_jaminan' => 'nullable|string|max:100',
            'nomor_drp' => 'nullable|string|max:100',
            'nama_pengadaan' => 'required|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}
