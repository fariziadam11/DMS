@php
    $title = 'Dashboard Logistik & Sarpen';
    $subtitle = 'Overview dokumen modul Logistik & Sarana Penunjang';
    $quickActions = [
        ['url' => route('logistik.procurement.index'), 'icon' => 'fas fa-shopping-bag', 'label' => 'Procurement'],
        ['url' => route('logistik.cleaning-service.index'), 'icon' => 'fas fa-broom', 'label' => 'Cleaning Service'],
        ['url' => route('logistik.keamanan.index'), 'icon' => 'fas fa-shield-alt', 'label' => 'Keamanan'],
        ['url' => route('logistik.kendaraan.index'), 'icon' => 'fas fa-car', 'label' => 'Kendaraan'],
        ['url' => route('logistik.sarana-penunjang.index'), 'icon' => 'fas fa-tools', 'label' => 'Sarana Penunjang'],
        ['url' => route('logistik.smk3.index'), 'icon' => 'fas fa-hard-hat', 'label' => 'SMK3'],
        [
            'url' => route('logistik.polis-asuransi.index'),
            'icon' => 'fas fa-file-contract',
            'label' => 'Polis Asuransi',
        ],
        ['url' => route('logistik.jaminan.index'), 'icon' => 'fas fa-certificate', 'label' => 'Jaminan'],
    ];
@endphp

@include('dashboards.base')
