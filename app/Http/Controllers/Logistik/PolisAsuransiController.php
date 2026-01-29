<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PolisAsuransiController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\PolisAsuransi::class;
    protected $viewPath = 'logistik.polis-asuransi';
    protected $routePrefix = 'logistik.polis-asuransi';
    protected $moduleName = 'Polis Asuransi';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'required|integer|digits:4',
            'berlaku_mulai' => 'nullable|date',
            'berlaku_akhir' => 'nullable|date',
            'nilai' => 'nullable|string|max:255',
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
            'berlaku_mulai' => 2,
            'berlaku_akhir' => 3,
            'nilai' => 4,
            'sifat_dokumen' => 5,
        ];
    }
}
