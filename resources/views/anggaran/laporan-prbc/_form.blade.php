{{-- Form fields for Laporan PRBC --}}
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
    <label class="form-label">Tahun </label>
    <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $record->tahun ?? '') }}" >
    @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-md-6 mb-3">
    <label class="form-label">Bulan </label>
    <select name="bulan" class="form-select @error('bulan') is-invalid @enderror" >
        <option value="">Pilih Bulan</option>
        <option value="1" {{ old('bulan', $record->bulan ?? '') == 1 ? 'selected' : '' }}>Januari</option>
        <option value="2" {{ old('bulan', $record->bulan ?? '') == 2 ? 'selected' : '' }}>Februari</option>
        <option value="3" {{ old('bulan', $record->bulan ?? '') == 3 ? 'selected' : '' }}>Maret</option>
        <option value="4" {{ old('bulan', $record->bulan ?? '') == 4 ? 'selected' : '' }}>April</option>
        <option value="5" {{ old('bulan', $record->bulan ?? '') == 5 ? 'selected' : '' }}>Mei</option>
        <option value="6" {{ old('bulan', $record->bulan ?? '') == 6 ? 'selected' : '' }}>Juni</option>
        <option value="7" {{ old('bulan', $record->bulan ?? '') == 7 ? 'selected' : '' }}>Juli</option>
        <option value="8" {{ old('bulan', $record->bulan ?? '') == 8 ? 'selected' : '' }}>Agustus</option>
        <option value="9" {{ old('bulan', $record->bulan ?? '') == 9 ? 'selected' : '' }}>September</option>
        <option value="10" {{ old('bulan', $record->bulan ?? '') == 10 ? 'selected' : '' }}>Oktober</option>
        <option value="11" {{ old('bulan', $record->bulan ?? '') == 11 ? 'selected' : '' }}>November</option>
        <option value="12" {{ old('bulan', $record->bulan ?? '') == 12 ? 'selected' : '' }}>Desember</option>
    </select>
    @error('bulan')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Sifat Dokumen</label>
        <select name="sifat_dokumen" class="form-select">
            <option value="Umum" {{ old('sifat_dokumen', $record->sifat_dokumen ?? 'Umum') == 'Umum' ? 'selected' : '' }}>Umum</option>
            <option value="Internal" {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Internal' ? 'selected' : '' }}>Internal</option>
            <option value="Rahasia" {{ old('sifat_dokumen', $record->sifat_dokumen ?? '') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
        </select>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">File Dokumen</label>
        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
        @if(isset($record) && $record->file_name)
            <div class="mt-2"><small class="text-muted">File saat ini: {{ $record->file_name }}</small></div>
        @endif
        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>