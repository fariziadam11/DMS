<?php

namespace App\Http\Controllers\Anggaran;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class DokumenRraController extends BaseDocumentController
{
    protected $model = \App\Models\Anggaran\DokumenRra::class;
    protected $viewPath = 'anggaran.dokumen-rra';
    protected $routePrefix = 'anggaran.dokumen-rra';
    protected $moduleName = 'Dokumen RRA';
    protected $storagePath = 'documents/anggaran/dokumen-rra';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'direktorat' => 'nullable|string|max:100',
            'nomor_nota' => 'nullable|string|max:100',
            'nomor_sukka' => 'nullable|string|max:255',
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
            'tanggal' => 1,
            'direktorat' => 2,
            'nomor_nota' => 3,
            'nomor_sukka' => 4,
            'sifat_dokumen' => 5,
        ];
    }
}
