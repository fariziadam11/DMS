{{-- Form fields for Surat Investasi --}}
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
        <label class="form-label">Tipe <span class="text-danger">*</span></label>
        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="">Pilih Tipe</option>
            <option value="1" {{ old('type', $record->type ?? '') == 1 ? 'selected' : '' }}>Masuk</option>
            <option value="2" {{ old('type', $record->type ?? '') == 2 ? 'selected' : '' }}>Keluar</option>
        </select>
        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Surat </label>
        <input type="date" name="tgl_surat" class="form-control @error('tgl_surat') is-invalid @enderror"
            value="{{ old('tgl_surat', isset($record) && $record->tgl_surat ? $record->tgl_surat->format('Y-m-d') : '') }}">
        @error('tgl_surat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">No. Surat </label>
        <input type="text" name="no_surat" class="form-control @error('no_surat') is-invalid @enderror"
            value="{{ old('no_surat', $record->no_surat ?? '') }}">
        @error('no_surat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">No. Agenda </label>
        <input type="text" name="no_agenda" class="form-control @error('no_agenda') is-invalid @enderror"
            value="{{ old('no_agenda', $record->no_agenda ?? '') }}">
        @error('no_agenda')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Perihal </label>
        <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror"
            value="{{ old('perihal', $record->perihal ?? '') }}">
        @error('perihal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Jenis </label>
        <select name="jenis" class="form-select @error('jenis') is-invalid @enderror">
            <option value="">Pilih Jenis</option>
            <option value="1" {{ old('jenis', $record->jenis ?? '') == 1 ? 'selected' : '' }}>Keluar</option>
            <option value="2" {{ old('jenis', $record->jenis ?? '') == 2 ? 'selected' : '' }}>Masuk</option>
        </select>
        @error('jenis')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Perusahaan </label>
        <input type="text" name="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror"
            value="{{ old('nama_perusahaan', $record->nama_perusahaan ?? '') }}">
        @error('nama_perusahaan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Keterangan </label>
        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $record->keterangan ?? '') }}</textarea>
        @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Klasifikasi </label>
        <select name="klasifikasi" class="form-select @error('klasifikasi') is-invalid @enderror">
            <option value="">Pilih Klasifikasi</option>
            <option value="1" {{ old('klasifikasi', $record->klasifikasi ?? '') == 1 ? 'selected' : '' }}>Penting
            </option>
            <option value="2" {{ old('klasifikasi', $record->klasifikasi ?? '') == 2 ? 'selected' : '' }}>Biasa
            </option>
            <option value="3" {{ old('klasifikasi', $record->klasifikasi ?? '') == 3 ? 'selected' : '' }}>Rahasia
            </option>
        </select>
        @error('klasifikasi')
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
