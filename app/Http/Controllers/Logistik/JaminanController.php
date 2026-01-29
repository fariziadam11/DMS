<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class JaminanController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\Jaminan::class;
    protected $viewPath = 'logistik.jaminan';
    protected $routePrefix = 'logistik.jaminan';
    protected $moduleName = 'Jaminan';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'jenis_jaminan' => 'nullable|string|max:255',
            'nomor_drp' => 'nullable|string|max:255',
            'nama_pengadaan' => 'nullable|string',
            'vendor' => 'nullable|string|max:255',
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
            'jenis_jaminan' => 1,
            'nomor_drp' => 2,
            'nama_pengadaan' => 3,
            'vendor' => 4,
            'sifat_dokumen' => 5,
        ];
    }
}
