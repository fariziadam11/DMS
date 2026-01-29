<?php

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class KajianHukumController extends BaseDocumentController
{
    protected $model = \App\Models\HukumKepatuhan\KajianHukum::class;
    protected $viewPath = 'hukum-kepatuhan.kajian-hukum';
    protected $routePrefix = 'hukum-kepatuhan.kajian-hukum';
    protected $moduleName = 'Kajian Hukum';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'judul' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string|max:100',
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
            'judul' => 1,
            'tanggal' => 2,
            'keterangan' => 3,
            'sifat_dokumen' => 4,
        ];
    }
}
