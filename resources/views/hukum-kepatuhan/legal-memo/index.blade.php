@extends('layouts.app')
@section('title', 'Legal Memo')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">Hukum kepatuhan</a>
    </li>
    <li class="breadcrumb-item active">Legal Memo</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Legal Memo</h1>
        <a href="{{ route('hukum-kepatuhan.legal-memo.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>
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
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Keterangan</th>
                        @if ($permissions['download'])
                            <th>File</th>
                        @endif
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $item)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}</td>
                            <td>{{ $item->tanggal ? date('d/m/Y', strtotime($item->tanggal)) : '-' }}</td>
                            <td>{{ Str::limit($item->judul, 50) ?? '-' }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            @if ($permissions['download'])
                                <td>
                                    @if ($item->file_name)
                                        @if ($item->userHasFileAccess(auth()->id()))
                                            <div class="btn-group btn-group-sm">
                                                <button
                                                    onclick="previewFile('{{ route('hukum-kepatuhan.legal-memo.preview', $item->id) }}', '{{ $item->file_name }}')"
                                                    class="btn btn-outline-primary" title="Preview"><i
                                                        class="bi bi-eye"></i></button>
                                                <a href="{{ route('hukum-kepatuhan.legal-memo.download', $item->id) }}"
                                                    class="btn btn-outline-success" title="Download"><i
                                                        class="bi bi-download"></i></a>
                                            </div>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                data-bs-toggle="modal" data-bs-target="#requestModal"
                                                data-type="{{ $item->getTable() }}" data-id="{{ $item->id }}"
                                                data-title="{{ $item->judul ?? $item->file_name }}">
                                                <i class="bi bi-key me-1"></i> Minta Akses
                                            </button>
                                        @endif
                                    @else<span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endif
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('hukum-kepatuhan.legal-memo.show', $item->id) }}"
                                        class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('hukum-kepatuhan.legal-memo.edit', $item->id) }}"
                                        class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('hukum-kepatuhan.legal-memo.destroy', $item->id) }}"
                                        method="POST" class="d-inline">@csrf @method('DELETE')<button
                                            class="btn btn-outline-danger"><i class="bi bi-trash"></i></button></form>
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
