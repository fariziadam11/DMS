<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class RemunerasiDokumenController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\RemunerasiDokumen::class;
    protected $viewPath = 'sekretariat.remunerasi-dokumen';
    protected $routePrefix = 'sekretariat.remunerasi-dokumen';
    protected $moduleName = 'Remunerasi Dokumen';
    protected $storagePath = 'documents/sekretariat/remunerasi-dokumen';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'periode' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'perihal' => 'nullable|string',
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
            'periode' => 3,
            'kategori' => 4,
            'perihal' => 5,
            'sifat_dokumen' => 6,
        ];
    }
}
