@extends('layouts.app')
@section('title', 'Master Divisi')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">Master</a>
    </li>
    <li class="breadcrumb-item active">Divisi</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Master Divisi</h1>
        <a href="{{ route('master.divisi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-10"><input type="text" name="search" class="form-control"
                        placeholder="Cari kode atau nama divisi..." value="{{ request('search') }}"></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i>
                        Cari</button></div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama Divisi</th>
                            <th>Department</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $i => $item)
                            <tr>
                                <td>{{ $data->firstItem() + $i }}</td>
                                <td><span class="badge bg-primary">{{ $item->kode_divisi }}</span></td>
                                <td><strong>{{ $item->nama_divisi }}</strong></td>
                                <td>{{ $item->department->nama_department ?? '-' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('master.divisi.show', $item->id) }}"
                                            class="btn btn-outline-primary"><i class="bi bi-eye"></i> Detail</a>
                                        <a href="{{ route('master.divisi.edit', $item->id) }}"
                                            class="btn btn-outline-warning"><i class="bi bi-pencil"></i> Edit</a>
                                        <form action="{{ route('master.divisi.destroy', $item->id) }}" method="POST"
                                            class="d-inline">@csrf @method('DELETE')<button
                                                class="btn btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button></form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($data->hasPages())
            <div class="card-footer">{{ $data->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection
