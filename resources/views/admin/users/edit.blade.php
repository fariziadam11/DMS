@extends('layouts.app')
@section('title', 'Edit User')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
    <div class="page-header mb-4">
        <h1 class="page-title">Edit User: {{ $user->name }}</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST"
                data-confirm="Apakah Anda yakin ingin mengupdate user ini?" data-confirm-title="Konfirmasi Update User">
                @csrf @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                            value="{{ old('nip', $user->nip) }}" required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password <small class="text-muted">(kosongkan jika tidak
                                diubah)</small></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Valid From <span class="text-danger">*</span></label>
                        <input type="date" name="valid_from"
                            class="form-control @error('valid_from') is-invalid @enderror"
                            value="{{ old('valid_from', $user->valid_from ? $user->valid_from->format('Y-m-d') : '') }}"
                            required>
                        @error('valid_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Valid Till</label>
                        <div class="input-group">
                            <input type="date" name="valid_till" id="valid_till"
                                class="form-control @error('valid_till') is-invalid @enderror"
                                value="{{ old('valid_till', $user->valid_till ? $user->valid_till->format('Y-m-d') : '') }}">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0 me-2" type="checkbox" name="is_permanent"
                                    id="is_permanent" value="1"
                                    {{ old('is_permanent', is_null($user->valid_till)) ? 'checked' : '' }}>
                                <label class="form-check-label mb-0" for="is_permanent">Permanent</label>
                            </div>
                        </div>
                        @error('valid_till')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Divisi</label>
                        <select name="id_divisi" class="form-select">
                            <option value="">Pilih Divisi</option>
                            @foreach ($divisions as $d)
                                <option value="{{ $d->id }}"
                                    {{ old('id_divisi', $user->id_divisi) == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select name="id_department" id="id_department"
                            class="form-select @error('id_department') is-invalid @enderror"
                            {{ $user->id_divisi ? '' : 'disabled' }}>
                            <option value="">Pilih Department</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}"
                                    {{ $user->id_department == $dept->id ? 'selected' : '' }}>{{ $dept->nama_department }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jabatan</label>
                        <select name="id_jabatan" id="id_jabatan"
                            class="form-select @error('id_jabatan') is-invalid @enderror"
                            {{ $user->id_department ? '' : 'disabled' }}>
                            <option value="">Pilih Jabatan</option>
                            @foreach ($jabatans as $jab)
                                <option value="{{ $jab->id }}"
                                    {{ $user->id_jabatan == $jab->id ? 'selected' : '' }}>
                                    {{ $jab->nama_jabatan }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Mengubah jabatan akan otomatis mengubah Role user.</small>
                        @error('id_jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Roles <span class="text-danger">*</span></label>
                        <select name="roles[]" id="roles" class="form-select select2" multiple required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ collect(old('roles', $user->roles->pluck('id')->toArray()))->contains($role->id) ? 'selected' : '' }}>
                                    {{ $role->roles_name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Role saat ini telah dipilih otomatis. Anda dapat mengubahnya sesuai
                            kebutuhan.</small>
                        @error('roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <hr class="my-4">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
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
            const validTillInput = $('#valid_till');
            const permanentCheckbox = $('#is_permanent');

            // Handle Permanent Checkbox
            function toggleValidTill() {
                if (permanentCheckbox.is(':checked')) {
                    validTillInput.val('').prop('disabled', true);
                } else {
                    validTillInput.prop('disabled', false);
                }
            }
            permanentCheckbox.change(toggleValidTill);
            toggleValidTill(); // Run on load

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
