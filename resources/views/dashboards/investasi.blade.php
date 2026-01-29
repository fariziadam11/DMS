@php
    $title = 'Dashboard Investasi';
    $subtitle = 'Overview dokumen modul Investasi';
    $quickActions = [
        ['url' => route('investasi.transaksi.index'), 'icon' => 'fas fa-exchange-alt', 'label' => 'Transaksi'],
        ['url' => route('investasi.surat.index'), 'icon' => 'fas fa-envelope', 'label' => 'Surat'],
        [
            'url' => route('investasi.perencanaan-transaksi.index'),
            'icon' => 'fas fa-clipboard-list',
            'label' => 'Perencanaan Transaksi',
        ],
        [
            'url' => route('investasi.perencanaan-surat.index'),
            'icon' => 'fas fa-file-signature',
            'label' => 'Perencanaan Surat',
        ],
        [
            'url' => route('investasi.propensa-transaksi.index'),
            'icon' => 'fas fa-chart-line',
            'label' => 'Propensa Transaksi',
        ],
        ['url' => route('investasi.propensa-surat.index'), 'icon' => 'fas fa-file-alt', 'label' => 'Propensa Surat'],
    ];
@endphp

@include('dashboards.base')
