<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class RisalahRapatController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\RisalahRapat::class;
    protected $viewPath = 'sekretariat.risalah-rapat';
    protected $routePrefix = 'sekretariat.risalah-rapat';
    protected $moduleName = 'Sekretariat - Risalah Rapat';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'periode' => 'nullable|string|max:100',
            'kategori' => 'nullable|string|max:100',
            'perihal' => 'required|string',
            'peserta' => 'nullable|string',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class MateriController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\Materi::class;
    protected $viewPath = 'sekretariat.materi';
    protected $routePrefix = 'sekretariat.materi';
    protected $moduleName = 'Sekretariat - Materi';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'periode' => 'nullable|string|max:100',
            'perihal' => 'required|string',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class LaporanController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\Laporan::class;
    protected $viewPath = 'sekretariat.laporan';
    protected $routePrefix = 'sekretariat.laporan';
    protected $moduleName = 'Sekretariat - Laporan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'kategori' => 'nullable|string|max:100',
            'perihal' => 'required|string',
            'tujuan' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class SuratController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\Surat::class;
    protected $viewPath = 'sekretariat.surat';
    protected $routePrefix = 'sekretariat.surat';
    protected $moduleName = 'Sekretariat - Surat';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'kategori' => 'nullable|string|max:100',
            'perihal' => 'required|string',
            'dari' => 'nullable|string|max:255',
            'tujuan' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class PengadaanController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\Pengadaan::class;
    protected $viewPath = 'sekretariat.pengadaan';
    protected $routePrefix = 'sekretariat.pengadaan';
    protected $moduleName = 'Sekretariat - Pengadaan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'kategori' => 'nullable|string|max:100',
            'perihal' => 'required|string',
            'tujuan' => 'nullable|string|max:255',
            'masa_akhir' => 'nullable|string|max:100',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class RemunerasiPedomanController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\RemunerasiPedoman::class;
    protected $viewPath = 'sekretariat.remunerasi-pedoman';
    protected $routePrefix = 'sekretariat.remunerasi-pedoman';
    protected $moduleName = 'Sekretariat - Remunerasi Pedoman';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'periode' => 'nullable|string|max:100',
            'perihal' => 'required|string',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class RemunerasiDokumenController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\RemunerasiDokumen::class;
    protected $viewPath = 'sekretariat.remunerasi-dokumen';
    protected $routePrefix = 'sekretariat.remunerasi-dokumen';
    protected $moduleName = 'Sekretariat - Remunerasi Dokumen';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'periode' => 'nullable|string|max:100',
            'kategori' => 'nullable|string|max:100',
            'perihal' => 'required|string',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}
