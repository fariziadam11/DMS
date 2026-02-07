<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PengadaanController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\Pengadaan::class;
    protected $viewPath = 'sekretariat.pengadaan';
    protected $routePrefix = 'sekretariat.pengadaan';
    protected $moduleName = 'Pengadaan';
    protected $storagePath = 'documents/sekretariat/pengadaan';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'kategori' => 'nullable|string|max:255',
            'perihal' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'masa_akhir' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia'
        ]);
    }

    /**
     * Configuration for Excel Import
     */
    protected function getImportConfig()
    {
        return [
            'nomor' => 1,
            'tanggal' => 2,
            'kategori' => 3,
            'perihal' => 4,
            'tujuan' => 5,
            'masa_akhir' => 6,
            'sifat_dokumen' => 7,
        ];
    }
}
