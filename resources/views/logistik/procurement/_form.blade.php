<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Divisi <span class="text-danger">*</span></label>
        <select name="id_divisi" class="form-select @error('id_divisi') is-invalid @enderror" required>
            <option value="">Pilih Divisi</option>
            @foreach (\App\Models\MasterDivisi::all() as $divisi)
                <option value="{{ $divisi->id }}" {{ old('id_divisi', $item->id_divisi ?? '') == $divisi->id ? 'selected' : '' }}>
                    {{ $divisi->nama_divisi }}</option>
            @endforeach
        </select>
        @error('id_divisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">No DRP</label>
        <input type="text" name="no_drp" class="form-control @error('no_drp') is-invalid @enderror" value="{{ old('no_drp', $item->no_drp ?? '') }}">
        @error('no_drp')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Kegiatan</label>
        <textarea name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror" rows="3">{{ old('nama_kegiatan', $item->nama_kegiatan ?? '') }}</textarea>
        @error('nama_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Direktorat</label>
        <input type="text" name="direktorat" class="form-control @error('direktorat') is-invalid @enderror" value="{{ old('direktorat', $item->direktorat ?? '') }}">
        @error('direktorat')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Sifat Dokumen</label>
        <select name="sifat_dokumen" class="form-select">
            <option value="Umum" {{ old('sifat_dokumen', $item->sifat_dokumen ?? 'Umum') == 'Umum' ? 'selected' : '' }}>Umum</option>
            <option value="Internal" {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Internal' ? 'selected' : '' }}>Internal</option>
            <option value="Rahasia" {{ old('sifat_dokumen', $item->sifat_dokumen ?? '') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">File Dokumen</label>
        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
        @if (isset($item) && $item->file_name)
            <div class="mt-2"><small class="text-muted">File saat ini: {{ $item->file_name }}</small></div>
        @endif
        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
