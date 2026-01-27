@extends('layouts.app')

@section('title', 'Permintaan Saya')

@section('breadcrumb')
    <li class="breadcrumb-item active">Permintaan Saya</li>
@endsection

@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Permintaan Akses Saya</h1>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Dokumen</th>
                        <th>Divisi</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Respons</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $i => $req)
                        <tr>
                            <td>{{ $requests->firstItem() + $i }}</td>
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
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>
                                @elseif($req->status == 'approved')
                                    <span class="badge bg-success"><i class="bi bi-check me-1"></i>Disetujui</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x me-1"></i>Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $req->created_at?->format('d M Y H:i') }}</td>
                            <td>
                                @if ($req->response_reason)
                                    <span class="text-muted"
                                        title="{{ $req->response_reason }}">{{ Str::limit($req->response_reason, 30) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mb-0">Anda belum mengajukan permintaan akses</p>
                            </td>
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
