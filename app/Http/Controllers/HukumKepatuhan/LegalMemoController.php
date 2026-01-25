<?php

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class LegalMemoController extends BaseDocumentController
{
    protected $model = \App\Models\HukumKepatuhan\LegalMemo::class;
    protected $viewPath = 'hukum-kepatuhan.legal-memo';
    protected $routePrefix = 'hukum-kepatuhan.legal-memo';
    protected $moduleName = 'Legal Memo';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'judul' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string|max:100',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
