@extends('layouts.app')
@section('title', $moduleName)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Investasi</a></li>
<li class="breadcrumb-item active">{{ $subModule }}</li>
@endsection
@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">{{ $moduleName }}</h1>
    <a href="{{ route($routePrefix . '.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah</a>
</div>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3">
        <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}"></div>
        <div class="col-md-3"><select name="id_divisi" class="form-select"><option value="">Semua Divisi</option>@foreach(\App\Models\MasterDivisi::all() as $d)<option value="{{ $d->id }}" {{ request('id_divisi') == $d->id ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>@endforeach</select></div>
        <div class="col-md-3"><select name="sifat_dokumen" class="form-select"><option value="">Semua</option><option value="Umum" {{ request('sifat_dokumen') == 'Umum' ? 'selected' : '' }}>Umum</option><option value="Internal" {{ request('sifat_dokumen') == 'Internal' ? 'selected' : '' }}>Internal</option><option value="Rahasia" {{ request('sifat_dokumen') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option></select></div>
        <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button></div>
    </form>
</div></div>
<div class="card"><div class="card-body p-0">
    <table class="table table-hover mb-0">
        <thead><tr><th>#</th><th>Judul</th><th>Divisi</th><th>Tanggal</th><th>Klasifikasi</th><th>Versi</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($data as $i => $item)
            <tr>
                <td>{{ $data->firstItem() + $i }}</td>
                <td><i class="bi bi-file-earmark-text text-primary me-2"></i><strong>{{ $item->judul ?? $item->perihal ?? $item->nama ?? '-' }}</strong></td>
                <td>{{ $item->divisi?->nama_divisi ?? '-' }}</td>
                <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}</td>
                <td>@if($item->sifat_dokumen == 'Rahasia')<span class="badge badge-secret"><i class="bi bi-lock"></i></span>@else<span class="badge badge-public"><i class="bi bi-unlock"></i></span>@endif</td>
                <td><span class="badge bg-secondary">v{{ $item->version ?? 1 }}</span></td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route($routePrefix . '.show', $item->id) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="{{ route($routePrefix . '.edit', $item->id) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @if($item->file)<a href="{{ route($routePrefix . '.download', $item->id) }}" class="btn btn-outline-success"><i class="bi bi-download"></i></a>@endif
                        <form action="{{ route($routePrefix . '.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin?')">@csrf @method('DELETE')<button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>@if($data->hasPages())<div class="card-footer">{{ $data->withQueryString()->links() }}</div>@endif</div>
@endsection