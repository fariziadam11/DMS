{{-- Form fields for Aspurjab --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Divisi</label>
        @if(auth()->user()->isSuperAdmin())
            <select name="id_divisi" class="form-select">
                <option value="">Pilih Divisi</option>
                @foreach($divisions ?? [] as $divisi)
                    <option value="{{ $divisi->id }}" {{ old('id_divisi', $record->id_divisi ?? '') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                @endforeach
            </select>
        @else
            <input type="hidden" name="id_divisi" value="{{ auth()->user()->id_divisi }}">
            <input type="text" class="form-control" value="{{ auth()->user()->divisi->nama_divisi ?? '' }}" readonly>
        @endif
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nomor</label>
        <input type="text" name="nomor" class="form-control @error('nomor') is-invalid @enderror"
            value="{{ old('nomor', $record->nomor ?? '') }}">
        @error('nomor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
            value="{{ old('tanggal', ($record->tanggal ?? null) ? \Carbon\Carbon::parse($record->tanggal)->format('Y-m-d') : '') }}">
        @error('tanggal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
            value="{{ old('nama', $record->nama ?? '') }}">
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Mulai</label>
        <input type="date" name="mulai" class="form-control @error('mulai') is-invalid @enderror"
            value="{{ old('mulai', ($record->mulai ?? null) ? \Carbon\Carbon::parse($record->mulai)->format('Y-m-d') : '') }}">
        @error('mulai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Akhir</label>
        <input type="date" name="akhir" class="form-control @error('akhir') is-invalid @enderror"
            value="{{ old('akhir', ($record->akhir ?? null) ? \Carbon\Carbon::parse($record->akhir)->format('Y-m-d') : '') }}">
        @error('akhir')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori</label>
        <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
            value="{{ old('kategori', $record->kategori ?? '') }}" placeholder="Kontrak Induk, Nama Perjanjian, dll">
        @error('kategori')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Sifat Dokumen</label>
        <select name="sifat_dokumen" class="form-select">
            <option value="Umum"
                {{ old('sifat_dokumen', $record->sifat_dokumen ?? 'Umum') == 'Umum' ? 'selected' : '' }}>Umum</option>
            <option value="Internal" {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Internal' ? 'selected' : '' }}>Internal</option>
            <option value="Rahasia"
                {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Rahasia' ? 'selected' : '' }}>Rahasia
            </option>
        </select>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">File Dokumen</label>
        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
        @if (isset($record) && $record->file_name)
            <div class="mt-2"><small class="text-muted">File saat ini: {{ $record->file_name }}</small></div>
        @endif
        @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
