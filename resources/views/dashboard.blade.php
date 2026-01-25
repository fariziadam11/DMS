@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Dashboard</h1>
        <div>
            <span class="text-muted">Selamat datang, {{ auth()->user()->name }}</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">
                                @if (auth()->user()->isSuperAdmin())
                                    {{ \App\Models\DocumentVersion::count() }}
                                @else
                                    {{ \App\Models\DocumentVersion::whereHas('uploader', fn($q) => $q->where('id_divisi', auth()->user()->id_divisi))->count() }}
                                @endif
                            </h3>
                            <small class="text-muted">Total Dokumen</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->isSuperAdmin() || auth()->user()->isDivisionAdmin())
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-clock-history text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ \App\Models\FileAccessRequest::pending()->count() }}</h3>
                                <small class="text-muted">Permintaan Akses</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-building text-info fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ \App\Models\MasterDivisi::count() }}</h3>
                                <small class="text-muted">Total Divisi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Documents & Activity -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Dokumen Terbaru</span>
                    <a href="{{ route('search') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama File</th>
                                    <th>Tipe</th>
                                    <th>Divisi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Models\DocumentVersion::with('uploader')->latest()->take(5)->get() as $doc)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                                <span>{{ Str::limit($doc->file_name, 30) }}</span>
                                            </div>
                                        </td>
                                        <td><span
                                                class="badge bg-secondary">{{ Str::title(str_replace('_', ' ', $doc->document_type)) }}</span>
                                        </td>
                                        <td>-</td>
                                        <td>{{ $doc->created_at->format('d M Y') }}</td>
                                        <td><span class="badge bg-success">v{{ $doc->version_number }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Aktivitas Terbaru</div>
                <div class="card-body">
                    @foreach (\App\Models\AuditLog::with('user')->latest()->take(5)->get() as $log)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-light rounded-circle p-2">
                                    <i
                                        class="bi bi-{{ $log->action == 'create' ? 'plus' : ($log->action == 'update' ? 'pencil' : 'eye') }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0 small"><strong>{{ $log->user?->name ?? 'System' }}</strong>
                                    {{ $log->action }} {{ $log->table_name }}</p>
                                <small class="text-muted">{{ $log->created_at?->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
