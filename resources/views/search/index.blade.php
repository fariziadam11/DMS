@extends('layouts.app')

@section('title', 'Pencarian Dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pencarian</li>
@endsection

@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Pencarian Global</h1>
    </div>

    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('search') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Kata Kunci</label>
                    <input type="text" name="q" class="form-control form-control-lg" placeholder="Cari dokumen..."
                        value="{{ $query }}" autofocus>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Divisi</label>
                    <select name="divisi" class="form-select form-select-lg">
                        <option value="">Semua Divisi</option>
                        @foreach ($divisions as $div)
                            <option value="{{ $div->id }}" {{ $selectedDivisi == $div->id ? 'selected' : '' }}>
                                {{ $div->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Klasifikasi</label>
                    <select name="classification" class="form-select form-select-lg">
                        <option value="">Semua</option>
                        <option value="Umum" {{ $selectedClassification == 'Umum' ? 'selected' : '' }}>Umum</option>
                        <option value="Internal" {{ $selectedClassification == 'Internal' ? 'selected' : '' }}>Internal
                        </option>
                        <option value="Rahasia" {{ $selectedClassification == 'Rahasia' ? 'selected' : '' }}>Rahasia
                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results -->
    @if ($query)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Hasil Pencarian: <strong>"{{ $query }}"</strong></span>
                <span class="badge bg-primary">{{ count($results) }} dokumen ditemukan</span>
            </div>
            <div class="card-body p-0">
                @if (count($results) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Modul</th>
                                    <th>Divisi</th>
                                    <th>Tanggal</th>
                                    <th>Klasifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr class="{{ !$result['has_access'] ? 'table-secondary' : '' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($result['is_secret'])
                                                    <i class="bi bi-lock text-danger me-2"></i>
                                                @elseif ($result['classification'] == 'Internal')
                                                    <i class="bi bi-building-lock text-warning me-2"></i>
                                                @else
                                                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                                @endif
                                                <div>
                                                    <strong>{{ $result['title'] }}</strong>
                                                    <br><small class="text-muted">v{{ $result['version'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $result['type'] }}</span></td>
                                        <td>{{ $result['division'] ?? '-' }}</td>
                                        <td>{{ $result['created_at'] }}</td>
                                        <td>
                                            @if ($result['classification'] == 'Rahasia')
                                                <span class="badge badge-secret"><i
                                                        class="bi bi-lock me-1"></i>Rahasia</span>
                                            @elseif ($result['classification'] == 'Internal')
                                                <span class="badge bg-warning text-dark"><i
                                                        class="bi bi-building-lock me-1"></i>Internal</span>
                                            @else
                                                <span class="badge badge-public"><i
                                                        class="bi bi-unlock me-1"></i>Umum</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result['has_access'] && $result['url'])
                                                <a href="{{ $result['url'] }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i> Lihat
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal" data-bs-target="#requestModal"
                                                    data-type="{{ $result['table'] }}" data-id="{{ $result['id'] }}"
                                                    data-title="{{ $result['title'] }}">
                                                    <i class="bi bi-key me-1"></i> Minta Akses
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-search fs-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">Tidak ada hasil ditemukan</h5>
                        <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">Mulai Pencarian</h5>
                <p class="text-muted">Masukkan kata kunci untuk mencari dokumen di seluruh sistem</p>
            </div>
        </div>
    @endif

    <!-- Request Access Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('search.request-access') }}" method="POST">
                    @csrf
                    <input type="hidden" name="document_type" id="modalDocType">
                    <input type="hidden" name="document_id" id="modalDocId">
                    <div class="modal-header">
                        <h5 class="modal-title">Minta Akses Dokumen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda meminta akses untuk dokumen: <strong id="modalDocTitle"></strong></p>
                        <div class="mb-3">
                            <label class="form-label">Alasan Permintaan <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" required
                                placeholder="Jelaskan mengapa Anda membutuhkan akses..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Permintaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('requestModal').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('modalDocType').value = btn.dataset.type;
            document.getElementById('modalDocId').value = btn.dataset.id;
            document.getElementById('modalDocTitle').textContent = btn.dataset.title;
        });
    </script>
@endpush
