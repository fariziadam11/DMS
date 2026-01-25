@extends('layouts.app')
@section('title', 'Detail Pajak')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Keuangan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('keuangan.pajak.index') }}">Pajak</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Detail Pajak</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">Masa</th>
                    <td>{{ $record->masa ?? '-' }}</td>
                </tr>
                <tr>
                    <th width="200">Tahun</th>
                    <td>{{ $record->tahun ?? '-' }}</td>
                </tr>
                <tr>
                    <th width="200">Uraian</th>
                    <td>{{ $record->uraian ?? '-' }}</td>
                </tr>
                <tr>
                    <th width="200">Divisi</th>
                    <td>{{ $record->divisi->nama_divisi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Sifat Dokumen</th>
                    <td><span
                            class="badge bg-{{ $record->sifat_dokumen == 'Rahasia' ? 'danger' : 'success' }}">{{ $record->sifat_dokumen ?? 'Umum' }}</span>
                    </td>
                </tr>
                @if ($permissions['download'])
                    <tr>
                        <th>File</th>
                        <td>
                            @if ($record->file_name)
                                <div class="btn-group btn-group-sm">
                                    <button
                                        onclick="previewFile('{{ route('keuangan.pajak.preview', $record->id) }}', '{{ $record->file_name }}')"
                                        class="btn btn-primary" title="Preview"><i class="bi bi-eye"></i> Preview</button>
                                    <a href="{{ route('keuangan.pajak.download', $record->id) }}" class="btn btn-success"
                                        title="Download"><i class="bi bi-download"></i> Download</a>
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
                @else
                    <a href="{{ route('keuangan.pajak.index') }}" class="btn btn-outline-secondary"><i
                        class="bi bi-arrow-left"></i> Kembali</a>
                @endif
                <div>
                    @if ($permissions['edit'])
                        <a href="{{ route('keuangan.pajak.edit', $record->id) }}" class="btn btn-warning"><i
                            class="bi bi-pencil"></i> Edit</a>
                    @endif
                    <form action="{{ route('keuangan.pajak.destroy', $record->id) }}" method="POST" class="d-inline">@csrf
                        @method('DELETE')<button class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button></form>
                </div>
            </div>
        </div>
    </div>
@endsection
