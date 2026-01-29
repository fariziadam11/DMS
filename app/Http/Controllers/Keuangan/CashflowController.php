<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class CashflowController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Cashflow::class;
    protected $viewPath = 'keuangan.cashflow';
    protected $routePrefix = 'keuangan.cashflow';
    protected $moduleName = 'Cashflow';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'nominal' => 'nullable|numeric',
            'uraian' => 'nullable|string|max:255',
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
            'nominal' => 2,
            'uraian' => 3,
        ];
    }
}
