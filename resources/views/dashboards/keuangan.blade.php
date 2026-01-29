@php
    $title = 'Dashboard Keuangan';
    $subtitle = 'Overview dokumen modul Keuangan';
    $quickActions = [
        ['url' => route('keuangan.surat-bayar.index'), 'icon' => 'fas fa-money-bill', 'label' => 'Surat Bayar'],
        ['url' => route('keuangan.spb.index'), 'icon' => 'fas fa-receipt', 'label' => 'SPB'],
        ['url' => route('keuangan.sppb.index'), 'icon' => 'fas fa-file-invoice', 'label' => 'SPPB'],
        ['url' => route('keuangan.cashflow.index'), 'icon' => 'fas fa-coins', 'label' => 'Cashflow'],
        ['url' => route('keuangan.penempatan.index'), 'icon' => 'fas fa-landmark', 'label' => 'Penempatan'],
        ['url' => route('keuangan.pemindahbukuan.index'), 'icon' => 'fas fa-exchange-alt', 'label' => 'Pemindahbukuan'],
        ['url' => route('keuangan.pajak.index'), 'icon' => 'fas fa-percentage', 'label' => 'Pajak'],
    ];
@endphp

@include('dashboards.base')
