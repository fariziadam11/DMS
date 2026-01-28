@extends('layouts.app')
@section('title', 'Manajemen User')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">Admin</a>
    </li>
    <li class="breadcrumb-item active">User</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Manajemen User</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah User</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-5"><input type="text" name="search" class="form-control"
                        placeholder="Cari nama atau email..." value="{{ request('search') }}"></div>
                <div class="col-md-4"><select name="id_divisi" class="form-select">
                        <option value="">Semua Divisi</option>
                        @foreach ($divisions as $d)
                            <option value="{{ $d->id }}" {{ request('id_divisi') == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i>
                        Cari</button></div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Display Name</th>
                        <th>Login Username</th>

                        <th>Divisi</th>
                        <th>Department</th>
                        <th>Jabatan</th>
                        <th>Role</th>
                        <th>Valid Till</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $i }}</td>
                            <td><span class="badge bg-secondary">{{ $user->nip ?? '-' }}</span></td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->username ?? '-' }}</td>

                            <td>
                                @if ($user->divisi)
                                    <span class="badge bg-info">{{ $user->divisi->nama_divisi }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($user->department)
                                    <span class="badge bg-secondary">{{ $user->department->nama_department }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($user->jabatan)
                                    <span class="badge bg-success">{{ $user->jabatan->nama_jabatan }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->roles_name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($user->valid_till)
                                    <span class="badge bg-warning">{{ $user->valid_till->format('d M Y') }}</span>
                                @else
                                    <span class="badge bg-success">Permanent</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-primary"><i
                                            class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-warning"><i
                                            class="bi bi-pencil"></i></a>
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            class="d-inline">@csrf
                                            @method('DELETE')<button class="btn btn-outline-danger"><i
                                                    class="bi bi-trash"></i></button></form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="card-footer">{{ $users->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection
