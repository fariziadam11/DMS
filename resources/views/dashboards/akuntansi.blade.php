@php
    $title = 'Dashboard Akuntansi';
    $subtitle = 'Overview dokumen modul Akuntansi';
    $quickActions = [
        [
            'url' => route('akuntansi.aturan-kebijakan.index'),
            'icon' => 'fas fa-file-alt',
            'label' => 'Aturan Kebijakan',
        ],
        ['url' => route('akuntansi.jurnal-umum.index'), 'icon' => 'fas fa-book', 'label' => 'Jurnal Umum'],
        [
            'url' => route('akuntansi.laporan-audit-investasi.index'),
            'icon' => 'fas fa-chart-line',
            'label' => 'Audit Investasi',
        ],
        [
            'url' => route('akuntansi.laporan-audit-keuangan.index'),
            'icon' => 'fas fa-money-check-alt',
            'label' => 'Audit Keuangan',
        ],
        [
            'url' => route('akuntansi.laporan-bulanan.index'),
            'icon' => 'fas fa-calendar-alt',
            'label' => 'Laporan Bulanan',
        ],
    ];
@endphp

@include('dashboards.base')
