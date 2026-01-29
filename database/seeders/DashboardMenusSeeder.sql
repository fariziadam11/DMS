-- SQL Seeder untuk menambahkan menu Dashboard per Modul ke base_menus
-- Jalankan query ini setelah implementasi selesai

-- Dashboard Akuntansi
INSERT INTO base_menus (id_group, id_module, parent_id, parent_sub_id, parent_sub_parent_id, code_name, menu_name, menu_label, sequence, path, icon, icon_type, created_at, updated_at)
VALUES (0, 0,
    (SELECT id FROM (SELECT id FROM base_menus WHERE code_name = 'akuntansi' LIMIT 1) AS tmp),
    NULL, NULL, 'dashboard.akuntansi', 'Dashboard Akuntansi', 'Dashboard', 0, '/dashboard/akuntansi', 'bi-pie-chart', NULL, NOW(), NOW());

-- Dashboard Anggaran
INSERT INTO base_menus (id_group, id_module, parent_id, parent_sub_id, parent_sub_parent_id, code_name, menu_name, menu_label, sequence, path, icon, icon_type, created_at, updated_at)
VALUES (0, 0,
    (SELECT id FROM (SELECT id FROM base_menus WHERE code_name = 'anggaran' LIMIT 1) AS tmp),
    NULL, NULL, 'dashboard.anggaran', 'Dashboard Anggaran', 'Dashboard', 0, '/dashboard/anggaran', 'bi-pie-chart', NULL, NOW(), NOW());

-- Dashboard Hukum & Kepatuhan
INSERT INTO base_menus (id_group, id_module, parent_id, parent_sub_id, parent_sub_parent_id, code_name, menu_name, menu_label, sequence, path, icon, icon_type, created_at, updated_at)
VALUES (0, 0,
    (SELECT id FROM (SELECT id FROM base_menus WHERE code_name = 'hukum-kepatuhan' LIMIT 1) AS tmp),
    NULL, NULL, 'dashboard.hukum-kepatuhan', 'Dashboard Hukum & Kepatuhan', 'Dashboard', 0, '/dashboard/hukum-kepatuhan', 'bi-pie-chart', NULL, NOW(), NOW());

-- Dashboard Investasi
INSERT INTO base_menus (id_group, id_module, parent_id, parent_sub_id, parent_sub_parent_id, code_name, menu_name, menu_label, sequence, path, icon, icon_type, created_at, updated_at)
VALUES (0, 0,
    (SELECT id FROM (SELECT id FROM base_menus WHERE code_name = 'investasi' LIMIT 1) AS tmp),
    NULL, NULL, 'dashboard.investasi', 'Dashboard Investasi', 'Dashboard', 0, '/dashboard/investasi', 'bi-pie-chart', NULL, NOW(), NOW());

-- Dashboard Keuangan
INSERT INTO base_menus (id_group, id_module, parent_id, parent_sub_id, parent_sub_parent_id, code_name, menu_name, menu_label, sequence, path, icon, icon_type, created_at, updated_at)
VALUES (0, 0,
    (SELECT id FROM (SELECT id FROM base_menus WHERE code_name = 'keuangan' LIMIT 1) AS tmp),
    NULL, NULL, 'dashboard.keuangan', 'Dashboard Keuangan', 'Dashboard', 0, '/dashboard/keuangan', 'bi-pie-chart', NULL, NOW(), NOW());

-- Dashboard SDM
INSERT INTO base_menus (id_group, id_module, parent_id, parent_sub_id, parent_sub_parent_id, code_name, menu_name, menu_label, sequence, path, icon, icon_type, created_at, updated_at)
VALUES (0, 0,
    (SELECT id FROM (SELECT id FROM base_menus WHERE code_name = 'sdm' LIMIT 1) AS tmp),
    NULL, NULL, 'dashboard.sdm', 'Dashboard SDM', 'Dashboard', 0, '/dashboard/sdm', 'bi-pie-chart', NULL, NOW(), NOW());

-- Dashboard Sekretariat
INSERT INTO base_menus (id_group, id_module, parent_id, parent_sub_id, parent_sub_parent_id, code_name, menu_name, menu_label, sequence, path, icon, icon_type, created_at, updated_at)
VALUES (0, 0,
    (SELECT id FROM (SELECT id FROM base_menus WHERE code_name = 'sekretariat' LIMIT 1) AS tmp),
    NULL, NULL, 'dashboard.sekretariat', 'Dashboard Sekretariat', 'Dashboard', 0, '/dashboard/sekretariat', 'bi-pie-chart', NULL, NOW(), NOW());

-- Dashboard Logistik
INSERT INTO base_menus (id_group, id_module, parent_id, parent_sub_id, parent_sub_parent_id, code_name, menu_name, menu_label, sequence, path, icon, icon_type, created_at, updated_at)
VALUES (0, 0,
    (SELECT id FROM (SELECT id FROM base_menus WHERE code_name = 'logistik' LIMIT 1) AS tmp),
    NULL, NULL, 'dashboard.logistik', 'Dashboard Logistik', 'Dashboard', 0, '/dashboard/logistik', 'bi-pie-chart', NULL, NOW(), NOW());

-- Assign view privilege untuk Super Admin (role_id = 1)
-- Sesuaikan role_id jika berbeda
/*
INSERT INTO base_privileges (role_id, menu_id, function_id, created_at, updated_at)
SELECT 1, id, (SELECT id FROM base_functions WHERE name = 'view' LIMIT 1), NOW(), NOW()
FROM base_menus
WHERE code_name LIKE 'dashboard.%'
AND NOT EXISTS (
    SELECT 1 FROM base_privileges
    WHERE role_id = 1 AND menu_id = base_menus.id
);
*/
