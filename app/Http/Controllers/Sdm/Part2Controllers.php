<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PenghargaanController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Penghargaan::class;
    protected $viewPath = 'sdm.penghargaan';
    protected $routePrefix = 'sdm.penghargaan';
    protected $moduleName = 'SDM - Penghargaan';

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

class IkutOrganisasiController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\IkutOrganisasi::class;
    protected $viewPath = 'sdm.ikut-organisasi';
    protected $routePrefix = 'sdm.ikut-organisasi';
    protected $moduleName = 'SDM - Ikut Organisasi';

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

class SuratMasukController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\SuratMasuk::class;
    protected $viewPath = 'sdm.surat-masuk';
    protected $routePrefix = 'sdm.surat-masuk';
    protected $moduleName = 'SDM - Surat Masuk';

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

class SuratKeluarController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\SuratKeluar::class;
    protected $viewPath = 'sdm.surat-keluar';
    protected $routePrefix = 'sdm.surat-keluar';
    protected $moduleName = 'SDM - Surat Keluar';

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

class AspurjabController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Aspurjab::class;
    protected $viewPath = 'sdm.aspurjab';
    protected $routePrefix = 'sdm.aspurjab';
    protected $moduleName = 'SDM - Aspurjab';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'nama' => 'required|string|max:100',
            'mulai' => 'nullable|date',
            'akhir' => 'nullable|date',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class RekonController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Rekon::class;
    protected $viewPath = 'sdm.rekon';
    protected $routePrefix = 'sdm.rekon';
    protected $moduleName = 'SDM - Rekon';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'ba' => 'nullable|string|max:100',
            'hasil' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}
