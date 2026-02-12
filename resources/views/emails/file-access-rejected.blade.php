@extends('emails.layouts.email-layout')

@section('title', 'Permintaan Akses File Ditolak - Dapentelkom DMS')

@section('content')
    <p class="greeting">Halo {{ $requester->name }},</p>

    <p class="message-text">
        Kami informasikan bahwa permintaan akses file Anda telah ditinjau.
    </p>

    <div class="info-box info-box-danger">
        <p class="info-label">Status Persetujuan</p>
        <p class="info-value" style="margin: 5px 0 0 0; color: #dc2626; font-weight: 600; font-size: 16px;">
            ✗ Ditolak
        </p>
    </div>

    @if ($accessRequest->response_reason)
        <div class="info-box">
            <p class="info-label">Alasan Penolakan</p>
            <p class="info-value" style="margin: 5px 0 0 0;">
                {{ $accessRequest->response_reason }}
            </p>
        </div>
    @endif

    <div class="info-box">
        <p class="info-label">Detail Permintaan</p>
        <table style="width: 100%; margin-top: 10px;">
            <tr>
                <td style="padding: 5px 0; color: #64748b; width: 150px;">Tanggal Permintaan</td>
                <td style="padding: 5px 0; color: #1e293b;">{{ $accessRequest->created_at->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td style="padding: 5px 0; color: #64748b;">Ditolak Oleh</td>
                <td style="padding: 5px 0; color: #1e293b;">{{ $accessRequest->responder->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px 0; color: #64748b;">Tanggal Penolakan</td>
                <td style="padding: 5px 0; color: #1e293b;">{{ $accessRequest->responded_at->format('d F Y, H:i') }} WIB
                </td>
            </tr>
        </table>
    </div>

    <p class="message-text">
        Jika Anda merasa penolakan ini tidak sesuai atau memiliki pertanyaan lebih lanjut, silakan hubungi administrator
        sistem atau atasan Anda untuk klarifikasi.
    </p>

    <div class="button-container">
        <a href="mailto:support@dapentelkom.co.id" class="button">
            Hubungi Support
        </a>
    </div>

    <div class="divider"></div>

    <p class="message-text" style="font-size: 13px; color: #64748b;">
        Anda dapat mengajukan permintaan akses baru jika diperlukan dengan alasan yang lebih jelas.
    </p>
@endsection
