<?php
// Anggaran Module Controllers

namespace App\Http\Controllers\Anggaran;

use App\Http\Controllers\BaseDocumentController;
use App\Models\Anggaran\AturanKebijakan;
use App\Models\Anggaran\DokumenRra;
use App\Models\Anggaran\LaporanPrbc;
use App\Models\Anggaran\RencanaKerjaDirektorat;
use App\Models\Anggaran\RencanaKerjaTahunan;
use App\Models\Anggaran\RencanaKerjaTriwulan;
use Illuminate\Http\Request;

class AturanKebijakanController extends BaseDocumentController
{
    protected $model = AturanKebijakan::class;
    protected $viewPath = 'anggaran.aturan-kebijakan';
    protected $routePrefix = 'anggaran.aturan-kebijakan';
    protected $moduleName = 'Anggaran - Aturan Kebijakan';
    protected $storagePath = 'documents/anggaran/aturan-kebijakan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'nomor' => 'nullable|string|max:100',
            'judul' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}

class DokumenRraController extends BaseDocumentController
{
    protected $model = DokumenRra::class;
    protected $viewPath = 'anggaran.dokumen-rra';
    protected $routePrefix = 'anggaran.dokumen-rra';
    protected $moduleName = 'Anggaran - Dokumen RRA';
    protected $storagePath = 'documents/anggaran/dokumen-rra';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'direktorat' => 'nullable|string|max:100',
            'nomor_nota' => 'nullable|string|max:100',
            'nomor_sukka' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}

class LaporanPrbcController extends BaseDocumentController
{
    protected $model = LaporanPrbc::class;
    protected $viewPath = 'anggaran.laporan-prbc';
    protected $routePrefix = 'anggaran.laporan-prbc';
    protected $moduleName = 'Anggaran - Laporan PRBC';
    protected $storagePath = 'documents/anggaran/laporan-prbc';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'bulan' => 'nullable|integer|min:1|max:12',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}

class RencanaKerjaDirektoratController extends BaseDocumentController
{
    protected $model = RencanaKerjaDirektorat::class;
    protected $viewPath = 'anggaran.rencana-kerja-direktorat';
    protected $routePrefix = 'anggaran.rencana-kerja-direktorat';
    protected $moduleName = 'Anggaran - Rencana Kerja Direktorat';
    protected $storagePath = 'documents/anggaran/rencana-kerja-direktorat';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'direktorat' => 'nullable|string|max:100',
            'judul' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}

class RencanaKerjaTahunanController extends BaseDocumentController
{
    protected $model = RencanaKerjaTahunan::class;
    protected $viewPath = 'anggaran.rencana-kerja-tahunan';
    protected $routePrefix = 'anggaran.rencana-kerja-tahunan';
    protected $moduleName = 'Anggaran - Rencana Kerja Tahunan';
    protected $storagePath = 'documents/anggaran/rencana-kerja-tahunan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'judul' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}

class RencanaKerjaTriwulanController extends BaseDocumentController
{
    protected $model = RencanaKerjaTriwulan::class;
    protected $viewPath = 'anggaran.rencana-kerja-triwulan';
    protected $routePrefix = 'anggaran.rencana-kerja-triwulan';
    protected $moduleName = 'Anggaran - Rencana Kerja Triwulan';
    protected $storagePath = 'documents/anggaran/rencana-kerja-triwulan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'judul' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}
