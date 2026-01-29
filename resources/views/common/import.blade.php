@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                    <h5 class="mb-0 text-primary fw-bold">Import Data Excel</h5>
                    <div>
                        <a href="{{ route($routePrefix . '.template') }}" class="btn btn-sm btn-outline-success me-2">
                            <i class="bi bi-download me-1"></i> Download Template
                        </a>
                        <a href="{{ route($routePrefix . '.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">

                    @if (session('import_summary'))
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <h6 class="alert-heading fw-bold"><i class="bi bi-info-circle me-2"></i>Hasil Import</h6>
                            <hr>
                            <ul class="mb-0">
                                <li>Total Baris: <strong>{{ session('import_summary')['total'] }}</strong></li>
                                <li>Berhasil: <strong
                                        class="text-success">{{ session('import_summary')['success'] }}</strong></li>
                                <li>Gagal: <strong class="text-danger">{{ session('import_summary')['failed'] }}</strong>
                                </li>
                            </ul>
                            @if (!empty(session('import_summary')['errors']))
                                <div class="mt-3">
                                    <strong>Detail Error:</strong>
                                    <ul class="mt-1 small text-danger max-h-40 overflow-auto">
                                        @foreach (session('import_summary')['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route($routePrefix . '.store-import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-4">
                                    <label for="file" class="form-label fw-bold">Pilih File Excel (.xlsx, .xls)</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror"
                                        id="file" name="file" accept=".xlsx, .xls" required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted mt-2">
                                        Pastikan format kolom sesuai dengan template yang ditentukan.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-cloud-upload me-2"></i> Upload & Import
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6 border-start ps-4">
                            <h6 class="fw-bold mb-3">Panduan Kolom</h6>
                            <p class="text-muted small">File Excel harus memiliki urutan kolom berikut (mulai dari kolom A):
                            </p>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered bg-light text-center small">
                                    <thead>
                                        <tr>
                                            <th>Kolom Excel</th>
                                            <th>Nama Field</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($importColumns as $field => $index)
                                            <tr>
                                                <td>{{ \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index) }}
                                                </td>
                                                <td>{{ ucwords(str_replace('_', ' ', $field)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
