<?php
// Investasi Module Controllers

namespace App\Http\Controllers\Investasi;

use App\Http\Controllers\BaseDocumentController;
use App\Models\Investasi\Surat;
use App\Models\Investasi\PerencanaanTransaksi;
use App\Models\Investasi\PerencanaanSurat;
use App\Models\Investasi\PropensaTransaksi;
use App\Models\Investasi\PropensaSurat;
use Illuminate\Http\Request;

class SuratController extends BaseDocumentController
{
    protected $model = Surat::class;
    protected $viewPath = 'investasi.surat';
    protected $routePrefix = 'investasi.surat';
    protected $moduleName = 'Investasi - Surat';
    protected $storagePath = 'documents/investasi/surat';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tgl_surat' => 'nullable|date',
            'type' => 'required|in:1,2',
            'no_surat' => 'nullable|string|max:100',
            'no_agenda' => 'nullable|string|max:100',
            'perihal' => 'nullable|string|max:100',
            'jenis' => 'nullable|in:1,2',
            'nama_perusahaan' => 'nullable|string|max:100',
            'klasifikasi' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class PerencanaanTransaksiController extends BaseDocumentController
{
    protected $model = PerencanaanTransaksi::class;
    protected $viewPath = 'investasi.perencanaan-transaksi';
    protected $routePrefix = 'investasi.perencanaan-transaksi';
    protected $moduleName = 'Investasi - Perencanaan Transaksi';
    protected $storagePath = 'documents/investasi/perencanaan-transaksi';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'type' => 'nullable|integer',
            'tanggal' => 'nullable|date',
            'perihal' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}

class PerencanaanSuratController extends BaseDocumentController
{
    protected $model = PerencanaanSurat::class;
    protected $viewPath = 'investasi.perencanaan-surat';
    protected $routePrefix = 'investasi.perencanaan-surat';
    protected $moduleName = 'Investasi - Perencanaan Surat';
    protected $storagePath = 'documents/investasi/perencanaan-surat';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'type' => 'nullable|integer',
            'tanggal' => 'nullable|date',
            'nomor' => 'nullable|string|max:50',
            'perihal' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class PropensaTransaksiController extends BaseDocumentController
{
    protected $model = PropensaTransaksi::class;
    protected $viewPath = 'investasi.propensa-transaksi';
    protected $routePrefix = 'investasi.propensa-transaksi';
    protected $moduleName = 'Investasi - Propensa Transaksi';
    protected $storagePath = 'documents/investasi/propensa-transaksi';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'type' => 'nullable|integer',
            'tanggal' => 'nullable|date',
            'perihal' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}

class PropensaSuratController extends BaseDocumentController
{
    protected $model = PropensaSurat::class;
    protected $viewPath = 'investasi.propensa-surat';
    protected $routePrefix = 'investasi.propensa-surat';
    protected $moduleName = 'Investasi - Propensa Surat';
    protected $storagePath = 'documents/investasi/propensa-surat';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'type' => 'nullable|integer',
            'tanggal' => 'nullable|date',
            'nomor' => 'nullable|string|max:50',
            'perihal' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}
