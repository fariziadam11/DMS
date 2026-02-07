<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class RisalahRapatController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\RisalahRapat::class;
    protected $viewPath = 'sekretariat.risalah-rapat';
    protected $routePrefix = 'sekretariat.risalah-rapat';
    protected $moduleName = 'Risalah Rapat';
    protected $storagePath = 'documents/sekretariat/risalah-rapat';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'periode' => 'nullable|string|max:25',
            'kategori' => 'required|string|max:50',
            'perihal' => 'nullable|string|max:65535',
            'peserta' => 'nullable|string|max:65535',
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
            'nomor' => 1,
            'tanggal' => 2,
            'periode' => 3,
            'kategori' => 4,
            'perihal' => 5,
            'peserta' => 6,
            'sifat_dokumen' => 7,
        ];
    }
}
