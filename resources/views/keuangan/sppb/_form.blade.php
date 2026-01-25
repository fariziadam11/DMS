{{-- Form fields for SPPB --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Surat Bayar <span class="text-danger">*</span></label>
        <select name="id_surat_bayar" class="form-select @error('id_surat_bayar') is-invalid @enderror" required>
            <option value="">Pilih Surat Bayar</option>
            @foreach ($suratBayars ?? [] as $surat)
                <option value="{{ $surat->id }}"
                    {{ old('id_surat_bayar', $record->id_surat_bayar ?? '') == $surat->id ? 'selected' : '' }}>
                    {{ $surat->nomor_surat_bayar }} -
                    {{ $surat->tanggal_surat_bayar ? $surat->tanggal_surat_bayar->format('d/m/Y') : '' }}
                </option>
            @endforeach
        </select>
        @error('id_surat_bayar')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">SPB <span class="text-danger">*</span></label>
        <select name="id_spb" class="form-select @error('id_spb') is-invalid @enderror" required>
            <option value="">Pilih SPB</option>
            @foreach ($spbs ?? [] as $spb)
                <option value="{{ $spb->id }}"
                    {{ old('id_spb', $record->id_spb ?? '') == $spb->id ? 'selected' : '' }}>
                    {{ $spb->nomor_spb }} - {{ $spb->tanggal_spb ? $spb->tanggal_spb->format('d/m/Y') : '' }}
                </option>
            @endforeach
        </select>
        @error('id_spb')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
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
        <label class="form-label">Tanggal SPPB </label>
        <input type="date" name="tanggal_sppb" class="form-control @error('tanggal_sppb') is-invalid @enderror"
            value="{{ old('tanggal_sppb', isset($record) && $record->tanggal_sppb ? $record->tanggal_sppb->format('Y-m-d') : '') }}">
        @error('tanggal_sppb')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nomor SPPB </label>
        <input type="text" name="nomor_sppb" class="form-control @error('nomor_sppb') is-invalid @enderror"
            value="{{ old('nomor_sppb', $record->nomor_sppb ?? '') }}">
        @error('nomor_sppb')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nominal </label>
        <input type="number" name="nominal" class="form-control @error('nominal') is-invalid @enderror"
            value="{{ old('nominal', $record->nominal ?? '') }}" step="0.01">
        @error('nominal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tujuan </label>
        <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror"
            value="{{ old('tujuan', $record->tujuan ?? '') }}">
        @error('tujuan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Dibuat Oleh </label>
        <input type="text" name="dibuat" class="form-control @error('dibuat') is-invalid @enderror"
            value="{{ old('dibuat', $record->dibuat ?? '') }}">
        @error('dibuat')
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
