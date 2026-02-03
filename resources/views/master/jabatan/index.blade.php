@extends('layouts.app')
@section('title', 'Master Jabatan')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">Master</a>
    </li>
    <li class="breadcrumb-item active">Jabatan</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Master Jabatan</h1>
        <a href="{{ route('master.jabatan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-10"><input type="text" name="search" class="form-control"
                        placeholder="Cari kode atau nama jabatan..." value="{{ request('search') }}"></div>
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
                            <th>Nama Jabatan</th>
                            <th>Divisi</th>
                            <th>Department</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $i => $item)
                            <tr>
                                <td>{{ $data->firstItem() + $i }}</td>
                                <td><span class="badge bg-success">{{ $item->kode_jabatan }}</span></td>
                                <td><strong>{{ $item->nama_jabatan }}</strong></td>
                                <td>{{ $item->divisi->nama_divisi ?? '-' }}</td>
                                <td>{{ $item->department->nama_department ?? '-' }}</td>
                                <td>
                                    @if ($item->level_jabatan == 1)
                                        <span class="badge bg-secondary">Staff</span>
                                    @elseif($item->level_jabatan == 2)
                                        <span class="badge bg-info">Supervisor</span>
                                    @elseif($item->level_jabatan == 3)
                                        <span class="badge bg-warning">Manager</span>
                                    @elseif($item->level_jabatan == 4)
                                        <span class="badge bg-danger">Director</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('master.jabatan.show', $item->id) }}"
                                            class="btn btn-outline-primary"><i class="bi bi-eye"></i> Detail</a>
                                        @if ($permissions['edit'])
                                            <a href="{{ route('master.jabatan.edit', $item->id) }}"
                                                class="btn btn-outline-warning"><i class="bi bi-pencil"></i> Edit</a>
                                        @endif
                                        @if ($permissions['delete'])
                                            <form action="{{ route('master.jabatan.destroy', $item->id) }}" method="POST"
                                                class="d-inline">@csrf @method('DELETE')<button
                                                    class="btn btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data</td>
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
