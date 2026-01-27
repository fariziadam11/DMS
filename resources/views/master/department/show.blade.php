@extends('layouts.app')
@section('title', 'Detail Department')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Master</a></li>
<li class="breadcrumb-item"><a href="{{ route('master.department.index') }}">Department</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="page-header mb-4"><h1 class="page-title">Detail Department</h1></div>
<div class="card"><div class="card-body">
    <table class="table table-bordered">
        <tr><th width="200">Kode Department</th><td><span class="badge bg-info">{{ $record->kode_department }}</span></td></tr>
        <tr><th>Nama Department</th><td><strong>{{ $record->nama_department }}</strong></td></tr>
        <tr><th>Divisi</th><td>{{ $record->divisi->nama_divisi ?? '-' }}</td></tr>
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
                    <a href="{{ route('master.department.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                @endif
        <div>
            @if ($permissions['edit'])
                        <a href="{{ route('master.department.edit', $record->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                    @endif
            @if ($permissions['delete'])
                        <form action="{{ route('master.department.destroy', $record->id) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button></form>
                    @endif
        </div>
    </div>
</div></div>
@endsection