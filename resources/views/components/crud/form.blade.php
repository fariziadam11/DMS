{{-- Create/Edit Form Template --}}
@extends('layouts.app')

@section('title', (isset($record) ? 'Edit' : 'Tambah') . ' - ' . $moduleName)

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route($routePrefix . '.index') }}">{{ $moduleName }}</a>
    </li>
    <li class="breadcrumb-item active">{{ isset($record) ? 'Edit' : 'Tambah' }}</li>
@endsection

@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">{{ isset($record) ? 'Edit' : 'Tambah' }} {{ $moduleName }}</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form
                action="{{ isset($record) ? route($routePrefix . '.update', $record->id) : route($routePrefix . '.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($record))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    <!-- Divisi -->
                    <div class="col-md-6">
                        <label class="form-label">Divisi <span class="text-danger">*</span></label>
                        <select name="id_divisi" class="form-select @error('id_divisi') is-invalid @enderror" required>
                            <option value="">Pilih Divisi</option>
                            @foreach ($divisions as $div)
                                <option value="{{ $div->id }}"
                                    {{ old('id_divisi', $record->id_divisi ?? '') == $div->id ? 'selected' : '' }}>
                                    {{ $div->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_divisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sifat Dokumen -->
                    <div class="col-md-6">
                        <label class="form-label">Klasifikasi Dokumen <span class="text-danger">*</span></label>
                        <select name="sifat_dokumen" class="form-select @error('sifat_dokumen') is-invalid @enderror"
                            required>
                            <option value="Umum"
                                {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Umum' ? 'selected' : '' }}>Umum
                            </option>
                            <option value="Rahasia"
                                {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Rahasia' ? 'selected' : '' }}>
                                Rahasia</option>
                        </select>
                        @error('sifat_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dynamic Fields - Override this in specific views -->
                    @yield('form_fields')

                    <!-- Judul (common field) -->
                    @if (!View::hasSection('form_fields'))
                        <div class="col-md-6">
                            <label class="form-label">Nomor</label>
                            <input type="text" name="nomor" class="form-control @error('nomor') is-invalid @enderror"
                                value="{{ old('nomor', $record->nomor ?? '') }}">
                            @error('nomor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal', isset($record->tanggal) ? \Carbon\Carbon::parse($record->tanggal)->format('Y-m-d') : '') }}">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Judul/Perihal <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul', $record->judul ?? ($record->perihal ?? '')) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <!-- File Upload -->
                    <div class="col-12">
                        <label class="form-label">File Dokumen @if (!isset($record))
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                            accept=".pdf,.doc,.docx,.xls,.xlsx" {{ !isset($record) ? 'required' : '' }}>
                        <div class="form-text">Format: PDF, DOC, DOCX, XLS, XLSX. Maksimal 10MB</div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if (isset($record) && $record->file)
                            <div class="mt-2">
                                <span class="badge bg-success"><i
                                        class="bi bi-file-earmark me-1"></i>{{ $record->file_name }}</span>
                                <small class="text-muted ms-2">File saat ini</small>
                            </div>
                        @endif
                    </div>

                    @if (isset($record))
                        <div class="col-12">
                            <label class="form-label">Catatan Perubahan</label>
                            <textarea name="change_notes" class="form-control" rows="2"
                                placeholder="Jelaskan perubahan yang dilakukan (opsional)"></textarea>
                        </div>
                    @endif
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="{{ route($routePrefix . '.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> {{ isset($record) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
