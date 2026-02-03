@extends('layouts.app')
@section('title', 'Manajemen Role')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active">Role</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Manajemen Role</h1>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Role</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari role..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Role</th>
                        <th>Divisi</th>
                        <th>Access Scope</th>
                        <th>Jumlah User</th>
                        <th>Privileges</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $i => $role)
                        <tr>
                            <td>{{ $roles->firstItem() + $i }}</td>
                            <td><strong>{{ $role->roles_name }}</strong></td>
                            <td>{{ $role->divisi?->nama_divisi ?? 'Semua Divisi' }}</td>
                            <td>
                                @if ($role->access_scope == 'global')
                                    <span class="badge bg-danger">Global</span>
                                @elseif($role->access_scope == 'division')
                                    <span class="badge bg-warning text-dark">Division</span>
                                @else
                                    <span class="badge bg-info">Department</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $role->users()->count() }} user</span></td>
                            <td><span class="badge bg-primary">{{ $role->privileges()->count() }} menu</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.roles.privileges', $role) }}" class="btn btn-outline-primary"
                                        title="Atur Privilege">
                                        <i class="bi bi-key"></i>
                                    </a>
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-warning"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin hapus role ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Hapus"><i
                                                class="bi bi-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada role</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($roles->hasPages())
            <div class="card-footer">{{ $roles->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection
