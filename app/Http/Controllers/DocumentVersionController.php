<?php

namespace App\Http\Controllers;

use App\Models\DocumentVersion;
use Illuminate\Http\Request;

class DocumentVersionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List all archived (non-current) document versions
     */
    public function index(Request $request)
    {
        $query = DocumentVersion::with(['uploader', 'document'])
            ->where('is_current', false); // Only Old Archives

        // Filter by Division (Same Division Logic)
        // "hanya list file dari divisi itu sendiri"
        $user = auth()->user();
        if (!$user->isSuperAdmin()) {
            if ($user->id_divisi) {
                // Approximate: Check uploader's division
                $query->whereHas('uploader', function ($q) use ($user) {
                    $q->where('id_divisi', $user->id_divisi);
                });
            } else {
                // If user has no division? Maybe show nothing or own uploads?
                 $query->where('uploaded_by', $user->id);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                  ->orWhere('change_notes', 'like', "%{$search}%");
            });
        }

        $versions = $query->latest('upload_date')->paginate(15);

        return view('document_versions.index', compact('versions'));
    }

    public function download($id)
    {
        $version = DocumentVersion::findOrFail($id);

        // Division Check (Security)
        $user = auth()->user();
        if (!$user->isSuperAdmin()) {
            // Check if uploader is in same division -> Approximation
            if ($version->uploader && $version->uploader->id_divisi != $user->id_divisi) {
                abort(403, 'Anda tidak memiliki akses ke dokumen dari divisi ini.');
            }
        }

        if (!$version->file) {
            abort(404, 'File tidak ditemukan');
        }

        // Path: 'versions/' . $version->file (Based on BaseDocumentController)
        $path = 'versions/' . $version->file;

        if (!\Illuminate\Support\Facades\Storage::exists($path)) {
            // Try 'documents/' just in case? No, versions usually in versions/
            abort(404, 'File fisik tidak ditemukan');
        }

        return \Illuminate\Support\Facades\Storage::download($path, $version->file_name);
    }
}
