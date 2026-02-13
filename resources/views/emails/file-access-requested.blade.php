@extends('emails.layouts.email-layout')

@section('title', 'Permintaan Akses File Baru - Dapentelkom DMS')

@section('content')
    <p class="greeting">Halo {{ $approver->name }},</p>

    <p class="message-text">
        Anda menerima email ini karena ada permintaan akses file baru yang memerlukan persetujuan Anda.
    </p>

    <div class="info-box">
        <p class="info-label">Detail Pemohon</p>
        <table style="width: 100%; margin-top: 10px;">
            <tr>
                <td style="padding: 5px 0; color: #64748b; width: 120px;">Nama</td>
                <td style="padding: 5px 0; color: #1e293b; font-weight: 600;">{{ $accessRequest->requester->name }}</td>
            </tr>
            <tr>
                <td style="padding: 5px 0; color: #64748b;">Divisi</td>
                <td style="padding: 5px 0; color: #1e293b;">{{ $accessRequest->divisi?->nama_divisi ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px 0; color: #64748b;">Email</td>
                <td style="padding: 5px 0; color: #1e293b;">{{ $accessRequest->requester->email }}</td>
            </tr>
        </table>
    </div>

    <div class="info-box info-box-warning">
        <p class="info-label">Alasan Permintaan</p>
        <p class="info-value" style="margin: 5px 0 0 0;">
            {{ $accessRequest->request_reason ?? 'Tidak ada alasan yang diberikan' }}
        </p>
    </div>

    <p class="message-text">
        Silakan tinjau permintaan ini dan berikan keputusan Anda.
    </p>

    <div class="button-container">
        <a href="{{ route('access.index') }}" class="button">
            Tinjau Permintaan
        </a>
    </div>

    <div class="divider"></div>

    <p class="message-text" style="font-size: 13px; color: #64748b;">
        Permintaan ini dibuat pada {{ $accessRequest->created_at->format('d F Y, H:i') }} WIB.
    </p>
@endsection
