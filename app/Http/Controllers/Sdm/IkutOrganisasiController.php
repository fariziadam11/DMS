<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class IkutOrganisasiController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\IkutOrganisasi::class;
    protected $viewPath = 'sdm.ikut-organisasi';
    protected $routePrefix = 'sdm.ikut-organisasi';
    protected $moduleName = 'Ikut Organisasi';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nik' => 'nullable|string|max:25',
            'nama' => 'required|string|max:100',
            'status' => 'nullable|integer',
            'perihal' => 'nullable|string|max:65535',
            'tanggal' => 'nullable|date',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
        ]);
    }

    /**
     * Configuration for Excel Import
     */
    protected function getImportConfig()
    {
        return [
            'nik' => 1,
            'nama' => 2,
            'perihal' => 3,
            'tanggal' => 4,
            'sifat_dokumen' => 5,
        ];
    }
}
