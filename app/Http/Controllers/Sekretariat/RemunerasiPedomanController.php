<?php

namespace App\Http\Controllers\Sekretariat;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class RemunerasiPedomanController extends BaseDocumentController
{
    protected $model = \App\Models\Sekretariat\RemunerasiPedoman::class;
    protected $viewPath = 'sekretariat.remunerasi-pedoman';
    protected $routePrefix = 'sekretariat.remunerasi-pedoman';
    protected $moduleName = 'Remunerasi Pedoman';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'periode' => 'nullable|string|max:255',
            'perihal' => 'nullable|string',
            'masa_akhir' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia'
        ]);
    }
}
