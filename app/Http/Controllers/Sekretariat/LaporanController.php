<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class LaporanController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\Laporan::class;
    protected $viewPath = 'sekretariat.laporan';
    protected $routePrefix = 'sekretariat.laporan';
    protected $moduleName = 'Laporan';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'periode' => 'nullable|string|max:25',
            'kategori' => 'required|string|max:50',
            'perihal' => 'nullable|string|max:65535',
            'tujuan' => 'nullable|string|max:255',
            'dilaporkan' => 'nullable|date',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
