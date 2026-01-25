@extends('layouts.app')
@section('title', 'Detail Rekon')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sdm.rekon.index') }}">Rekon</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Rekon</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <th width="200">Divisi</th>
                            <td>{{ $item->divisi->nama_divisi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ $item->tanggal ? date('d/m/Y', strtotime($item->tanggal)) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Ba</th>
                            <td>{{ $item->ba ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Hasil</th>
                            <td>{{ $item->hasil ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $item->kategori ?? '-' }}</td>
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
                                                onclick="previewFile('{{ route('sdm.rekon.preview', $item->id) }}', '{{ $item->file_name }}')"
                                                class="btn btn-primary" title="Preview"><i class="bi bi-eye"></i>
                                                Preview</button>
                                            <a href="{{ route('sdm.rekon.download', $item->id) }}" class="btn btn-success"
                                                title="Download"><i class="bi bi-download"></i> Download</a>
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
                    <a href="{{ route('sdm.rekon.edit', $item->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i>
                        Edit</a>
                    <a href="{{ route('sdm.rekon.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
