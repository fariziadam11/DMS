<?php

namespace App\Http\Controllers\Investasi;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PropensaSuratController extends BaseDocumentController
{
    protected $model = \App\Models\Investasi\PropensaSurat::class;
    protected $viewPath = 'investasi.propensa-surat';
    protected $routePrefix = 'investasi.propensa-surat';
    protected $moduleName = 'Propensa Surat';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'type' => 'nullable|string|max:50',
            'judul' => 'nullable|string|max:255',
            'perihal' => 'nullable|string|max:255',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Configuration for Excel Import
     */
    protected function getImportConfig()
    {
        return [
            'type' => 1,
            'judul' => 2,
            'perihal' => 3,
            'nomor' => 4,
            'tanggal' => 5,
            'sifat_dokumen' => 6,
        ];
    }
}
