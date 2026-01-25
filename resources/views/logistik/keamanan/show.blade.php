@extends('layouts.app')
@section('title', 'Detail Keamanan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('logistik.keamanan.index') }}">Keamanan</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Keamanan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <th width="200">Divisi</th>
                            <td>{{ $item->divisi->nama_divisi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Sifat Dokumen</th>
                            <td>
                                @if (($item->sifat_dokumen ?? '') == 'Rahasia')
                                    <span class="badge bg-danger">Rahasia</span>
                                @else
                                    <span class="badge bg-success">Umum</span>
                                @endif
                            </td>
                        </tr>
                        @if ($permissions['download'])
                            <tr>
                                <th>File Dokumen</th>
                                <td>
                                    @if ($item->file_name)
                                        <div class="btn-group btn-group-sm">
                                            <button
                                                onclick="previewFile('{{ route('logistik.keamanan.preview', $item->id) }}', '{{ $item->file_name }}')"
                                                class="btn btn-primary" title="Preview"><i class="bi bi-eye"></i>
                                                Preview</button>
                                            <a href="{{ route('logistik.keamanan.download', $item->id) }}"
                                                class="btn btn-success" title="Download"><i class="bi bi-download"></i>
                                                Download</a>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Lokasi Fisik</th>
                            <td>{{ $item->lokasi ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer text-end">
                    @if ($permissions['edit'])
                        <a href="{{ route('logistik.keamanan.edit', $item->id) }}" class="btn btn-warning"><i
                            class="bi bi-pencil"></i> Edit</a>
                    @endif
                    @if (request('source') == 'my-documents')
                    <a href="{{ route('my-documents.index') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali ke Dokumen Saya</a>
                @else
                    <a href="{{ route('logistik.keamanan.index') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali</a>
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection
