<?php

namespace App\Http\Controllers\Anggaran;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class RencanaKerjaDirektoratController extends BaseDocumentController
{
    protected $model = \App\Models\Anggaran\RencanaKerjaDirektorat::class;
    protected $viewPath = 'anggaran.rencana-kerja-direktorat';
    protected $routePrefix = 'anggaran.rencana-kerja-direktorat';
    protected $moduleName = 'Rencana Kerja Direktorat';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'direktorat' => 'nullable|string|max:100',
            'judul' => 'nullable|string|max:100',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
