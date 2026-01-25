<?php
// Hukum Kepatuhan Module Controllers

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use App\Models\HukumKepatuhan\KajianHukum;
use App\Models\HukumKepatuhan\LegalMemo;
use App\Models\HukumKepatuhan\RegulasiInternal;
use App\Models\HukumKepatuhan\RegulasiExternal;
use App\Models\HukumKepatuhan\Kontrak;
use App\Models\HukumKepatuhan\Putusan;
use App\Models\HukumKepatuhan\ComplianceCheck;
use App\Models\HukumKepatuhan\Penomoran;
use App\Models\HukumKepatuhan\LembarRekomendasi;
use App\Models\HukumKepatuhan\LembarKeputusan;
use App\Models\HukumKepatuhan\ExecutiveSummary;
use Illuminate\Http\Request;

class KajianHukumController extends BaseDocumentController
{
    protected $model = KajianHukum::class;
    protected $viewPath = 'hukum-kepatuhan.kajian-hukum';
    protected $routePrefix = 'hukum-kepatuhan.kajian-hukum';
    protected $moduleName = 'Hukum & Kepatuhan - Kajian Hukum';
    protected $storagePath = 'documents/hukum-kepatuhan/kajian-hukum';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'judul' => 'required|string',
            'tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class LegalMemoController extends BaseDocumentController
{
    protected $model = LegalMemo::class;
    protected $viewPath = 'hukum-kepatuhan.legal-memo';
    protected $routePrefix = 'hukum-kepatuhan.legal-memo';
    protected $moduleName = 'Hukum & Kepatuhan - Legal Memo';
    protected $storagePath = 'documents/hukum-kepatuhan/legal-memo';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'judul' => 'required|string',
            'tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class RegulasiInternalController extends BaseDocumentController
{
    protected $model = RegulasiInternal::class;
    protected $viewPath = 'hukum-kepatuhan.regulasi-internal';
    protected $routePrefix = 'hukum-kepatuhan.regulasi-internal';
    protected $moduleName = 'Hukum & Kepatuhan - Regulasi Internal';
    protected $storagePath = 'documents/hukum-kepatuhan/regulasi-internal';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'judul' => 'required|string',
            'tanggal' => 'nullable|date',
            'inisiator' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class RegulasiExternalController extends BaseDocumentController
{
    protected $model = RegulasiExternal::class;
    protected $viewPath = 'hukum-kepatuhan.regulasi-external';
    protected $routePrefix = 'hukum-kepatuhan.regulasi-external';
    protected $moduleName = 'Hukum & Kepatuhan - Regulasi External';
    protected $storagePath = 'documents/hukum-kepatuhan/regulasi-external';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'judul' => 'required|string',
            'tanggal' => 'nullable|date',
            'status' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class KontrakController extends BaseDocumentController
{
    protected $model = Kontrak::class;
    protected $viewPath = 'hukum-kepatuhan.kontrak';
    protected $routePrefix = 'hukum-kepatuhan.kontrak';
    protected $moduleName = 'Hukum & Kepatuhan - Kontrak';
    protected $storagePath = 'documents/hukum-kepatuhan/kontrak';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'judul' => 'required|string',
            'tanggal' => 'nullable|date',
            'berlaku_awal' => 'nullable|string|max:100',
            'berlaku_akhir' => 'nullable|string|max:100',
            'jenis' => 'nullable|string|max:100',
            'pihak' => 'nullable|string|max:100',
            'pemilik' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class PutusanController extends BaseDocumentController
{
    protected $model = Putusan::class;
    protected $viewPath = 'hukum-kepatuhan.putusan';
    protected $routePrefix = 'hukum-kepatuhan.putusan';
    protected $moduleName = 'Hukum & Kepatuhan - Putusan';
    protected $storagePath = 'documents/hukum-kepatuhan/putusan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'klarifikasi' => 'nullable|string|max:255',
            'tingkat_pengadilan' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:100',
            'pihak' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class ComplianceCheckController extends BaseDocumentController
{
    protected $model = ComplianceCheck::class;
    protected $viewPath = 'hukum-kepatuhan.compliance-check';
    protected $routePrefix = 'hukum-kepatuhan.compliance-check';
    protected $moduleName = 'Hukum & Kepatuhan - Compliance Check';
    protected $storagePath = 'documents/hukum-kepatuhan/compliance-check';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'perihal' => 'required|string',
            'tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class PenomoranController extends BaseDocumentController
{
    protected $model = Penomoran::class;
    protected $viewPath = 'hukum-kepatuhan.penomoran';
    protected $routePrefix = 'hukum-kepatuhan.penomoran';
    protected $moduleName = 'Hukum & Kepatuhan - Penomoran';
    protected $storagePath = 'documents/hukum-kepatuhan/penomoran';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'perihal' => 'required|string',
            'keterangan' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class LembarRekomendasiController extends BaseDocumentController
{
    protected $model = LembarRekomendasi::class;
    protected $viewPath = 'hukum-kepatuhan.lembar-rekomendasi';
    protected $routePrefix = 'hukum-kepatuhan.lembar-rekomendasi';
    protected $moduleName = 'Hukum & Kepatuhan - Lembar Rekomendasi';
    protected $storagePath = 'documents/hukum-kepatuhan/lembar-rekomendasi';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'perihal' => 'required|string',
            'keterangan' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class LembarKeputusanController extends BaseDocumentController
{
    protected $model = LembarKeputusan::class;
    protected $viewPath = 'hukum-kepatuhan.lembar-keputusan';
    protected $routePrefix = 'hukum-kepatuhan.lembar-keputusan';
    protected $moduleName = 'Hukum & Kepatuhan - Lembar Keputusan';
    protected $storagePath = 'documents/hukum-kepatuhan/lembar-keputusan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'perihal' => 'required|string',
            'keterangan' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}

class ExecutiveSummaryController extends BaseDocumentController
{
    protected $model = ExecutiveSummary::class;
    protected $viewPath = 'hukum-kepatuhan.executive-summary';
    protected $routePrefix = 'hukum-kepatuhan.executive-summary';
    protected $moduleName = 'Hukum & Kepatuhan - Executive Summary';
    protected $storagePath = 'documents/hukum-kepatuhan/executive-summary';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'perihal' => 'required|string',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:10240',
        ]);
    }
}
