<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class SuratBayarController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\SuratBayar::class;
    protected $viewPath = 'keuangan.surat-bayar';
    protected $routePrefix = 'keuangan.surat-bayar';
    protected $moduleName = 'Keuangan - Surat Bayar';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal_surat_bayar' => 'nullable|date',
            'nomor_surat_bayar' => 'nullable|string|max:100',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class SpbController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Spb::class;
    protected $viewPath = 'keuangan.spb';
    protected $routePrefix = 'keuangan.spb';
    protected $moduleName = 'Keuangan - SPB';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_surat_bayar' => 'required|exists:keuangan_bendaharapajak_surat,id',
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal_spb' => 'nullable|date',
            'nomor_spb' => 'nullable|string|max:100',
            'nominal' => 'nullable|numeric',
            'tujuan' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class SppbController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Sppb::class;
    protected $viewPath = 'keuangan.sppb';
    protected $routePrefix = 'keuangan.sppb';
    protected $moduleName = 'Keuangan - SPPB';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_surat_bayar' => 'required',
            'id_spb' => 'required',
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal_sppb' => 'nullable|date',
            'nomor_sppb' => 'nullable|string|max:100',
            'nominal' => 'nullable|numeric',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class CashflowController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Cashflow::class;
    protected $viewPath = 'keuangan.cashflow';
    protected $routePrefix = 'keuangan.cashflow';
    protected $moduleName = 'Keuangan - Cashflow';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'nominal' => 'nullable|numeric',
            'uraian' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class PenempatanController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Penempatan::class;
    protected $viewPath = 'keuangan.penempatan';
    protected $routePrefix = 'keuangan.penempatan';
    protected $moduleName = 'Keuangan - Penempatan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'nominal' => 'nullable|numeric',
            'uraian' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class PemindahbukuanController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Pemindahbukuan::class;
    protected $viewPath = 'keuangan.pemindahbukuan';
    protected $routePrefix = 'keuangan.pemindahbukuan';
    protected $moduleName = 'Keuangan - Pemindahbukuan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'nominal' => 'nullable|numeric',
            'uraian' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}

class PajakController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Pajak::class;
    protected $viewPath = 'keuangan.pajak';
    protected $routePrefix = 'keuangan.pajak';
    protected $moduleName = 'Keuangan - Pajak';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'masa' => 'nullable|string|max:50',
            'tahun' => 'nullable|integer',
            'uraian' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|max:10240',
        ]);
    }
}
