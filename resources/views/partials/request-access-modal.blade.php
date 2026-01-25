<!-- Request Access Modal -->
<div class="modal fade" id="requestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('search.request-access') }}" method="POST">
                @csrf
                <input type="hidden" name="document_type" id="modalDocType">
                <input type="hidden" name="document_id" id="modalDocId">
                <div class="modal-header">
                    <h5 class="modal-title">Minta Akses Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Anda meminta akses untuk dokumen: <strong id="modalDocTitle"></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Alasan Permintaan <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required
                            placeholder="Jelaskan mengapa Anda membutuhkan akses..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var requestModal = document.getElementById('requestModal');
            if (requestModal) {
                requestModal.addEventListener('show.bs.modal', function(e) {
                    var btn = e.relatedTarget;
                    document.getElementById('modalDocType').value = btn.dataset.type;
                    document.getElementById('modalDocId').value = btn.dataset.id;
                    document.getElementById('modalDocTitle').textContent = btn.dataset.title;
                });
            }
        });
    </script>
@endpush
