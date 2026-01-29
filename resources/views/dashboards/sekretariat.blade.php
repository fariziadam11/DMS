@php
    $title = 'Dashboard Sekretariat';
    $subtitle = 'Overview dokumen modul Sekretariat';
    $quickActions = [
        ['url' => route('sekretariat.risalah-rapat.index'), 'icon' => 'fas fa-users', 'label' => 'Risalah Rapat'],
        ['url' => route('sekretariat.materi.index'), 'icon' => 'fas fa-file-powerpoint', 'label' => 'Materi'],
        ['url' => route('sekretariat.laporan.index'), 'icon' => 'fas fa-chart-bar', 'label' => 'Laporan'],
        ['url' => route('sekretariat.surat.index'), 'icon' => 'fas fa-envelope', 'label' => 'Surat'],
        ['url' => route('sekretariat.pengadaan.index'), 'icon' => 'fas fa-shopping-cart', 'label' => 'Pengadaan'],
        [
            'url' => route('sekretariat.remunerasi-pedoman.index'),
            'icon' => 'fas fa-book-open',
            'label' => 'Remunerasi Pedoman',
        ],
        [
            'url' => route('sekretariat.remunerasi-dokumen.index'),
            'icon' => 'fas fa-file-invoice-dollar',
            'label' => 'Remunerasi Dokumen',
        ],
    ];
@endphp

@include('dashboards.base')
