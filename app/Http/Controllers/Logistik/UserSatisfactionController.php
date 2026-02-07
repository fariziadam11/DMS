<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class UserSatisfactionController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\UserSatisfaction::class;
    protected $viewPath = 'logistik.user-satisfaction';
    protected $routePrefix = 'logistik.user-satisfaction';
    protected $moduleName = 'User Satisfaction';
    protected $storagePath = 'documents/logistik/user-satisfaction';

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

    /**
     * Configuration for Excel Import
     */
    protected function getImportConfig()
    {
        return [
            'tahun' => 1,
            'tanggal' => 2,
            'sifat_dokumen' => 3,
        ];
    }
}
