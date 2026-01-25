<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class VendorSatisfactionController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\VendorSatisfaction::class;
    protected $viewPath = 'logistik.vendor-satisfaction';
    protected $routePrefix = 'logistik.vendor-satisfaction';
    protected $moduleName = 'Vendor Satisfaction';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'required|integer|digits:4',
            'tanggal' => 'nullable|date',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia'
        ]);
    }
}
