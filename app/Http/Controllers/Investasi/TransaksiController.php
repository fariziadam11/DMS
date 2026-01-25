<?php

namespace App\Http\Controllers\Investasi;

use App\Http\Controllers\BaseDocumentController;
use App\Models\Investasi\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends BaseDocumentController
{
    protected $model = Transaksi::class;
    protected $viewPath = 'investasi.transaksi';
    protected $routePrefix = 'investasi.transaksi';
    protected $moduleName = 'Investasi - Transaksi';
    protected $storagePath = 'documents/investasi/transaksi';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'type' => 'required|in:1,2',
            'tgl_transaksi' => 'nullable|date',
            'perihal' => 'nullable|string|max:250',
            'saham' => 'nullable|string|max:100',
            'broker' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }

    public function index(Request $request)
    {
        $query = $this->model::query();

        if (!auth()->user()->isSuperAdmin()) {
            $query->accessibleByUser(auth()->id());
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->globalSearch($request->search);
        }
        if ($request->filled('id_divisi')) {
            $query->byDivision($request->id_divisi);
        }

        $data = $query->with('divisi')
            ->orderBy('tgl_transaksi', 'desc')
            ->paginate(15);

        return view($this->viewPath . '.index', [
            'data' => $data,
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'divisions' => $this->getUserDivisions(),
            'permissions' => $this->getPermissions(),
        ]);
    }
}
