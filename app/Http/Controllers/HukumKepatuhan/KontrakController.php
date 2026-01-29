<?php

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class KontrakController extends BaseDocumentController
{
    protected $model = \App\Models\HukumKepatuhan\Kontrak::class;
    protected $viewPath = 'hukum-kepatuhan.kontrak';
    protected $routePrefix = 'hukum-kepatuhan.kontrak';
    protected $moduleName = 'Kontrak';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'judul' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'berlaku_awal' => 'nullable|string|max:100',
            'berlaku_akhir' => 'nullable|string|max:100',
            'jenis' => 'nullable|string|max:100',
            'pihak' => 'nullable|string|max:100',
            'pemilik' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:100',
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
            'nomor' => 1,
            'judul' => 2,
            'tanggal' => 3,
            'berlaku_awal' => 4,
            'berlaku_akhir' => 5,
            'jenis' => 6,
            'pihak' => 7,
            'pemilik' => 8,
            'status' => 9,
            'sifat_dokumen' => 10,
        ];
    }
}
