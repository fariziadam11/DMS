@extends('layouts.app')
@section('title', 'Detail Jabatan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Master</a></li>
<li class="breadcrumb-item"><a href="{{ route('master.jabatan.index') }}">Jabatan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="page-header mb-4"><h1 class="page-title">Detail Jabatan</h1></div>
<div class="card"><div class="card-body">
    <table class="table table-bordered">
        <tr><th width="200">Kode Jabatan</th><td><span class="badge bg-success">{{ $record->kode_jabatan }}</span></td></tr>
        <tr><th>Nama Jabatan</th><td><strong>{{ $record->nama_jabatan }}</strong></td></tr>
        <tr><th>Divisi</th><td>{{ $record->divisi->nama_divisi ?? '-' }}</td></tr>
        <tr><th>Department</th><td>{{ $record->department->nama_department ?? '-' }}</td></tr>
        <tr><th>Level Jabatan</th><td>
            @if($record->level_jabatan == 1)<span class="badge bg-secondary">Staff</span>
            @elseif($record->level_jabatan == 2)<span class="badge bg-info">Supervisor</span>
            @elseif($record->level_jabatan == 3)<span class="badge bg-warning">Manager</span>
            @elseif($record->level_jabatan == 4)<span class="badge bg-danger">Director</span>
            @else - @endif
        </td></tr>
        <tr><th>Default Role</th><td>{{ $record->defaultRole->roles_name ?? '-' }}</td></tr>
        <tr><th>Deskripsi</th><td>{{ $record->description ?? '-' }}</td></tr>
        <tr><th>Dibuat</th><td>{{ $record->created_at ? $record->created_at->format('d F Y H:i') : '-' }}</td></tr>
        <tr><th>Diupdate</th><td>{{ $record->updated_at ? $record->updated_at->format('d F Y H:i') : '-' }}</td></tr>
    </table>
    <hr class="my-4">
    <div class="d-flex justify-content-between">
        @if (request('source') == 'my-documents')
                    <a href="{{ route('my-documents.index') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali ke Dokumen Saya</a>
                @elseif (request('source') == 'search')
                    <a href="{{ route('search') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali ke Pencarian</a>

                @else
                    <a href="{{ route('master.jabatan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                @endif
        <div>
            @if ($permissions['edit'])
                        <a href="{{ route('master.jabatan.edit', $record->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                    @endif
            @if ($permissions['delete'])
                        <form action="{{ route('master.jabatan.destroy', $record->id) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button></form>
                    @endif
        </div>
    </div>
</div></div>
@endsection