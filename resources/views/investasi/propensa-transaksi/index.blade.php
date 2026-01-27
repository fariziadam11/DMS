@extends('layouts.app')
@section('title', 'Propensa Transaksi')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">Investasi</a>
    </li>
    <li class="breadcrumb-item active">Propensa Transaksi</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Propensa Transaksi</h1>
        <a href="{{ route('investasi.propensa-transaksi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>
            Tambah</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-8"><input type="text" name="search" class="form-control" placeholder="Cari..."
                        value="{{ request('search') }}"></div>
                <div class="col-md-4"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i>
                        Cari</button></div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipe</th>
                            <th>Tanggal</th>
                            <th>Perihal</th>
                            <th>Sifat Dokumen</th>
                            <th>Versi</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse($items as $i => $item)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}</td>
                            <td>{{ $item->type ?? '-' }}</td>
                            <td>{{ $item->tanggal ? date('d/m/Y', strtotime($item->tanggal)) : '-' }}</td>
                            <td>{{ $item->perihal ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ ($item->sifat_dokumen ?? 'Umum') == 'Rahasia' ? 'danger' : (($item->sifat_dokumen ?? 'Umum') == 'Internal' ? 'warning' : 'success') }}">
                                    {{ $item->sifat_dokumen ?? 'Umum' }}
                                </span>
                            </td>
                            <td><span
                                    class="badge bg-light text-dark border border-secondary">V{{ $item->version ?? '1' }}</span>
                            </td>
                            <td>
                                @if ($item->file_name)
                                    @if ($item->userHasFileAccess(auth()->id()))
                                        <div class="btn-group btn-group-sm">
                                            <button
                                                onclick="previewFile('{{ route('investasi.propensa-transaksi.preview', $item->id) }}', '{{ $item->file_name }}')"
                                                class="btn btn-outline-primary" title="Preview"><i
                                                    class="bi bi-eye"></i></button>
                                            <a href="{{ route('investasi.propensa-transaksi.download', $item->id) }}"
                                                class="btn btn-outline-success" title="Download"><i
                                                    class="bi bi-download"></i></a>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#requestModal" data-type="{{ $item->getTable() }}"
                                            data-id="{{ $item->id }}"
                                            data-title="{{ $item->perihal ?? $item->file_name }}">
                                            <i class="bi bi-key me-1"></i> Minta Akses
                                        </button>
                                    @endif
                                @else<span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if ($item->canPerformAction('read', auth()->id()))
                                        @if ($item->canPerformAction('read', auth()->id()))
                                            <a href="{{ route('investasi.propensa-transaksi.show', $item->id) }}"
                                                class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                        @endif
                                    @endif
                                    @if ($permissions['edit'] && $item->canPerformAction('edit', auth()->id()))
                                        <a href="{{ route('investasi.propensa-transaksi.edit', $item->id) }}"
                                            class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                    @endif
                                    @if ($permissions['delete'] && $item->canPerformAction('delete', auth()->id()))
                                        <form action="{{ route('investasi.propensa-transaksi.destroy', $item->id) }}"
                                            method="POST" class="d-inline">@csrf @method('DELETE')<button
                                                class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $permissions['download'] ? 6 : 5 }}" class="text-center py-4 text-muted">Belum
                                ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($items->hasPages())
            <div class="card-footer">{{ $items->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection
