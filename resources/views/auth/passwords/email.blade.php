@extends('layouts.guest')
@section('title', 'Lupa Password')
@section('content')
    <div class="text-center mb-4">
        <h4>Reset Password</h4>
        <p class="text-muted">Masukkan email yang terdaftar untuk menerima link reset password.</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-envelope me-2"></i> Kirim Link Reset
            </button>
            <a href="{{ route('login') }}" class="btn btn-light text-muted">
                Kembali ke Login
            </a>
        </div>
    </form>
@endsection
