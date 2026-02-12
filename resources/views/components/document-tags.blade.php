{{-- Document Tags Component --}}
{{-- Usage: @include('components.document-tags', ['record' => $record, 'allTags' => $allTags, 'module' => 'akuntansi', 'submodule' => 'aturan-kebijakan']) --}}

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Tags</h5>
        @if (isset($permissions['edit']) && $permissions['edit'])
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTagModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Tag
            </button>
        @endif
    </div>
    <div class="card-body">
        @if ($record->tags && $record->tags->count() > 0)
            <div class="d-flex flex-wrap gap-2">
                @foreach ($record->tags as $tag)
                    <div class="position-relative">
                        <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="text-decoration-none">
                            <span class="badge fs-6 py-2 px-3" style="background-color: {{ $tag->color }}">
                                <i class="bi bi-tag-fill me-1"></i>{{ $tag->name }}
                            </span>
                        </a>
                        @if (isset($permissions['edit']) && $permissions['edit'])
                            <form
                                action="{{ route('documents.tags.detach', [$module, $submodule, $record->id, $tag->id]) }}"
                                method="POST" class="d-inline position-absolute top-0 start-100 translate-middle">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger rounded-circle p-0"
                                    style="width: 20px; height: 20px; font-size: 10px;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Belum ada tag untuk dokumen ini.
            </p>
        @endif
    </div>
</div>

{{-- Add Tag Modal --}}
@if (isset($permissions['edit']) && $permissions['edit'])
    <div class="modal fade" id="addTagModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('documents.tags.attach', [$module, $submodule, $record->id]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Tag ke Dokumen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Tag <span class="text-danger">*</span></label>
                            <select name="tag_id" class="form-select" required>
                                <option value="">-- Pilih Tag --</option>
                                @foreach ($allTags as $tag)
                                    @php
                                        $alreadyAttached = $record->tags->contains('id', $tag->id);
                                    @endphp
                                    <option value="{{ $tag->id }}" {{ $alreadyAttached ? 'disabled' : '' }}>
                                        {{ $tag->name }} {{ $alreadyAttached ? '(Sudah ditambahkan)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                Belum ada tag yang sesuai?
                                <a href="{{ route('master.tags.index') }}" target="_blank">Buat tag baru</a>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
