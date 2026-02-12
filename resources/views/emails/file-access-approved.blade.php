@extends('emails.layouts.email-layout')

@section('title', 'Permintaan Akses File Disetujui - Dapentelkom DMS')

@section('content')
    <p class="greeting">Halo {{ $requester->name }},</p>

    <p class="message-text">
        Kabar baik! Permintaan akses file Anda telah <strong>disetujui</strong>.
    </p>

    <div class="info-box info-box-success">
        <p class="info-label">Status Persetujuan</p>
        <p class="info-value" style="margin: 5px 0 0 0; color: #059669; font-weight: 600; font-size: 16px;">
            ✓ Disetujui
        </p>
    </div>

    <div class="info-box">
        <p class="info-label">Detail Akses</p>
        <table style="width: 100%; margin-top: 10px;">
            @if ($accessRequest->permissions && is_array($accessRequest->permissions))
                <tr>
                    <td style="padding: 5px 0; color: #64748b; width: 150px;">Hak Akses</td>
                    <td style="padding: 5px 0; color: #1e293b;">
                        @foreach ($accessRequest->permissions as $permission)
                            <span
                                style="display: inline-block; background-color: #dbeafe; color: #1e40af; padding: 3px 10px; border-radius: 4px; margin: 2px; font-size: 13px;">
                                {{ ucfirst($permission) }}
                            </span>
                        @endforeach
                    </td>
                </tr>
            @endif

            @if ($accessRequest->valid_till)
                <tr>
                    <td style="padding: 5px 0; color: #64748b;">Berlaku Hingga</td>
                    <td style="padding: 5px 0; color: #1e293b;">
                        {{ \Carbon\Carbon::parse($accessRequest->valid_till)->format('d F Y') }}</td>
                </tr>
            @endif

            @if ($accessRequest->download_limit)
                <tr>
                    <td style="padding: 5px 0; color: #64748b;">Batas Download</td>
                    <td style="padding: 5px 0; color: #1e293b;">{{ $accessRequest->download_limit }} kali</td>
                </tr>
            @endif

            <tr>
                <td style="padding: 5px 0; color: #64748b;">Disetujui Oleh</td>
                <td style="padding: 5px 0; color: #1e293b;">{{ $accessRequest->responder->name ?? '-' }}</td>
            </tr>

            @if ($accessRequest->response_reason)
                <tr>
                    <td style="padding: 5px 0; color: #64748b; vertical-align: top;">Catatan</td>
                    <td style="padding: 5px 0; color: #1e293b;">{{ $accessRequest->response_reason }}</td>
                </tr>
            @endif
        </table>
    </div>

    <p class="message-text">
        Anda sekarang dapat mengakses file yang diminta sesuai dengan hak akses yang diberikan.
    </p>

    <div class="button-container">
        <a href="{{ url('/') }}" class="button button-secondary">
            Akses Sistem
        </a>
    </div>

    <div class="divider"></div>

    <p class="message-text" style="font-size: 13px; color: #64748b;">
        Jika Anda memiliki pertanyaan tentang akses ini, silakan hubungi administrator sistem.
    </p>
@endsection
