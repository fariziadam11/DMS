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
        <label class="form-label">Tahun</label>
        <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $item->tahun ?? '') }}">
        @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $item->tanggal ?? '') }}">
        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
