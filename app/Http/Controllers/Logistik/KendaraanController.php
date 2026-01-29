<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class KendaraanController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\Kendaraan::class;
    protected $viewPath = 'logistik.kendaraan';
    protected $routePrefix = 'logistik.kendaraan';
    protected $moduleName = 'Kendaraan';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'required|integer|digits:4',
            'bulan' => 'nullable|string|max:255',
            'nama_kegiatan' => 'nullable|string',
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
            'tahun' => 1,
            'bulan' => 2,
            'nama_kegiatan' => 3,
            'sifat_dokumen' => 4,
        ];
    }
}
