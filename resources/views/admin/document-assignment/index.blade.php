@extends('layouts.app')
@section('title', 'Assignment Dokumen ke Divisi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active">Document Assignment</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Assignment Dokumen ke Divisi</h1>
            <p class="text-muted mb-0">Kelola kepemilikan dokumen per divisi</p>
        </div>
    </div>

    <!-- Filter by Division -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-10">
                    <select name="id_divisi" class="form-select">
                        <option value="">Semua Divisi</option>
                        @foreach ($divisions as $d)
                            <option value="{{ $d->id }}" {{ $selectedDivision == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Document Statistics by Table -->
    <div class="card">
        <div class="card-header">
            <strong>Statistik Dokumen per Modul</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Modul</th>
                        <th class="text-center">Total Dokumen</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentStats as $stat)
                        <tr>
                            <td><strong>{{ $stat['name'] }}</strong></td>
                            <td class="text-center"><span class="badge bg-primary">{{ $stat['total'] }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('admin.document-assignment.module', $stat['module_slug']) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
