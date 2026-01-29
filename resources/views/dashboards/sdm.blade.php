@php
    $title = 'Dashboard SDM';
    $subtitle = 'Overview dokumen modul Sumber Daya Manusia';
    $quickActions = [
        ['url' => route('sdm.pks.index'), 'icon' => 'fas fa-handshake', 'label' => 'PKS'],
        ['url' => route('sdm.rarus.index'), 'icon' => 'fas fa-file-alt', 'label' => 'RARUS'],
        ['url' => route('sdm.peraturan.index'), 'icon' => 'fas fa-book', 'label' => 'Peraturan'],
        ['url' => route('sdm.rekrut-masuk.index'), 'icon' => 'fas fa-user-plus', 'label' => 'Rekrutmen'],
        ['url' => route('sdm.promosi-mutasi.index'), 'icon' => 'fas fa-user-tie', 'label' => 'Promosi & Mutasi'],
        ['url' => route('sdm.naik-gaji.index'), 'icon' => 'fas fa-dollar-sign', 'label' => 'Kenaikan Gaji'],
        ['url' => route('sdm.surat-masuk.index'), 'icon' => 'fas fa-inbox', 'label' => 'Surat Masuk'],
        ['url' => route('sdm.surat-keluar.index'), 'icon' => 'fas fa-paper-plane', 'label' => 'Surat Keluar'],
    ];
@endphp

@include('dashboards.base')
