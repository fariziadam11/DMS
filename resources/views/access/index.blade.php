@extends('layouts.app')

@section('title', 'Manajemen Akses')

@section('breadcrumb')
    <li class="breadcrumb-item active">Akses Dokumen</li>
@endsection

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Permintaan Akses Dokumen</h1>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-10">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Peminta</th>
                        <th>Dokumen</th>
                        <th>Divisi</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $i => $req)
                        <tr>
                            <td>{{ $requests->firstItem() + $i }}</td>
                            <td><strong>{{ $req->requester?->name ?? '-' }}</strong></td>
                            <td>
                                @if ($req->document)
                                    <strong>{{ $req->document->judul ?? ($req->document->perihal ?? ($req->document->nama ?? ($req->document->nomor ?? 'Dokumen #' . $req->document_id))) }}</strong>
                                    <br>
                                    <small
                                        class="text-muted">{{ Str::headline(str_replace('_', ' ', $req->document_type)) }}</small>
                                @else
                                    {{ Str::headline(str_replace('_', ' ', $req->document_type)) }} #{{ $req->document_id }}
                                @endif
                            </td>
                            <td>{{ $req->divisi?->nama_divisi ?? '-' }}</td>
                            <td>{{ Str::limit($req->request_reason, 50) }}</td>
                            <td>
                                @if ($req->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($req->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $req->created_at?->format('d M Y') }}</td>
                            <td>
                                @php
                                    $currentUser = auth()->user();
                                    $menuAccess = \App\Models\BaseMenu::where('code_name', 'access.index')->first();
                                    $funcApproval = \App\Models\BaseFunction::where(
                                        'function_name',
                                        'Approval',
                                    )->first();

                                    $userHasApproval = $currentUser->isSuperAdmin();
                                    if (!$userHasApproval && $menuAccess && $funcApproval) {
                                        $userHasApproval = $currentUser->hasMenuFunction(
                                            $menuAccess->id,
                                            $funcApproval->id,
                                        );
                                    }

                                    // Check if user has division access to this request (simple check based on index logic)
                                    // For now, we rely on controller filter, but strictly speaking checking approval privilege is key.

                                @endphp

                                @if ($req->status == 'pending')
                                    @if ($userHasApproval && $req->id_user != $currentUser->id)
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#approveModal{{ $req->id }}">
                                            <i class="bi bi-check"></i> Setujui
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $req->id }}">
                                            <i class="bi bi-x"></i> Tolak
                                        </button>

                                        <!-- Approve Modal -->
                                        <div class="modal fade" id="approveModal{{ $req->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form action="{{ route('access.approve', $req->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Setujui & Atur Hak Akses</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <div class="alert alert-info py-2 mb-3">
                                                                <small>Permintaan dari:
                                                                    <strong>{{ $req->requester?->name }}</strong> untuk
                                                                    dokumen
                                                                    <strong>{{ $req->document->judul ?? ($req->document->perihal ?? ($req->document->nama ?? ($req->document->nomor ?? Str::headline(str_replace('_', ' ', $req->document_type)) . ' #' . $req->document_id))) }}</strong></small>
                                                            </div>
                                                            <p class="mb-2">Pilih izin yang diberikan:</p>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]" value="read"
                                                                    id="perm_read_{{ $req->id }}" checked
                                                                    onclick="return false;">
                                                                <label class="form-check-label"
                                                                    for="perm_read_{{ $req->id }}">
                                                                    View (Melihat Data) <span
                                                                        class="text-muted text-xs ms-1">*Wajib</span>
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]" value="download"
                                                                    id="perm_download_{{ $req->id }}" checked>
                                                                <label class="form-check-label"
                                                                    for="perm_download_{{ $req->id }}">
                                                                    Download (Mengunduh File)
                                                                </label>
                                                            </div>
                                                            <!-- Edit/Delete permissions removed as per request -->
                                                            <div class="mt-3">
                                                                <label class="form-label">Catatan (Opsional)</label>
                                                                <textarea name="reason" class="form-control" rows="2" placeholder="Catatan persetujuan..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-success">Setujui
                                                                Akses</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form action="{{ route('access.reject', $req->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tolak Permintaan</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="alert alert-danger py-2 mb-3">
                                                                <small>Menolak permintaan dari:
                                                                    <strong>{{ $req->requester?->name }}</strong> untuk
                                                                    dokumen
                                                                    <strong>{{ $req->document->judul ?? ($req->document->perihal ?? ($req->document->nama ?? ($req->document->nomor ?? Str::headline(str_replace('_', ' ', $req->document_type)) . ' #' . $req->document_id))) }}</strong></small>
                                                            </div>
                                                            <label class="form-label">Alasan Penolakan <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Tolak</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        @if ($req->id_user == $currentUser->id)
                                            <span class="text-muted text-xs">Menunggu Persetujuan</span>
                                        @else
                                            <span class="text-muted text-xs">Akses View Only</span>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada permintaan akses</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($requests->hasPages())
            <div class="card-footer">{{ $requests->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection
