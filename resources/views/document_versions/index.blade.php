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
                        <th>Catatan</th>
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
                                @else
                                    <span class="text-muted text-xs">Dokumen Terhapus</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">v{{ $ver->version_number }}</span></td>
                            <td>{{ $ver->formatted_file_size }}</td>
                            <td>{{ $ver->uploader->name ?? '-' }}</td>
                            <td>{{ $ver->upload_date->format('d M Y H:i') }}</td>
                            <td>{{ Str::limit($ver->change_notes ?? '-', 30) }}</td>
                            <td>
                                <!-- Download works via BaseDocumentController or we need a route -->
                                <!-- Usually DocumentVersionController handles download, or BaseDocumentController -->
                                <!-- BaseDocumentController has downloadVersion. But it needs Controller Instance. -->
                                <!-- Easier to use direct storage link if public? No, secure. -->
                                <!-- We need a download route for versions. -->
                                <!-- I will assume there isn't one globally available easily without knowing parent controller. -->
                                <!-- But I can make a download route in DocumentVersionController! -->
                                <a href="{{ route('document-versions.download', $ver->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-download"></i>
                                </a>
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
