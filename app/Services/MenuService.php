<?php

namespace App\Services;

use App\Models\BaseMenu;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    /**
     * Get menu tree for the authenticated user
     */
    public static function getMenuForUser(?User $user = null): array
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return [];
        }

        // Cache menu for performance (5 minutes)
        $cacheKey = "user_menu_{$user->id}";

        return Cache::remember($cacheKey, 300, function () use ($user) {
            return self::buildMenuTree($user);
        });
    }

    /**
     * Build hierarchical menu tree
     */
    protected static function buildMenuTree(User $user): array
    {
        // Super admin gets all menus
        if ($user->isSuperAdmin()) {
            $menus = BaseMenu::query()
                ->whereNull('parent_id')
                ->orderBy('sequence')
                ->with(['children' => function ($q) {
                    $q->orderBy('sequence');
                }])
                ->get();
        } else {
            // Get menu IDs user has access to
            $allowedMenuIds = $user->getAllowedMenuIds();

            $menus = BaseMenu::whereNull('parent_id')
                ->where(function ($q) use ($allowedMenuIds) {
                    $q->whereIn('id', $allowedMenuIds)
                        ->orWhereHas('children', function ($sub) use ($allowedMenuIds) {
                            $sub->whereIn('id', $allowedMenuIds);
                        });
                })
                ->orderBy('sequence')
                ->with(['children' => function ($q) use ($allowedMenuIds) {
                    $q->whereIn('id', $allowedMenuIds)
                        ->orderBy('sequence');
                }])
                ->get();
        }

        return self::formatMenuItems($menus);
    }

    /**
     * Format menu items for view
     */
    protected static function formatMenuItems($menus): array
    {
        $items = [];

        foreach ($menus as $menu) {
            $route = $menu->code_name;

            // Special case for access document to avoid matching overlapping routes like access.my-requests
            if ($route === 'access') {
                $route = 'access.index';
            }
            if ($route === 'my-request') {
                $route = 'my-request.index';
            }

            // Append .index for resource routes (heuristic: contains dot)
            // Exception: dashboard routes don't need .index
            if (strpos($route, '.') !== false &&
                !str_ends_with($route, '.index') &&
                !str_starts_with($route, 'dashboard.')) {
                $route .= '.index';
            }

            $item = [
                'id' => $menu->id,
                'name' => $menu->menu_name,
                'icon' => $menu->icon ?? 'bi-folder',
                'url' => $menu->path,
                'route' => $route,
                // 'section' => $menu->section_name, // Not used
                'children' => [],
            ];

            if ($menu->children && $menu->children->count() > 0) {
                foreach ($menu->children as $child) {
                     $childRoute = $child->code_name;
                     // Exception: dashboard routes and access.my-requests don't need .index
                     if (strpos($childRoute, '.') !== false &&
                         !str_ends_with($childRoute, '.index') &&
                         $childRoute !== 'access.my-requests' &&
                         !str_starts_with($childRoute, 'dashboard.')) {
                        $childRoute .= '.index';
                    }

                    $item['children'][] = [
                        'id' => $child->id,
                        'name' => $child->menu_name,
                        'url' => $child->path,
                        'route' => $childRoute,
                    ];
                }
            }

            $items[] = $item;
        }

        // Sidebar expects sections. Wrap all in one default section or split if needed.
        // For now, simple wrapping.
        return [
            [
                'section' => 'Main Menu',
                'items' => $items
            ]
        ];
    }

    /**
     * Clear menu cache for user
     */
    public static function clearCache(?int $userId = null): void
    {
        if ($userId) {
            Cache::forget("user_menu_{$userId}");
        } else {
            // Clear all user menu cache
            Cache::flush();
        }
    }

    /**
     * Get static menu (fallback when database menus not configured)
     */
    public static function getStaticMenu(): array
    {
        return [
            [
                'section' => 'Menu Utama',
                'items' => [
                    ['name' => 'Dashboard', 'icon' => 'bi-grid-1x2', 'route' => 'dashboard', 'url' => '/dashboard'],
                ]
            ],
            [
                'section' => 'Modul',
                'items' => [
                    [
                        'name' => 'Akuntansi',
                        'icon' => 'bi-file-earmark-spreadsheet',
                        'url' => '#',
                        'children' => [
                            ['name' => 'Aturan Kebijakan', 'route' => 'akuntansi.aturan-kebijakan.index'],
                            ['name' => 'Jurnal Umum', 'route' => 'akuntansi.jurnal-umum.index'],
                            ['name' => 'Laporan Audit Investasi', 'route' => 'akuntansi.laporan-audit-investasi.index'],
                            ['name' => 'Laporan Audit Keuangan', 'route' => 'akuntansi.laporan-audit-keuangan.index'],
                            ['name' => 'Laporan Bulanan', 'route' => 'akuntansi.laporan-bulanan.index'],
                        ]
                    ],
                    [
                        'name' => 'Anggaran',
                        'icon' => 'bi-currency-dollar',
                        'url' => '#',
                        'children' => [
                            ['name' => 'Aturan Kebijakan', 'route' => 'anggaran.aturan-kebijakan.index'],
                            ['name' => 'Dokumen RRA', 'route' => 'anggaran.dokumen-rra.index'],
                            ['name' => 'Laporan PRBC', 'route' => 'anggaran.laporan-prbc.index'],
                            ['name' => 'Rencana Kerja Direktorat', 'route' => 'anggaran.rencana-kerja-direktorat.index'],
                            ['name' => 'Rencana Kerja Tahunan', 'route' => 'anggaran.rencana-kerja-tahunan.index'],
                            ['name' => 'Rencana Kerja Triwulan', 'route' => 'anggaran.rencana-kerja-triwulan.index'],
                        ]
                    ],
                    [
                        'name' => 'Hukum & Kepatuhan',
                        'icon' => 'bi-shield-check',
                        'url' => '#',
                        'children' => [
                            ['name' => 'Kajian Hukum', 'route' => 'hukum-kepatuhan.kajian-hukum.index'],
                            ['name' => 'Legal Memo', 'route' => 'hukum-kepatuhan.legal-memo.index'],
                            ['name' => 'Kontrak', 'route' => 'hukum-kepatuhan.kontrak.index'],
                            ['name' => 'Putusan', 'route' => 'hukum-kepatuhan.putusan.index'],
                            ['name' => 'Regulasi Internal', 'route' => 'hukum-kepatuhan.regulasi-internal.index'],
                            ['name' => 'Regulasi External', 'route' => 'hukum-kepatuhan.regulasi-external.index'],
                            ['name' => 'Compliance Check', 'route' => 'hukum-kepatuhan.compliance-check.index'],
                            ['name' => 'Executive Summary', 'route' => 'hukum-kepatuhan.executive-summary.index'],
                            ['name' => 'Lembar Keputusan', 'route' => 'hukum-kepatuhan.lembar-keputusan.index'],
                            ['name' => 'Lembar Rekomendasi', 'route' => 'hukum-kepatuhan.lembar-rekomendasi.index'],
                            ['name' => 'Penomoran', 'route' => 'hukum-kepatuhan.penomoran.index'],
                        ]
                    ],
                    [
                        'name' => 'Investasi',
                        'icon' => 'bi-graph-up-arrow',
                        'url' => '#',
                        'children' => [
                            ['name' => 'Transaksi', 'route' => 'investasi.transaksi.index'],
                            ['name' => 'Surat', 'route' => 'investasi.surat.index'],
                            ['name' => 'Perencanaan Transaksi', 'route' => 'investasi.perencanaan-transaksi.index'],
                            ['name' => 'Perencanaan Surat', 'route' => 'investasi.perencanaan-surat.index'],
                            ['name' => 'Propensa Transaksi', 'route' => 'investasi.propensa-transaksi.index'],
                            ['name' => 'Propensa Surat', 'route' => 'investasi.propensa-surat.index'],
                        ]
                    ],
                    [
                        'name' => 'Keuangan',
                        'icon' => 'bi-wallet2',
                        'url' => '#',
                        'children' => [
                            ['name' => 'Surat Bayar', 'route' => 'keuangan.surat-bayar.index'],
                            ['name' => 'SPB', 'route' => 'keuangan.spb.index'],
                            ['name' => 'SPPB', 'route' => 'keuangan.sppb.index'],
                            ['name' => 'Cashflow', 'route' => 'keuangan.cashflow.index'],
                            ['name' => 'Penempatan', 'route' => 'keuangan.penempatan.index'],
                            ['name' => 'Pemindahbukuan', 'route' => 'keuangan.pemindahbukuan.index'],
                            ['name' => 'Pajak', 'route' => 'keuangan.pajak.index'],
                        ]
                    ],
                    [
                        'name' => 'SDM',
                        'icon' => 'bi-people',
                        'url' => '#',
                        'children' => [
                            ['name' => 'PKS', 'route' => 'sdm.pks.index'],
                            ['name' => 'Rarus', 'route' => 'sdm.rarus.index'],
                            ['name' => 'Peraturan', 'route' => 'sdm.peraturan.index'],
                            ['name' => 'Rekrut Masuk', 'route' => 'sdm.rekrut-masuk.index'],
                            ['name' => 'Capeg Pegrus', 'route' => 'sdm.capeg-pegrus.index'],
                            ['name' => 'Promosi Mutasi', 'route' => 'sdm.promosi-mutasi.index'],
                            ['name' => 'Naik Gaji', 'route' => 'sdm.naik-gaji.index'],
                            ['name' => 'Penghargaan', 'route' => 'sdm.penghargaan.index'],
                            ['name' => 'Ikut Organisasi', 'route' => 'sdm.ikut-organisasi.index'],
                            ['name' => 'Surat Masuk', 'route' => 'sdm.surat-masuk.index'],
                            ['name' => 'Surat Keluar', 'route' => 'sdm.surat-keluar.index'],
                            ['name' => 'Aspurjab', 'route' => 'sdm.aspurjab.index'],
                            ['name' => 'Rekon', 'route' => 'sdm.rekon.index'],
                        ]
                    ],
                    [
                        'name' => 'Sekretariat',
                        'icon' => 'bi-journal-text',
                        'url' => '#',
                        'children' => [
                            ['name' => 'Risalah Rapat', 'route' => 'sekretariat.risalah-rapat.index'],
                            ['name' => 'Materi', 'route' => 'sekretariat.materi.index'],
                            ['name' => 'Laporan', 'route' => 'sekretariat.laporan.index'],
                            ['name' => 'Surat', 'route' => 'sekretariat.surat.index'],
                            ['name' => 'Pengadaan', 'route' => 'sekretariat.pengadaan.index'],
                            ['name' => 'Remunerasi Pedoman', 'route' => 'sekretariat.remunerasi-pedoman.index'],
                            ['name' => 'Remunerasi Dokumen', 'route' => 'sekretariat.remunerasi-dokumen.index'],
                        ]
                    ],
                    [
                        'name' => 'Logistik & Sarpen',
                        'icon' => 'bi-box-seam',
                        'url' => '#',
                        'children' => [
                            ['name' => 'Procurement', 'route' => 'logistik.procurement.index'],
                            ['name' => 'Cleaning Service', 'route' => 'logistik.cleaning-service.index'],
                            ['name' => 'Keamanan', 'route' => 'logistik.keamanan.index'],
                            ['name' => 'Kendaraan', 'route' => 'logistik.kendaraan.index'],
                            ['name' => 'Sarana Penunjang', 'route' => 'logistik.sarana-penunjang.index'],
                            ['name' => 'Pelaporan PRBC', 'route' => 'logistik.pelaporan-prbc.index'],
                            ['name' => 'User Satisfaction', 'route' => 'logistik.user-satisfaction.index'],
                            ['name' => 'Vendor Satisfaction', 'route' => 'logistik.vendor-satisfaction.index'],
                            ['name' => 'SMK3', 'route' => 'logistik.smk3.index'],
                            ['name' => 'Polis Asuransi', 'route' => 'logistik.polis-asuransi.index'],
                            ['name' => 'Jaminan', 'route' => 'logistik.jaminan.index'],
                        ]
                    ],
                ]
            ],
            [
                'section' => 'Master Data',
                'admin_only' => true,
                'items' => [
                    [
                        'name' => 'Master Data',
                        'icon' => 'bi-database',
                        'url' => '#',
                        'children' => [
                            ['name' => 'Divisi', 'route' => 'master.divisi.index'],
                            ['name' => 'Department', 'route' => 'master.department.index'],
                            ['name' => 'Jabatan', 'route' => 'master.jabatan.index'],
                        ]
                    ],
                ]
            ],
            [
                'section' => 'Admin',
                'admin_only' => true,
                'items' => [
                    [
                        'name' => 'Manajemen Akses',
                        'icon' => 'bi-shield-lock',
                        'url' => '#',
                        'children' => [
                            ['name' => 'Menu', 'route' => 'admin.menus.index'],
                            ['name' => 'Role', 'route' => 'admin.roles.index'],
                            ['name' => 'User', 'route' => 'admin.users.index'],
                        ]
                    ],
                    [
                        'name' => 'Document Assignment',
                        'icon' => 'bi-folder-symlink',
                        'route' => 'admin.document-assignment.index',
                    ],
                ]
            ],
            [
                'section' => 'Manajemen',
                'items' => [
                    ['name' => 'Akses Dokumen', 'icon' => 'bi-clipboard-check', 'route' => 'access.index', 'badge' => 'pending_access'],
                    ['name' => 'Permintaan Saya', 'icon' => 'bi-file-earmark-lock', 'route' => 'my-request.index'],
                ]
            ],
        ];
    }
}
