@extends('layouts.app')
@section('title', 'Detail Surat Investasi')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="#">Investasi</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ route('investasi.surat.index') }}">Surat Investasi</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Detail Surat Investasi</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="200">Tipe</th>
                    <td>{{ $record->type == 1 ? 'Masuk' : ($record->type == 2 ? 'Keluar' : '-') }}</td>
                </tr>
                <tr>
                    <th width="200">Tanggal</th>
                    <td>{{ $record->tgl_surat ? date('d F Y', strtotime($record->tgl_surat)) : '-' }}</td>
                </tr>
                <tr>
                    <th width="200">No. Surat</th>
                    <td>{{ $record->no_surat ?? '-' }}</td>
                </tr>
                <tr>
                    <th width="200">Perihal</th>
                    <td>{{ $record->perihal ?? '-' }}</td>
                </tr>
                <tr>
                    <th width="200">Perusahaan</th>
                    <td>{{ $record->nama_perusahaan ?? '-' }}</td>
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
                @if ($permissions['download'])
                    <tr>
                        <th>File</th>
                        <td>
                            @if ($record->file_name)
                                <div class="btn-group btn-group-sm">
                                    <button
                                        onclick="previewFile('{{ route('investasi.surat.preview', $record->id) }}', '{{ $record->file_name }}')"
                                        class="btn btn-primary" title="Preview"><i class="bi bi-eye"></i> Preview</button>
                                    <a href="{{ route('investasi.surat.download', $record->id) }}" class="btn btn-success"
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
                @elseif (request('source') == 'search')
                    <a href="{{ route('search') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali ke Pencarian</a>

                @else
                    <a href="{{ route('investasi.surat.index') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali</a>
                @endif
                <div>
                    @if ($permissions['edit'])
                        <a href="{{ route('investasi.surat.edit', $record->id) }}" class="btn btn-warning"><i
                                class="bi bi-pencil"></i> Edit</a>
                    @endif
                    <form action="{{ route('investasi.surat.destroy', $record->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')<button class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
