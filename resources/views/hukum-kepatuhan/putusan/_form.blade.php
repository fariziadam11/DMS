{{-- Form fields for Putusan --}}
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
        <label class="form-label">Tanggal </label>
        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
            value="{{ old('tanggal', isset($record) && $record->tanggal ? $record->tanggal->format('Y-m-d') : '') }}">
        @error('tanggal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nomor </label>
        <input type="text" name="nomor" class="form-control @error('nomor') is-invalid @enderror"
            value="{{ old('nomor', $record->nomor ?? '') }}">
        @error('nomor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Klarifikasi </label>
        <input type="text" name="klarifikasi" class="form-control @error('klarifikasi') is-invalid @enderror"
            value="{{ old('klarifikasi', $record->klarifikasi ?? '') }}">
        @error('klarifikasi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tingkat Pengadilan </label>
        <input type="text" name="tingkat_pengadilan"
            class="form-control @error('tingkat_pengadilan') is-invalid @enderror"
            value="{{ old('tingkat_pengadilan', $record->tingkat_pengadilan ?? '') }}">
        @error('tingkat_pengadilan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="">Pilih Status</option>
            <option value="Menang" {{ old('status', $record->status ?? '') == 'Menang' ? 'selected' : '' }}>Menang
            </option>
            <option value="Kalah" {{ old('status', $record->status ?? '') == 'Kalah' ? 'selected' : '' }}>Kalah
            </option>
            <option value="Proses" {{ old('status', $record->status ?? '') == 'Proses' ? 'selected' : '' }}>Proses
            </option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Pihak </label>
        <input type="text" name="pihak" class="form-control @error('pihak') is-invalid @enderror"
            value="{{ old('pihak', $record->pihak ?? '') }}">
        @error('pihak')
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
