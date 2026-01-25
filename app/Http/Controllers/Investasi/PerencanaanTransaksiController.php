<?php

namespace App\Http\Controllers\Investasi;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PerencanaanTransaksiController extends BaseDocumentController
{
    protected $model = \App\Models\Investasi\PerencanaanTransaksi::class;
    protected $viewPath = 'investasi.perencanaan-transaksi';
    protected $routePrefix = 'investasi.perencanaan-transaksi';
    protected $moduleName = 'Perencanaan Transaksi';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'judul' => 'nullable|string|max:255',
            'perihal' => 'nullable|string|max:255',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
