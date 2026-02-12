-- Add Tag Management Menu to base_menus under Master Data parent
-- This script adds the "Manajemen Tag" menu item as a child of "Master Data"

INSERT INTO base_menus (
    id_group,
    id_module,
    parent_id,
    parent_sub_id,
    parent_sub_parent_id,
    code_name,
    menu_name,
    menu_label,
    sequence,
    path,
    icon,
    icon_type,
    created_at,
    updated_at
)
VALUES (
    0,
    0,
    (SELECT id FROM (SELECT id FROM base_menus WHERE code_name = 'master' LIMIT 1) AS tmp),
    NULL,
    NULL,
    'master.tags',
    'Tag',
    'Manajemen Tag',
    4,
    '/tags',
    'bi-tags',
    NULL,
    NOW(),
    NOW()
);

-- Verify the insertion
SELECT id, code_name, menu_label, path, sequence
FROM base_menus
WHERE code_name = 'master.tags';
