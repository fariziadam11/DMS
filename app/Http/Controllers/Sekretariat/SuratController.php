<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class SuratController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\Surat::class;
    protected $viewPath = 'sekretariat.surat';
    protected $routePrefix = 'sekretariat.surat';
    protected $moduleName = 'Surat';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'kategori' => 'nullable|string|max:255',
            'perihal' => 'nullable|string',
            'dari' => 'nullable|string|max:255',
            'tujuan' => 'nullable|string|max:255',
            'disposisi' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia'
        ]);
    }

    /**
     * Configuration for Excel Import
     * Maps Database Column => Excel Column Index (1-based)
     */
    protected function getImportConfig()
    {
        return [
            'nomor' => 1,
            'tanggal' => 2,
            'kategori' => 3,
            'perihal' => 4,
            'dari' => 5,
            'tujuan' => 6,
            'disposisi' => 7,
            'file_name' => 8, // Assuming file name text is in col 8
            'sifat_dokumen' => 9,
        ];
    }
}
