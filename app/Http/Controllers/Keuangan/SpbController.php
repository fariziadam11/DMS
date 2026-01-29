<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\BaseDocumentController;
use App\Models\Keuangan\SuratBayar;
use Illuminate\Http\Request;

class SpbController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Spb::class;
    protected $viewPath = 'keuangan.spb';
    protected $routePrefix = 'keuangan.spb';
    protected $moduleName = 'SPB';

    public function create()
    {
        $suratBayars = SuratBayar::orderBy('tanggal_surat_bayar', 'desc')->get();
        return view($this->viewPath . '.create', [
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'divisions' => $this->getUserDivisions(),
            'suratBayars' => $suratBayars,
        ]);
    }

    public function edit($id)
    {
        $record = $this->model::findOrFail($id);
        $this->authorizeAccess($record, 'write');
        $suratBayars = SuratBayar::orderBy('tanggal_surat_bayar', 'desc')->get();

        return view($this->viewPath . '.edit', [
            'record' => $record,
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'divisions' => $this->getUserDivisions(),
            'suratBayars' => $suratBayars,
        ]);
    }

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_surat_bayar' => 'required|exists:keuangan_bendaharapajak_surat,id',
            'id_divisi' => 'nullable|exists:master_divisi,id',
            'tanggal_spb' => 'nullable|date',
            'nomor_spb' => 'nullable|string|max:100',
            'nominal' => 'nullable|numeric',
            'tujuan' => 'nullable|string|max:255',
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
            'nomor_spb' => 1,
            'tanggal_spb' => 2,
            'nominal' => 3,
            'tujuan' => 4,
        ];
    }
}
