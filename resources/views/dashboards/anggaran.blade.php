@php
    $title = 'Dashboard Anggaran';
    $subtitle = 'Overview dokumen modul Anggaran';
    $quickActions = [
        ['url' => route('anggaran.aturan-kebijakan.index'), 'icon' => 'fas fa-file-alt', 'label' => 'Aturan Kebijakan'],
        ['url' => route('anggaran.dokumen-rra.index'), 'icon' => 'fas fa-folder', 'label' => 'Dokumen RRA'],
        ['url' => route('anggaran.laporan-prbc.index'), 'icon' => 'fas fa-chart-bar', 'label' => 'Laporan PRBC'],
        [
            'url' => route('anggaran.rencana-kerja-direktorat.index'),
            'icon' => 'fas fa-briefcase',
            'label' => 'RK Direktorat',
        ],
        [
            'url' => route('anggaran.rencana-kerja-tahunan.index'),
            'icon' => 'fas fa-calendar-check',
            'label' => 'RK Tahunan',
        ],
        [
            'url' => route('anggaran.rencana-kerja-triwulan.index'),
            'icon' => 'fas fa-calendar',
            'label' => 'RK Triwulan',
        ],
    ];
@endphp

@include('dashboards.base')
