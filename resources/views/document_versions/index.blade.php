@extends('layouts.app')

@section('title', 'Document Version (Old Archive)')

@section('breadcrumb')
    <li class="breadcrumb-item active">Document Version</li>
@endsection

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Document Version (Old Archive)</h1>
            <p class="text-muted mb-0">Arsip dokumen versi sebelumnya</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama file atau catatan..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nama File</th>
                        <th>Dokumen Induk</th>
                        <th>Versi</th>
                        <th>Ukuran</th>
                        <th>Diunggah Oleh</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($versions as $ver)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2 fs-5"></i>
                                    <span>{{ $ver->file_name }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($ver->document)
                                    <small class="text-muted">
                                        {{ $ver->document->judul ?? ($ver->document->perihal ?? ($ver->document->nama ?? ($ver->document->nomor ?? 'Dokumen #' . $ver->document_id))) }}
                                    </small>
                                    <br>
                                    <span class="badge bg-secondary" style="font-size: 0.65rem;">
                                        {{ Str::title(str_replace('_', ' ', $ver->document_type)) }}
                                    </span>

                                    <!-- Classification Badge -->
                                    <div class="mt-1">
                                        @if ($ver->document->sifat_dokumen == 'Rahasia')
                                            <span class="badge badge-secret" style="font-size: 0.65rem;"><i
                                                    class="bi bi-lock me-1"></i>Rahasia</span>
                                        @elseif($ver->document->sifat_dokumen == 'Internal')
                                            <span class="badge bg-warning text-dark" style="font-size: 0.65rem;"><i
                                                    class="bi bi-shield-lock me-1"></i>Internal</span>
                                        @else
                                            <span class="badge badge-public" style="font-size: 0.65rem;"><i
                                                    class="bi bi-unlock me-1"></i>Umum</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted text-xs">Dokumen Terhapus</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">v{{ $ver->version_number }}</span></td>
                            <td>{{ $ver->formatted_file_size }}</td>
                            <td>{{ $ver->uploader->name ?? '-' }}</td>
                            <td>{{ $ver->upload_date->format('d M Y H:i') }}</td>
                            <td>
                                @php
                                    $canDownload = false;
                                    if (auth()->user()->isSuperAdmin() || $ver->uploaded_by == auth()->id()) {
                                        $canDownload = true;
                                    } elseif ($ver->document && method_exists($ver->document, 'userHasFileAccess')) {
                                        $canDownload = $ver->document->userHasFileAccess(auth()->id());
                                    }
                                @endphp

                                @if ($canDownload)
                                    <a href="{{ route('document-versions.download', $ver->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#requestModal" data-type="{{ $ver->document_type }}"
                                        data-id="{{ $ver->document_id }}"
                                        data-title="{{ $ver->document->judul ?? ($ver->document->perihal ?? ($ver->document->nama ?? ($ver->document->nomor ?? 'Dokumen #' . $ver->document_id))) }}"
                                        title="Minta Akses Dokumen">
                                        <i class="bi bi-key"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada dokumen versi lama</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($versions->hasPages())
            <div class="card-footer">{{ $versions->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection
