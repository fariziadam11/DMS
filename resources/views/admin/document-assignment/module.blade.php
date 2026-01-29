@extends('layouts.app')
@section('title', 'Kelola Dokumen - ' . $moduleName)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.document-assignment.index') }}">Document Assignment</a></li>
    <li class="breadcrumb-item active">{{ $moduleName }}</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">{{ $moduleName }}</h1>
            <p class="text-muted mb-0">Kelola assignment dokumen ke divisi</p>
        </div>
        <a href="{{ route('admin.document-assignment.index') }}" class="btn btn-outline-secondary"><i
                class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul/nomor..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="id_divisi" class="form-select">
                        <option value="">Semua Divisi</option>
                        <option value="-1" {{ $selectedDivision == -1 ? 'selected' : '' }}>⚠️ Belum Assign</option>
                        @foreach ($divisions as $d)
                            <option value="{{ $d->id }}" {{ $selectedDivision == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.document-assignment.module', $module) }}"
                        class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <form action="{{ route('admin.document-assignment.reassign') }}" method="POST" id="reassignForm">
        @csrf
        <input type="hidden" name="module" value="{{ $module }}">

        <div class="card mb-3">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label" for="selectAll">Pilih Semua</label>
                </div>
                <div class="flex-grow-1">
                    <select name="new_divisi_id" class="form-select form-select-sm" style="width: 250px;" required>
                        <option value="">Pindahkan ke divisi...</option>
                        @foreach ($divisions as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-warning"
                    onclick="return confirm('Yakin pindahkan dokumen terpilih?')">
                    <i class="bi bi-arrow-right-circle"></i> Pindahkan
                </button>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="40"></th>
                            <th>Judul/Perihal</th>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Divisi Saat Ini</th>
                            <th>Klasifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            <tr>
                                <td>
                                    <input type="checkbox" name="document_ids[]" value="{{ $doc->id }}"
                                        class="form-check-input doc-checkbox">
                                </td>
                                <td>
                                    <strong>{{ $doc->judul ?? ($doc->perihal ?? ($doc->nama ?? ($doc->nomor ?? 'Dokumen Tanpa Judul'))) }}</strong>
                                </td>
                                <td><code>{{ $doc->nomor ?? '-' }}</code></td>
                                <td>{{ isset($doc->tanggal) && $doc->tanggal ? \Carbon\Carbon::parse($doc->tanggal)->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    @if ($doc->nama_divisi)
                                        <span class="badge bg-info">{{ $doc->nama_divisi }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Assign</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($doc->sifat_dokumen == 'Rahasia')
                                        <span class="badge bg-danger"><i class="bi bi-lock me-1"></i>Rahasia</span>
                                    @elseif($doc->sifat_dokumen == 'Internal')
                                        <span class="badge bg-warning text-dark"><i
                                                class="bi bi-shield-lock me-1"></i>Internal</span>
                                    @else
                                        <span class="badge bg-success"><i class="bi bi-unlock me-1"></i>Umum</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada dokumen</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($documents->hasPages())
                <div class="card-footer">{{ $documents->withQueryString()->links() }}</div>
            @endif
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('.doc-checkbox').forEach(cb => cb.checked = this.checked);
        });
    </script>
@endpush
