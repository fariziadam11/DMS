@extends('layouts.app')
@section('title', 'Detail Smk3')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('logistik.smk3.index') }}">Smk3</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Smk3</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <th width="200">Divisi</th>
                            <td>{{ $item->divisi->nama_divisi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tahun</th>
                            <td>{{ $item->tahun ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bulan</th>
                            <td>{{ $item->bulan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Kegiatan</th>
                            <td>{{ $item->nama_kegiatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Sifat Dokumen</th>
                            <td>
                                @if (($item->sifat_dokumen ?? '') == 'Rahasia')
                                    <span class="badge bg-danger">Rahasia</span>
                                @elseif (($item->sifat_dokumen ?? '') == 'Internal')
                                    <span class="badge bg-warning text-dark">Internal</span>
                                @else
                                    <span class="badge bg-success">Umum</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Versi</th>
                            <td><span class="badge bg-light text-dark border border-secondary">V{{ $item->version ?? '1' }}</span></td>
                        </tr>
                        @if ($permissions['download'])
                            <tr>
                                <th>File Dokumen</th>
                                <td>
                                    @if ($item->file_name)
                                        <div class="btn-group btn-group-sm">
                                            @if($permissions['preview'] ?? false)
                                            <button
                                                onclick="previewFile('{{ route('logistik.smk3.preview', $item->id) }}', '{{ $item->file_name }}')"
                                                class="btn btn-primary" title="Preview"><i class="bi bi-eye"></i>
                                                Preview</button>
                                            @endif
                                            <a href="{{ route('logistik.smk3.download', $item->id) }}"
                                                class="btn btn-success" title="Download"><i class="bi bi-download"></i>
                                                Download</a>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endif

                    </table>
                </div>
                <div class="card-footer text-end">
                    @if ($permissions['edit'])
                        <a href="{{ route('logistik.smk3.edit', $item->id) }}" class="btn btn-warning"><i
                                class="bi bi-pencil"></i> Edit</a>
                    @endif
                    @if (request('source') == 'my-documents')
                        <a href="{{ route('my-documents.index') }}" class="btn btn-outline-secondary"><i
                                class="bi bi-arrow-left"></i> Kembali ke Dokumen Saya</a>
                    @elseif (request('source') == 'search')
                        <a href="{{ route('search') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
                            Kembali ke Pencarian</a>
                    @else
                        <a href="{{ route('logistik.smk3.index') }}" class="btn btn-outline-secondary"><i
                                class="bi bi-arrow-left"></i> Kembali</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
