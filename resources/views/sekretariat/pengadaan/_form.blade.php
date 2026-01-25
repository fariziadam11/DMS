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
        <label class="form-label">Nomor</label>
        <input type="text" name="nomor" class="form-control @error('nomor') is-invalid @enderror" value="{{ old('nomor', $item->nomor ?? '') }}">
        @error('nomor')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $item->tanggal ?? '') }}">
        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror">
            <option value="">Pilih Kategori</option>
            <option value="TOR" {{ old('kategori', $item->kategori ?? '') == 'TOR' ? 'selected' : '' }}>TOR</option>
            <option value="Justifikasi" {{ old('kategori', $item->kategori ?? '') == 'Justifikasi' ? 'selected' : '' }}>Justifikasi</option>
            <option value="Kontrak" {{ old('kategori', $item->kategori ?? '') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
            <option value="SPMK" {{ old('kategori', $item->kategori ?? '') == 'SPMK' ? 'selected' : '' }}>SPMK</option>
            <option value="Berita Acara" {{ old('kategori', $item->kategori ?? '') == 'Berita Acara' ? 'selected' : '' }}>Berita Acara</option>
        </select>
        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Perihal</label>
        <textarea name="perihal" class="form-control @error('perihal') is-invalid @enderror" rows="3">{{ old('perihal', $item->perihal ?? '') }}</textarea>
        @error('perihal')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tujuan</label>
        <textarea name="tujuan" class="form-control @error('tujuan') is-invalid @enderror" rows="3">{{ old('tujuan', $item->tujuan ?? '') }}</textarea>
        @error('tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Masa Akhir</label>
        <input type="text" name="masa_akhir" class="form-control @error('masa_akhir') is-invalid @enderror" value="{{ old('masa_akhir', $item->masa_akhir ?? '') }}">
        @error('masa_akhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
