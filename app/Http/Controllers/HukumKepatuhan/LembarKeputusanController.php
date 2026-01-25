<?php

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class LembarKeputusanController extends BaseDocumentController
{
    protected $model = \App\Models\HukumKepatuhan\LembarKeputusan::class;
    protected $viewPath = 'hukum-kepatuhan.lembar-keputusan';
    protected $routePrefix = 'hukum-kepatuhan.lembar-keputusan';
    protected $moduleName = 'Lembar Keputusan';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'perihal' => 'nullable|string',
            'keterangan' => 'nullable|string|max:100',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
