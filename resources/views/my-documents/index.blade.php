@extends('layouts.app')

@section('title', 'Dokumen Saya')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dokumen Saya</li>
@endsection

@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Dokumen Saya</h1>
        <p class="text-muted">Dokumen yang telah diizinkan untuk Anda akses</p>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Dokumen</span>
            <span class="badge bg-primary">{{ $documents->count() }} dokumen</span>
        </div>
        <div class="card-body p-0">
            @if ($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Judul Dokumen</th>
                                <th>Tipe</th>
                                <th>Divisi</th>
                                <th>Klasifikasi</th>
                                <th>Disetujui Oleh</th>
                                <th>Tanggal Disetujui</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $doc)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                            <strong>{{ $doc['title'] }}</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $doc['type_label'] }}</span></td>
                                    <td>{{ $doc['division'] }}</td>
                                    <td>
                                        @if ($doc['classification'] == 'Rahasia')
                                            <span class="badge badge-secret">
                                                <i class="bi bi-lock me-1"></i>Rahasia
                                            </span>
                                        @elseif ($doc['classification'] == 'Internal')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-building-lock me-1"></i>Internal
                                            </span>
                                        @else
                                            <span class="badge badge-public">
                                                <i class="bi bi-unlock me-1"></i>Umum
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $doc['approved_by'] }}</td>
                                    <td>{{ $doc['approved_at']->format('d M Y H:i') }}</td>
                                    <td>
                                        @if ($doc['url'])
                                            <div class="btn-group btn-group-sm">
                                    <a href="{{ $doc['url'] }}" class="btn btn-primary">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                                @if (!empty($doc['has_download_permission']) && !empty($doc['download_url']))
                                                    <a href="{{ $doc['download_url'] }}" class="btn btn-success"
                                                        title="Download">
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                @endif
                                </div>
                                        @else
                                            <span class="badge bg-warning">URL tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($pagination->hasPages())
                    <div class="card-footer">
                        {{ $pagination->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-folder2-open fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Belum Ada Dokumen</h5>
                    <p class="text-muted">Anda belum memiliki akses ke dokumen apapun.<br>
                        Gunakan fitur <a href="{{ route('search') }}">Pencarian</a> untuk menemukan dan meminta akses
                        dokumen.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
