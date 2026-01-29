<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\BaseDocumentController;
use App\Models\Akuntansi\AturanKebijakan;
use Illuminate\Http\Request;

class AturanKebijakanController extends BaseDocumentController
{
    protected $model = AturanKebijakan::class;
    protected $viewPath = 'akuntansi.aturan-kebijakan';
    protected $routePrefix = 'akuntansi.aturan-kebijakan';
    protected $moduleName = 'Akuntansi - Aturan Kebijakan';
    protected $storagePath = 'documents/akuntansi/aturan-kebijakan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'nomor' => 'nullable|string|max:100',
            'judul' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
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
            'sifat_dokumen' => 4,
        ];
    }
}
