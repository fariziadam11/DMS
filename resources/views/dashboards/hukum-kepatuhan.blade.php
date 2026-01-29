@php
    $title = 'Dashboard Hukum & Kepatuhan';
    $subtitle = 'Overview dokumen modul Hukum & Kepatuhan';
    $quickActions = [
        ['url' => route('hukum-kepatuhan.kajian-hukum.index'), 'icon' => 'fas fa-gavel', 'label' => 'Kajian Hukum'],
        ['url' => route('hukum-kepatuhan.legal-memo.index'), 'icon' => 'fas fa-file-contract', 'label' => 'Legal Memo'],
        [
            'url' => route('hukum-kepatuhan.regulasi-internal.index'),
            'icon' => 'fas fa-book-open',
            'label' => 'Regulasi Internal',
        ],
        [
            'url' => route('hukum-kepatuhan.regulasi-external.index'),
            'icon' => 'fas fa-globe',
            'label' => 'Regulasi External',
        ],
        ['url' => route('hukum-kepatuhan.kontrak.index'), 'icon' => 'fas fa-handshake', 'label' => 'Kontrak'],
        ['url' => route('hukum-kepatuhan.putusan.index'), 'icon' => 'fas fa-balance-scale', 'label' => 'Putusan'],
    ];
@endphp

@include('dashboards.base')
