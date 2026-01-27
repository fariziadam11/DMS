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
        $version = DocumentVersion::with('document')->findOrFail($id);

        // 1. Existence Check
        if (!$version->file) {
            abort(404, 'File tidak ditemukan');
        }

        // 2. Security Check (Delegated to Parent Document Logic)
        // If parent document exists, use its robust permission check (handling Rahasia, Internal, Permissions)
        if ($version->document) {
            if (method_exists($version->document, 'userHasFileAccess')) {
                if (!$version->document->userHasFileAccess(auth()->id())) {
                     abort(403, 'Anda tidak memiliki izin untuk mengakses dokumen ini (Klasifikasi: ' . $version->document->sifat_dokumen . ').');
                }
            } else {
                // Fallback for models without trait (shouldn't happen for main docs)
                // Default to Division Check
                $user = auth()->user();
                if (!$user->isSuperAdmin() && $version->uploader && $version->uploader->id_divisi != $user->id_divisi) {
                     abort(403, 'Akses ditolak (Divisi berbeda).');
                }
            }
        } else {
             // Parent deleted? Allow if SuperAdmin, else Deny?
             // Or allow if user is uploader.
             if (auth()->id() != $version->uploaded_by && !auth()->user()->isSuperAdmin()) {
                 abort(403, 'Dokumen induk tidak ditemukan.');
             }
        }

        // Path: 'versions/' . $version->file (Based on BaseDocumentController)
        $path = 'versions/' . $version->file;

        if (!\Illuminate\Support\Facades\Storage::exists($path)) {
            // Check fallback path (legacy maybe?)
            abort(404, 'File fisik tidak ditemukan di storage');
        }

        return \Illuminate\Support\Facades\Storage::download($path, $version->file_name);
    }
}
