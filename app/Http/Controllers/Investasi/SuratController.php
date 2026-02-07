<?php

namespace App\Http\Controllers\Investasi;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class SuratController extends BaseDocumentController
{
    protected $model = \App\Models\Investasi\Surat::class;
    protected $viewPath = 'investasi.surat';
    protected $routePrefix = 'investasi.surat';
    protected $moduleName = 'Investasi - Surat';
    protected $storagePath = 'documents/investasi/surat';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tgl_surat' => 'nullable|date',
            'type' => 'required|in:1,2',
            'no_surat' => 'nullable|string|max:100',
            'no_agenda' => 'nullable|string|max:100',
            'perihal' => 'nullable|string|max:100',
            'jenis' => 'nullable|in:1,2',
            'nama_perusahaan' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|integer',
            'keterangan' => 'nullable|string',
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
            'tgl_surat' => 1,
            'no_surat' => 2,
            'no_agenda' => 3,
            'perihal' => 4,
            'nama_perusahaan' => 5,
            'keterangan' => 6,
            'sifat_dokumen' => 7,
        ];
    }
}
