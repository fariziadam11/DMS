<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PelaporanPrbcController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\PelaporanPrbc::class;
    protected $viewPath = 'logistik.pelaporan-prbc';
    protected $routePrefix = 'logistik.pelaporan-prbc';
    protected $moduleName = 'Pelaporan PRBC';
    protected $storagePath = 'documents/logistik/pelaporan-prbc';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'bulan' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
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
            'bulan' => 1,
            'tanggal' => 2,
            'sifat_dokumen' => 3,
        ];
    }
}
