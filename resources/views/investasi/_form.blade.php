@extends('layouts.app')
@section('title', (isset($record) ? 'Edit' : 'Tambah') . ' - ' . $moduleName)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Investasi</a></li>
<li class="breadcrumb-item"><a href="{{ route($routePrefix . '.index') }}">{{ $subModule }}</a></li>
<li class="breadcrumb-item active">{{ isset($record) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="page-header mb-4"><h1 class="page-title">{{ isset($record) ? 'Edit' : 'Tambah' }} {{ $moduleName }}</h1></div>
<div class="card"><div class="card-body">
    <form action="{{ isset($record) ? route($routePrefix . '.update', $record->id) : route($routePrefix . '.store') }}" method="POST" enctype="multipart/form-data">
        @csrf @if(isset($record)) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-md-6"><label class="form-label">Divisi <span class="text-danger">*</span></label><select name="id_divisi" class="form-select" required><option value="">Pilih Divisi</option>@foreach($divisions as $d)<option value="{{ $d->id }}" {{ old('id_divisi', $record->id_divisi ?? '') == $d->id ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">Klasifikasi</label><select name="sifat_dokumen" class="form-select"><option value="Umum" {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Umum' ? 'selected' : '' }}>Umum</option>
            <option value="Internal" {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Internal' ? 'selected' : '' }}>Internal</option><option value="Rahasia" {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option></select></div>
            <div class="col-md-6"><label class="form-label">Nomor</label><input type="text" name="nomor" class="form-control" value="{{ old('nomor', $record->nomor ?? '') }}"></div>
            <div class="col-md-6"><label class="form-label">Tanggal</label><input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', isset($record->tanggal) ? \Carbon\Carbon::parse($record->tanggal)->format('Y-m-d') : '') }}"></div>
            <div class="col-12"><label class="form-label">Judul/Perihal <span class="text-danger">*</span></label><input type="text" name="judul" class="form-control" value="{{ old('judul', $record->judul ?? $record->perihal ?? '') }}" required></div>
            <div class="col-12"><label class="form-label">Lokasi Fisik</label><input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $record->lokasi ?? '') }}"></div>
            <div class="col-12"><label class="form-label">File @if(!isset($record))<span class="text-danger">*</span>@endif</label><input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx" {{ !isset($record) ? 'required' : '' }}>@if(isset($record) && $record->file)<div class="mt-2"><span class="badge bg-success">{{ $record->file_name }}</span></div>@endif</div>
            @if(isset($record))<div class="col-12"><label class="form-label">Catatan Perubahan</label><textarea name="change_notes" class="form-control" rows="2"></textarea></div>@endif
        </div>
        <hr class="my-4">
        <div class="d-flex justify-content-between"><a href="{{ route($routePrefix . '.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> {{ isset($record) ? 'Update' : 'Simpan' }}</button></div>
    </form>
</div></div>
@endsection