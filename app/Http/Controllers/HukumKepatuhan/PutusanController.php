<?php

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PutusanController extends BaseDocumentController
{
    protected $model = \App\Models\HukumKepatuhan\Putusan::class;
    protected $viewPath = 'hukum-kepatuhan.putusan';
    protected $routePrefix = 'hukum-kepatuhan.putusan';
    protected $moduleName = 'Putusan';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'klarifikasi' => 'nullable|string|max:255',
            'tingkat_pengadilan' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:100',
            'pihak' => 'nullable|string|max:100',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
