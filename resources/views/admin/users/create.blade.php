@extends('layouts.app')
@section('title', 'Tambah User')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Tambah User</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST"
                data-confirm="Apakah Anda yakin ingin menambah user ini?" data-confirm-title="Konfirmasi Tambah User">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                            value="{{ old('nip') }}" required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Divisi</label>
                        <select name="id_divisi" class="form-select">
                            <option value="">Pilih Divisi</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}" {{ old('id_divisi') == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select name="id_department" id="id_department"
                            class="form-select @error('id_department') is-invalid @enderror" disabled>
                            <option value="">Pilih Divisi Terlebih Dahulu</option>
                        </select>
                        @error('id_department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jabatan</label>
                        <select name="id_jabatan" id="id_jabatan"
                            class="form-select @error('id_jabatan') is-invalid @enderror" disabled>
                            <option value="">Pilih Department Terlebih Dahulu</option>
                        </select>
                        <small class="text-muted">Role akan otomatis ditentukan berdasarkan jabatan yang dipilih.</small>
                        @error('id_jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Roles <span class="text-danger">*</span></label>
                        <select name="roles[]" id="roles" class="form-select select2" multiple required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                    {{ $role->roles_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Jabatan akan otomatis memilih role default, namun Anda dapat menambah atau
                            mengurangi role secara manual.</small>
                        @error('roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <hr class="my-4">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih Roles'
            });

            const divSelect = $('select[name="id_divisi"]');
            const deptSelect = $('#id_department');
            const jabSelect = $('#id_jabatan');
            const rolesSelect = $('#roles');

            // Map of Jabatan ID to Role ID (would need to fetch this or simple heuristic)
            // Ideally we need an endpoint to get the default role for a Jabatan.
            // Let's assume we can fetch it when Jabatan is selected.

            divSelect.change(function() {
                const divId = $(this).val();
                deptSelect.html('<option value="">Loading...</option>').prop('disabled', true);
                jabSelect.html('<option value="">Loading...</option>').prop('disabled', true);

                if (divId) {
                    // Fetch Department (Parent)
                    $.get(`/admin/users/ajax/departments/${divId}`, function(data) {
                        let options = '<option value="">Pilih Department</option>';
                        data.forEach(function(item) {
                            options +=
                                `<option value="${item.id}" selected>${item.nama_department}</option>`;
                        });
                        deptSelect.html(options).prop('disabled', false);
                    });

                    // Fetch Jabatans (Children of Divisi)
                    $.get(`/admin/users/ajax/jabatans/${divId}`, function(data) {
                        let options = '<option value="">Pilih Jabatan</option>';
                        data.forEach(function(item) {
                            options +=
                                `<option value="${item.id}">${item.nama_jabatan}</option>`;
                        });
                        jabSelect.html(options).prop('disabled', false);
                    });

                } else {
                    deptSelect.html('<option value="">Pilih Divisi Terlebih Dahulu</option>');
                    jabSelect.html('<option value="">Pilih Divisi Terlebih Dahulu</option>');
                }
            });

            jabSelect.change(function() {
                const jabatanId = $(this).val();
                if (jabatanId) {
                    // We need to find the default role for this Jabatan.
                    // Since we don't have a direct endpoint for "get default role", we might need to add one or use a workaround?
                    // Let's check if we can add a simple endpoint.
                    // Or, we can piggyback existing or just assume the user will pick roles manually, but the requirement implies "like rangkap role", existing flow was auto-assign.
                    // Best DX: select Jabatan -> auto select the default role in the list.

                    $.get(`/admin/api/jabatan/${jabatanId}/default-role`, function(data) {
                        if (data.role_id) {
                            // Add to current selection
                            let currentRoles = rolesSelect.val() || [];
                            if (!currentRoles.includes(data.role_id.toString())) {
                                currentRoles.push(data.role_id.toString());
                                rolesSelect.val(currentRoles).trigger('change');
                            }
                        }
                    });
                }
            });

            // Department change listener removed as it is auto-determined by Divisi
        });
    </script>
@endpush
