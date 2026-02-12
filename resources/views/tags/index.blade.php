@extends('layouts.app')

@section('title', 'Manajemen Tag')

@section('content')
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <h1 class="page-title">Manajemen Tag</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
            <i class="bi bi-plus-circle me-1"></i> Buat Tag Baru
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Tag</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Dokumen</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                        <tr>
                            <td>
                                <span class="badge" style="background-color: {{ $tag->color }}">
                                    {{ $tag->name }}
                                </span>
                            </td>
                            <td>{{ $tag->description ?? '-' }}</td>
                            <td>
                                <a href="{{ route('search', ['tag' => $tag->slug]) }}">
                                    {{ $tag->documents_count }} dokumen
                                </a>
                            </td>
                            <td>{{ $tag->creator->name ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editTagModal{{ $tag->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('master.tags.destroy', $tag->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal for this tag -->
                        <div class="modal fade" id="editTagModal{{ $tag->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('master.tags.update', $tag->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Tag</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Tag <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ $tag->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea name="description" class="form-control" rows="2">{{ $tag->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Warna</label>
                                                <input type="color" name="color" class="form-control form-control-color"
                                                    value="{{ $tag->color }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada tag. Klik "Buat Tag Baru" untuk membuat tag pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $tags->links() }}
        </div>
    </div>

    <!-- Create Tag Modal -->
    <div class="modal fade" id="createTagModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('master.tags.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Buat Tag Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Tag <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="contoh: Urgent, Q1-2025, Audit">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi optional untuk tag ini"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Warna</label>
                            <input type="color" name="color" class="form-control form-control-color" value="#6c757d">
                            <small class="text-muted">Pilih warna untuk badge tag</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Buat Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
