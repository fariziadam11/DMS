<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PksController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Pks::class;
    protected $viewPath = 'sdm.pks';
    protected $routePrefix = 'sdm.pks';
    protected $moduleName = 'SDM - PKS';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'nama' => 'required|string|max:100',
            'mulai' => 'nullable|date',
            'akhir' => 'nullable|date',
            'kategori' => 'nullable|string|max:100',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class RarusController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Rarus::class;
    protected $viewPath = 'sdm.rarus';
    protected $routePrefix = 'sdm.rarus';
    protected $moduleName = 'SDM - Rarus';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'perihal' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class PeraturanController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Peraturan::class;
    protected $viewPath = 'sdm.peraturan';
    protected $routePrefix = 'sdm.peraturan';
    protected $moduleName = 'SDM - Peraturan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'perihal' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class RekrutMasukController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\RekrutMasuk::class;
    protected $viewPath = 'sdm.rekrut-masuk';
    protected $routePrefix = 'sdm.rekrut-masuk';
    protected $moduleName = 'SDM - Rekrut Masuk';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nik' => 'nullable|string|max:50',
            'nama' => 'required|string|max:100',
            'status' => 'nullable|string|max:100',
            'perihal' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class CapegPegrusController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\CapegPegrus::class;
    protected $viewPath = 'sdm.capeg-pegrus';
    protected $routePrefix = 'sdm.capeg-pegrus';
    protected $moduleName = 'SDM - Capeg/Pegrus';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nik' => 'nullable|string|max:50',
            'nama' => 'required|string|max:100',
            'status' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class PromosiMutasiController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\PromosiMutasi::class;
    protected $viewPath = 'sdm.promosi-mutasi';
    protected $routePrefix = 'sdm.promosi-mutasi';
    protected $moduleName = 'SDM - Promosi/Mutasi';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nik' => 'nullable|string|max:50',
            'nama' => 'required|string|max:100',
            'status' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class NaikGajiController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\NaikGaji::class;
    protected $viewPath = 'sdm.naik-gaji';
    protected $routePrefix = 'sdm.naik-gaji';
    protected $moduleName = 'SDM - Naik Gaji';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nik' => 'nullable|string|max:50',
            'nama' => 'required|string|max:100',
            'tanggal' => 'nullable|date',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}
