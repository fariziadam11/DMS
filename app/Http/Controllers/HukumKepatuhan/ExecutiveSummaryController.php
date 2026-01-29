<?php

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class ExecutiveSummaryController extends BaseDocumentController
{
    protected $model = \App\Models\HukumKepatuhan\ExecutiveSummary::class;
    protected $viewPath = 'hukum-kepatuhan.executive-summary';
    protected $routePrefix = 'hukum-kepatuhan.executive-summary';
    protected $moduleName = 'Executive Summary';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'perihal' => 'nullable|string',
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
            'perihal' => 1,
            'sifat_dokumen' => 2,
        ];
    }
}
