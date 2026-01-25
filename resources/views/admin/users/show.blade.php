@extends('layouts.app')
@section('title', 'Detail User')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Detail User</h1>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th width="200">Nama</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Divisi</th>
                    <td>{{ $user->divisi->nama_divisi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $user->department->nama_department ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $user->jabatan->nama_jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>
                        @foreach ($user->roles as $role)
                            <span class="badge bg-primary">{{ $role->roles_name }}</span>
                        @endforeach
                        @if ($user->roles->isEmpty())
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Bergabung</th>
                    <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="mt-4">@if (request('source') == 'my-documents')
                    <a href="{{ route('my-documents.index') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali ke Dokumen Saya</a>
                @else
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i
                class="bi bi-arrow-left"></i> Kembali</a>
                @endif</div>
@endsection
