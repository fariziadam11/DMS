<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Divisi <span class="text-danger">*</span></label>
        <select name="id_divisi" class="form-select @error('id_divisi') is-invalid @enderror" required>
            <option value="">Pilih Divisi</option>
            @foreach (\App\Models\MasterDivisi::all() as $divisi)
                <option value="{{ $divisi->id }}"
                    {{ old('id_divisi', $item->id_divisi ?? '') == $divisi->id ? 'selected' : '' }}>
                    {{ $divisi->nama_divisi }}</option>
            @endforeach
        </select>
        @error('id_divisi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nomor</label>
        <input type="text" name="nomor" class="form-control @error('nomor') is-invalid @enderror"
            value="{{ old('nomor', $item->nomor ?? '') }}">
        @error('nomor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
            value="{{ old('tanggal', isset($item->tanggal) ? $item->tanggal->format('Y-m-d') : '') }}">
        @error('tanggal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror">
            <option value="">Pilih Kategori</option>
            <option value="Surat Masuk" {{ old('kategori', $item->kategori ?? '') == 'Surat Masuk' ? 'selected' : '' }}>
                Surat Masuk</option>
            <option value="Surat Keluar"
                {{ old('kategori', $item->kategori ?? '') == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
        </select>
        @error('kategori')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Perihal</label>
        <textarea name="perihal" class="form-control @error('perihal') is-invalid @enderror" rows="3">{{ old('perihal', $item->perihal ?? '') }}</textarea>
        @error('perihal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Dari</label>
        <input type="text" name="dari" class="form-control @error('dari') is-invalid @enderror"
            value="{{ old('dari', $item->dari ?? '') }}">
        @error('dari')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tujuan</label>
        <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror"
            value="{{ old('tujuan', $item->tujuan ?? '') }}">
        @error('tujuan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Disposisi</label>
        <input type="text" name="disposisi" class="form-control @error('disposisi') is-invalid @enderror"
            value="{{ old('disposisi', $item->disposisi ?? '') }}">
        @error('disposisi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Sifat Dokumen</label>
        <select name="sifat_dokumen" class="form-select">
            <option value="Umum"
                {{ old('sifat_dokumen', $item->sifat_dokumen ?? 'Umum') == 'Umum' ? 'selected' : '' }}>Umum</option>
            <option value="Internal"
                {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Internal' ? 'selected' : '' }}>Internal
            </option>
            <option value="Rahasia"
                {{ old('sifat_dokumen', $item->sifat_dokumen ?? '') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">File Dokumen</label>
        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
        @if (isset($item) && $item->file_name)
            <div class="mt-2"><small class="text-muted">File saat ini: {{ $item->file_name }}</small></div>
        @endif
        @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
