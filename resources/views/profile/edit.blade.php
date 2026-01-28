@extends('layouts.app')

@section('title', 'Profil Saya')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">User</a></li>
    <li class="breadcrumb-item active">Profil</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3 position-relative d-inline-block">
                        <img src="{{ $profile->photo_url ?? asset('assets/img/default-avatar.png') }}"
                            class="rounded-circle img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;"
                            alt="User Photo">
                        <label for="photo_input"
                            class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle"
                            style="width: 32px; height: 32px; padding: 4px; cursor: pointer;">
                            <i class="bi bi-camera"></i>
                        </label>
                    </div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $profile->divisi ?? 'No Division' }}</p>
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary me-2">{{ $user->roles->first()->roles_name ?? 'User' }}</span>
                        <span
                            class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Info Akun</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small mb-1">Email</label>
                        <div class="fw-bold">{{ $user->email }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="small mb-1">Username</label>
                        <div class="fw-bold">{{ $user->username }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="small mb-1">NIP</label>
                        <div class="fw-bold">{{ $user->nip }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="small mb-1">Terdaftar Sejak</label>
                        <div class="fw-bold">{{ $user->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#personal" data-bs-toggle="tab">Data Pribadi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#employment" data-bs-toggle="tab">Kepegawaian</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact" data-bs-toggle="tab">Kontak & Alamat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#security" data-bs-toggle="tab">Keamanan</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Hidden Photo Input triggered by icon -->
                        <input type="file" name="photo" id="photo_input" class="d-none" accept="image/*"
                            onchange="previewImage(this)">

                        <div class="tab-content">
                            <!-- Personal Info Tab -->
                            <div class="tab-pane fade show active" id="personal">
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1">Nama Depan</label>
                                        <input class="form-control" name="first_name" type="text"
                                            value="{{ old('first_name', $profile->first_name) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">Nama Belakang</label>
                                        <input class="form-control" name="last_name" type="text"
                                            value="{{ old('last_name', $profile->last_name) }}">
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1">Tempat/Tanggal Lahir</label>
                                        <input class="form-control" name="birth_date" type="date"
                                            value="{{ old('birth_date', $profile->birth_date ? $profile->birth_date->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">Jenis Kelamin</label>
                                        <select class="form-select" name="gender">
                                            <option value="">Pilih</option>
                                            <option value="1"
                                                {{ old('gender', $profile->gender) == 1 ? 'selected' : '' }}>Laki-laki
                                            </option>
                                            <option value="2"
                                                {{ old('gender', $profile->gender) == 2 ? 'selected' : '' }}>Perempuan
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1">Agama</label>
                                        <select class="form-select" name="religion">
                                            <option value="">Pilih</option>
                                            @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $rel)
                                                <option value="{{ $rel }}"
                                                    {{ old('religion', $profile->religion) == $rel ? 'selected' : '' }}>
                                                    {{ $rel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">Golongan Darah</label>
                                        <select class="form-select" name="blood_type">
                                            <option value="">Pilih</option>
                                            @foreach (['A', 'B', 'AB', 'O'] as $blood)
                                                <option value="{{ $blood }}"
                                                    {{ old('blood_type', $profile->blood_type) == $blood ? 'selected' : '' }}>
                                                    {{ $blood }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1">Status Pernikahan</label>
                                        <select class="form-select" name="marital_status">
                                            <option value="">Pilih</option>
                                            @foreach (['Single', 'Married', 'Divorced', 'Widowed'] as $status)
                                                <option value="{{ $status }}"
                                                    {{ old('marital_status', $profile->marital_status) == $status ? 'selected' : '' }}>
                                                    {{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">Pendidikan Terakhir</label>
                                        <input class="form-control" name="education_level" type="text"
                                            value="{{ old('education_level', $profile->education_level) }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="small mb-1">Upload Tanda Tangan</label>
                                    <input class="form-control" name="signature_file" type="file" accept="image/*">
                                    @if ($profile->signature_file)
                                        <div class="mt-2">
                                            <img src="{{ $profile->signature_url }}" height="60" alt="Signature">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Employment Tab -->
                            <div class="tab-pane fade" id="employment">
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1">NIP (Karyawan)</label>
                                        <input class="form-control" type="text" value="{{ $user->nip }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">Status Karyawan</label>
                                        <select class="form-select" name="employee_status">
                                            <option value="">Pilih</option>
                                            @foreach (['Permanent', 'Contract', 'Internship'] as $stat)
                                                <option value="{{ $stat }}"
                                                    {{ old('employee_status', $profile->employee_status) == $stat ? 'selected' : '' }}>
                                                    {{ $stat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1">Tanggal Bergabung</label>
                                        <input class="form-control" name="join_date" type="date"
                                            value="{{ old('join_date', $profile->join_date ? $profile->join_date->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">NPWP</label>
                                        <input class="form-control" name="npwp" type="text"
                                            value="{{ old('npwp', $profile->npwp) }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="small mb-1">No. KTP</label>
                                    <input class="form-control" name="ktp" type="text"
                                        value="{{ old('ktp', $profile->ktp) }}">
                                </div>
                            </div>

                            <!-- Contact Tab -->
                            <div class="tab-pane fade" id="contact">
                                <div class="mb-3">
                                    <label class="small mb-1">Alamat Lengkap</label>
                                    <textarea class="form-control" name="address" rows="3">{{ old('address', $profile->address) }}</textarea>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-4">
                                        <label class="small mb-1">Kota</label>
                                        <input class="form-control" name="city" type="text"
                                            value="{{ old('city', $profile->city) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small mb-1">Negara</label>
                                        <input class="form-control" name="country" type="text"
                                            value="{{ old('country', $profile->country) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small mb-1">Kode Pos</label>
                                        <input class="form-control" name="poscode" type="text"
                                            value="{{ old('poscode', $profile->poscode) }}">
                                    </div>
                                </div>

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1">No. Telepon</label>
                                        <input class="form-control" name="phone" type="text"
                                            value="{{ old('phone', $profile->phone) }}">
                                    </div>
                                </div>

                                <h6 class="mt-4 mb-3 text-primary">Kontak Darurat</h6>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1">Nama Kontak</label>
                                        <input class="form-control" name="emergency_contact_name" type="text"
                                            value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1">No. Telepon Kontak</label>
                                        <input class="form-control" name="emergency_contact_phone" type="text"
                                            value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}">
                                    </div>
                                </div>

                                <h6 class="mt-4 mb-3 text-primary">Media Sosial</h6>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1">Instagram</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                                            <input class="form-control" name="instagram" type="text"
                                                value="{{ old('instagram', $profile->instagram) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1">Facebook</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                                            <input class="form-control" name="facebook" type="text"
                                                value="{{ old('facebook', $profile->facebook) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1">Twitter (X)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-twitter-x"></i></span>
                                            <input class="form-control" name="twitter" type="text"
                                                value="{{ old('twitter', $profile->twitter) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small mb-1">LinkedIn / Website</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-linkedin"></i></span>
                                            <input class="form-control" name="linkedin" type="text"
                                                value="{{ old('linkedin', $profile->website) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security/Password Tab -->
                            <div class="tab-pane fade" id="security">
                                <div class="alert alert-info">
                                    <i class="bi bi-shield-lock me-2"></i> Ganti kata sandi akun Anda secara berkala untuk
                                    keamanan.
                                </div>
                                <form action="{{ route('profile.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="small mb-1">Kata Sandi Saat Ini</label>
                                        <input class="form-control" name="current_password" type="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1">Kata Sandi Baru</label>
                                        <input class="form-control" name="password" type="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1">Konfirmasi Kata Sandi Baru</label>
                                        <input class="form-control" name="password_confirmation" type="password"
                                            required>
                                    </div>
                                    <button class="btn btn-danger" type="submit">Ganti Password</button>
                                </form>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.img-thumbnail').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
