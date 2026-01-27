@extends('layouts.app')
@section('title', 'Detail Divisi')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Master</a></li>
<li class="breadcrumb-item"><a href="{{ route('master.divisi.index') }}">Divisi</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="page-header mb-4"><h1 class="page-title">Detail Divisi</h1></div>
<div class="card"><div class="card-body">
    <table class="table table-bordered">
        <tr><th width="200">Kode Divisi</th><td><span class="badge bg-primary">{{ $record->kode_divisi }}</span></td></tr>
        <tr><th>Nama Divisi</th><td><strong>{{ $record->nama_divisi }}</strong></td></tr>
        <tr><th>Module</th><td>{{ $record->module->module_name ?? '-' }}</td></tr>
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
                    <a href="{{ route('master.divisi.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                @endif
        <div>
            @if ($permissions['edit'])
                        <a href="{{ route('master.divisi.edit', $record->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                    @endif
            @if ($permissions['delete'])
                        <form action="{{ route('master.divisi.destroy', $record->id) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button></form>
                    @endif
        </div>
    </div>
</div></div>
@endsection