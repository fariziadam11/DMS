@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>{{ $title }}</h1>
            <p>{{ $subtitle }}</p>
        </div>

        <!-- Quick Actions -->
        @if (isset($quickActions) && count($quickActions) > 0)
            <div class="quick-actions">
                @foreach ($quickActions as $action)
                    <a href="{{ $action['url'] }}" class="quick-action-btn">
                        <i class="{{ $action['icon'] }}"></i>
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Total Dokumen</span>
                    <div class="stat-card-icon icon-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ number_format($stats['total_documents']) }}</div>
                <div class="stat-card-footer">Semua dokumen dalam modul</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Dokumen Bulan Ini</span>
                    <div class="stat-card-icon icon-success">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ number_format($stats['documents_this_month']) }}</div>
                <div class="stat-card-footer">{{ date('F Y') }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Dokumen Umum</span>
                    <div class="stat-card-icon icon-info">
                        <i class="fas fa-globe"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ number_format($stats['by_sifat']['Umum']) }}</div>
                <div class="stat-card-footer">Dapat diakses semua</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Dokumen Rahasia</span>
                    <div class="stat-card-icon icon-danger">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ number_format($stats['by_sifat']['Rahasia']) }}</div>
                <div class="stat-card-footer">Perlu approval akses</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <!-- Documents per Sub-Module -->
            <div class="chart-card">
                <h3 class="chart-card-title">Dokumen per Sub-Modul</h3>
                <div class="chart-container">
                    <canvas id="subModuleChart"></canvas>
                </div>
            </div>

            <!-- Documents by Sifat -->
            <div class="chart-card">
                <h3 class="chart-card-title">Distribusi Sifat Dokumen</h3>
                <div class="chart-container">
                    <canvas id="sifatChart"></canvas>
                </div>
            </div>

            <!-- Monthly Trend -->
            <div class="chart-card" style="grid-column: 1 / -1;">
                <h3 class="chart-card-title">Trend Upload Dokumen (6 Bulan Terakhir)</h3>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="recent-documents">
            <div class="recent-documents-header">
                <h3 class="recent-documents-title">Dokumen Terbaru</h3>
            </div>

            @if (count($stats['recent_documents']) > 0)
                <div class="table-responsive">
                    <table class="documents-table">
                        <thead>
                            <tr>
                                <th>Sub-Modul</th>
                                <th>Tanggal Upload</th>
                                <th>Terakhir Update</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stats['recent_documents'] as $doc)
                                @php
                                    $routeName = '#';
                                    $tablePrefixes = [
                                        'akuntansi_' => 'akuntansi.',
                                        'anggaran_' => 'anggaran.',
                                        'hukumkepatuhan_' => 'hukum-kepatuhan.',
                                        'investasi_' => 'investasi.',
                                        'keuangan_' => 'keuangan.',
                                        'logistiksarpen_' => 'logistik.',
                                        'sdm_' => 'sdm.',
                                        'sekretariat_' => 'sekretariat.',
                                    ];

                                    foreach ($tablePrefixes as $prefix => $routePrefix) {
                                        if (str_starts_with($doc->table_name, $prefix)) {
                                            $subModule = substr($doc->table_name, strlen($prefix));
                                            $subModule = str_replace('_', '-', $subModule);
                                            try {
                                                $routeName = route($routePrefix . $subModule . '.preview', $doc->id);
                                            } catch (\Exception $e) {
                                                $routeName = '#';
                                            }
                                            break;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $doc->sub_module }}</td>
                                    <td>{{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($doc->updated_at)->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ $routeName }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada dokumen</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Documents per Sub-Module Chart
        const subModuleCtx = document.getElementById('subModuleChart').getContext('2d');
        new Chart(subModuleCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($stats['per_submodule'], 'label')) !!},
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: {!! json_encode(array_column($stats['per_submodule'], 'count')) !!},
                    backgroundColor: 'rgba(25, 118, 210, 0.7)',
                    borderColor: 'rgba(25, 118, 210, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Sifat Dokumen Pie Chart
        const sifatCtx = document.getElementById('sifatChart').getContext('2d');
        new Chart(sifatCtx, {
            type: 'doughnut',
            data: {
                labels: ['Umum', 'Internal', 'Rahasia'],
                datasets: [{
                    data: [
                        {{ $stats['by_sifat']['Umum'] }},
                        {{ $stats['by_sifat']['Internal'] }},
                        {{ $stats['by_sifat']['Rahasia'] }}
                    ],
                    backgroundColor: [
                        'rgba(25, 118, 210, 0.7)',
                        'rgba(245, 124, 0, 0.7)',
                        'rgba(211, 47, 47, 0.7)'
                    ],
                    borderColor: [
                        'rgba(25, 118, 210, 1)',
                        'rgba(245, 124, 0, 1)',
                        'rgba(211, 47, 47, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Trend Line Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($stats['monthly_trend']['labels']) !!},
                datasets: [{
                    label: 'Dokumen Diupload',
                    data: {!! json_encode($stats['monthly_trend']['data']) !!},
                    borderColor: 'rgba(25, 118, 210, 1)',
                    backgroundColor: 'rgba(25, 118, 210, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endpush
