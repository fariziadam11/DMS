@extends('emails.layouts.email-layout')

@section('title', 'Reset Password - Dapentelkom DMS')

@section('content')
    <p class="greeting">Halo,</p>

    <p class="message-text">
        Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda di sistem Dapentelkom DMS.
    </p>

    <div class="button-container">
        <a href="{{ $url }}" class="button">
            Reset Password
        </a>
    </div>

    <div class="info-box info-box-warning">
        <p class="info-label">Penting untuk Diketahui</p>
        <p class="info-value" style="margin: 5px 0 0 0;">
            Link reset password ini akan kadaluarsa dalam
            <strong>{{ config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') }} menit</strong>.
        </p>
    </div>

    <p class="message-text">
        Jika Anda tidak meminta reset password, abaikan email ini dan tidak ada perubahan yang akan dilakukan pada akun
        Anda.
    </p>

    <div class="divider"></div>

    <p class="message-text" style="font-size: 13px; color: #64748b;">
        Jika Anda mengalami kesulitan mengklik tombol "Reset Password", salin dan tempel URL berikut ke browser Anda:
    </p>
    <p style="font-size: 12px; color: #94a3b8; word-break: break-all; margin-top: 10px;">
        {{ $url }}
    </p>
@endsection
