@extends('layouts.app')
@section('title', 'Detail Regulasi Internal')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Hukum kepatuhan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hukum-kepatuhan.regulasi-internal.index') }}">Regulasi Internal</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Detail Regulasi Internal</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">Tanggal</th>
                    <td>{{ $record->tanggal ? date('d F Y', strtotime($record->tanggal)) : '-' }}</td>
                </tr>
                <tr>
                    <th width="200">Nomor</th>
                    <td>{{ $record->nomor ?? '-' }}</td>
                </tr>
                <tr>
                    <th width="200">Judul</th>
                    <td>{{ $record->judul ?? '-' }}</td>
                </tr>
                <tr>
                    <th width="200">Divisi</th>
                    <td>{{ $record->divisi->nama_divisi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Sifat Dokumen</th>
                    <td><span
                            class="badge bg-{{ ($record->sifat_dokumen ) == 'Rahasia' ? 'danger' : (($record->sifat_dokumen ) == 'Internal' ? 'warning' : 'success') }}">{{ $record->sifat_dokumen ?? 'Umum' }}</span>
                    </td>
                </tr>
                        <tr>
                            <th>Versi</th>
                            <td><span class="badge bg-light text-dark border border-secondary">V{{ $item->version ?? '1' }}</span></td>
                        </tr>
                @if ($permissions['download'])
                    <tr>
                        <th>File</th>
                        <td>
                            @if ($record->file_name)
                                <div class="btn-group btn-group-sm">
                                    @if($permissions['preview'] ?? false)
                                            <button
                                        onclick="previewFile('{{ route('hukum-kepatuhan.regulasi-internal.preview', $record->id) }}', '{{ $record->file_name }}')"
                                        class="btn btn-primary" title="Preview"><i class="bi bi-eye"></i> Preview</button>
                                            @endif
                                    <a href="{{ route('hukum-kepatuhan.regulasi-internal.download', $record->id) }}"
                                        class="btn btn-success" title="Download"><i class="bi bi-download"></i> Download</a>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endif
                <tr>
                    <th>Dibuat</th>
                    <td>{{ $record->created_at ? $record->created_at->format('d F Y H:i') : '-' }}</td>
                </tr>
                <tr>
                    <th>Diupdate</th>
                    <td>{{ $record->updated_at ? $record->updated_at->format('d F Y H:i') : '-' }}</td>
                </tr>
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
                    <a href="{{ route('hukum-kepatuhan.regulasi-internal.index') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali</a>
                @endif
                <div>
                    @if ($permissions['edit'])
                        <a href="{{ route('hukum-kepatuhan.regulasi-internal.edit', $record->id) }}"
                            class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                    @endif
                    @if ($permissions['delete'])
                        <form action="{{ route('hukum-kepatuhan.regulasi-internal.destroy', $record->id) }}" method="POST"
                            class="d-inline">@csrf @method('DELETE')<button class="btn btn-danger"><i
                                    class="bi bi-trash"></i>
                                Hapus</button></form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
