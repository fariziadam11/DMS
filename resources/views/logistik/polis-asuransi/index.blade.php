@extends('layouts.app')
@section('title', 'Polis Asuransi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Logistik</a></li>
    <li class="breadcrumb-item active">Polis Asuransi</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Polis Asuransi</h1>
        <a href="{{ route('logistik.polis-asuransi.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>
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
                        <th>Tahun</th>
                        <th>Berlaku Mulai</th>
                        <th>Berlaku Akhir</th>
                        <th>Nilai</th>
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
                            <td>{{ $item->tahun ?? '-' }}</td>
                            <td>{{ $item->berlaku_mulai ? date('d/m/Y', strtotime($item->berlaku_mulai)) : '-' }}</td>
                            <td>{{ $item->berlaku_akhir ? date('d/m/Y', strtotime($item->berlaku_akhir)) : '-' }}</td>
                            <td>{{ $item->nilai ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ ($item->sifat_dokumen ?? 'Umum') == 'Rahasia' ? 'danger' : 'success' }}">
                                    {{ $item->sifat_dokumen ?? 'Umum' }}
                                </span>
                            </td>
                            <td><span class="badge bg-light text-dark border border-secondary">V{{ $item->version ?? '1' }}</span></td>
                            <td>
                                @if ($item->file_name)
                                    @if ($item->userHasFileAccess(auth()->id()))
                                        <div class="btn-group btn-group-sm">
                                            <button
                                                onclick="previewFile('{{ route('logistik.polis-asuransi.preview', $item->id) }}', '{{ $item->file_name }}')"
                                                class="btn btn-outline-primary" title="Preview"><i
                                                    class="bi bi-eye"></i></button>
                                            <a href="{{ route('logistik.polis-asuransi.download', $item->id) }}"
                                                class="btn btn-outline-success" title="Download"><i
                                                    class="bi bi-download"></i></a>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#requestModal" data-type="{{ $item->getTable() }}"
                                            data-id="{{ $item->id }}" data-title="{{ $item->file_name }}">
                                            <i class="bi bi-key me-1"></i> Minta Akses
                                        </button>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if ($item->canPerformAction('read', auth()->id()))
                                        @if ($item->canPerformAction('read', auth()->id()))
                                            <a href="{{ route('logistik.polis-asuransi.show', $item->id) }}"
                                                class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                        @endif
                                    @endif
                                    @if ($permissions['edit'] && $item->canPerformAction('edit', auth()->id()))
                                        <a href="{{ route('logistik.polis-asuransi.edit', $item->id) }}"
                                            class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                    @endif
                                    @if ($permissions['delete'] && $item->canPerformAction('delete', auth()->id()))
                                        <form action="{{ route('logistik.polis-asuransi.destroy', $item->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger"
                                                onclick="return confirm('Apakah Anda yakin?')"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $permissions['download'] ? 7 : 6 }}" class="text-center py-4 text-muted">Belum
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
