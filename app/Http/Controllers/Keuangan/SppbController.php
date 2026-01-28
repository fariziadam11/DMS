<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\BaseDocumentController;
use App\Models\Keuangan\SuratBayar;
use App\Models\Keuangan\Spb;
use Illuminate\Http\Request;

class SppbController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Sppb::class;
    protected $viewPath = 'keuangan.sppb';
    protected $routePrefix = 'keuangan.sppb';
    protected $moduleName = 'SPPB';
    protected $withRelations = ['divisi', 'suratBayar', 'spb'];

    public function create()
    {
        $suratBayars = SuratBayar::orderBy('tanggal_surat_bayar', 'desc')->get();
        $spbs = Spb::orderBy('tanggal_spb', 'desc')->get();

        return view($this->viewPath . '.create', [
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'divisions' => $this->getUserDivisions(),
            'suratBayars' => $suratBayars,
            'spbs' => $spbs,
        ]);
    }

    public function edit($id)
    {
        $record = $this->model::findOrFail($id);
        $this->authorizeAccess($record, 'write');
        $suratBayars = SuratBayar::orderBy('tanggal_surat_bayar', 'desc')->get();
        $spbs = Spb::orderBy('tanggal_spb', 'desc')->get();

        return view($this->viewPath . '.edit', [
            'record' => $record,
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'divisions' => $this->getUserDivisions(),
            'suratBayars' => $suratBayars,
            'spbs' => $spbs,
        ]);
    }

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_surat_bayar' => 'required|exists:keuangan_bendaharapajak_surat,id',
            'id_spb' => 'required|exists:keuangan_bendaharapajak_spb,id',
            'id_divisi' => 'nullable|exists:master_divisi,id',
            'tanggal_sppb' => 'nullable|date',
            'nomor_sppb' => 'nullable|string|max:100',
            'nominal' => 'nullable|numeric',
            'tujuan' => 'nullable|string|max:255',
            'dibuat' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
        ]);
    }
}
