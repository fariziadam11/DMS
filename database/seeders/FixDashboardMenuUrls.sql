-- Quick Fix: Update Dashboard Menu URLs
-- Run this if dashboard menu URLs are showing as '#'

UPDATE base_menus SET path = '/dashboard/akuntansi' WHERE code_name = 'dashboard.akuntansi';
UPDATE base_menus SET path = '/dashboard/anggaran' WHERE code_name = 'dashboard.anggaran';
UPDATE base_menus SET path = '/dashboard/hukum-kepatuhan' WHERE code_name = 'dashboard.hukum-kepatuhan';
UPDATE base_menus SET path = '/dashboard/investasi' WHERE code_name = 'dashboard.investasi';
UPDATE base_menus SET path = '/dashboard/keuangan' WHERE code_name = 'dashboard.keuangan';
UPDATE base_menus SET path = '/dashboard/sdm' WHERE code_name = 'dashboard.sdm';
UPDATE base_menus SET path = '/dashboard/sekretariat' WHERE code_name = 'dashboard.sekretariat';
UPDATE base_menus SET path = '/dashboard/logistik' WHERE code_name = 'dashboard.logistik';

-- Verify the update
SELECT id, code_name, menu_label, path FROM base_menus WHERE code_name LIKE 'dashboard.%';
