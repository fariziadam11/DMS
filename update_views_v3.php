<?php

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
$regex = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$count = 0;
foreach ($regex as $file) {
    $path = $file[0];

    // We only want to target "show" views or partials potentially used in show
    // My previous regex was `show.blade.php`.
    // Let's filter by filename ending in `show.blade.php` OR `_show.blade.php` OR just check content
    // but risk false positives in index/edit.
    // The user specifically asked for "view show".
    // Let's stick to files containing "show" in name for safety, or strict check.
    if (!str_contains($path, 'show.blade.php')) {
        continue;
    }

    $content = file_get_contents($path);
    $originalContent = $content;

    // 1. Back Button with $routePrefix
    // Pattern: <a href="{{ route($routePrefix . '.index') }}" ...
    $backPatternDynamic = '/<a href="\{\{ route\(\$routePrefix \. \'\.index\'\) \}\}" class="([^"]+)">((?:<i class="[^"]+"><\/i>\s*)?Kembali)<\/a>/i';

    if (preg_match($backPatternDynamic, $content) && !str_contains($content, 'request(\'source\')')) {
        $content = preg_replace_callback($backPatternDynamic, function($matches) {
            $class = $matches[1]; // e.g. btn btn-outline-secondary
            $text = $matches[2]; // e.g. <i...> Kembali

            // Standardize style if possible, or keep original
            return '@if (request(\'source\') == \'my-documents\')' . "\n" .
                   '                    <a href="{{ route(\'my-documents.index\') }}" class="' . $class . '"><i' . "\n" .
                   '                            class="bi bi-arrow-left"></i> Kembali ke Dokumen Saya</a>' . "\n" .
                   '                @else' . "\n" .
                   '                    $0' . "\n" .
                   '                @endif';
        }, $content);
    }

    // 2. Edit Button with $routePrefix
    // <a href="{{ route($routePrefix . '.edit', $item->id) }}" ...
    $editPatternDynamic = '/<a href="\{\{ route\(\$routePrefix \. \'\.edit\', \$(?:record|item)->id\) \}\}" class="([^"]+)">((?:<i class="[^"]+"><\/i>\s*)?Edit)<\/a>/i';

    if (preg_match($editPatternDynamic, $content) && !str_contains($content, 'permissions[\'edit\']')) {
        $content = preg_replace($editPatternDynamic,
            '@if ($permissions[\'edit\'])' . "\n" .
            '                        $0' . "\n" .
            '                    @endif',
            $content
        );
    }

    // 3. Delete Button with $routePrefix
    // <form action="{{ route($routePrefix . '.destroy', $item->id) }}" ...
    $deletePatternDynamic = '/<form action="\{\{ route\(\$routePrefix \. \'\.destroy\', \$(?:record|item)->id\) \}\}" method="POST"\s+class="d-inline">@csrf @method\(\'DELETE\'\)<button.*?Hapus<\/button><\/form>/s';

    if (preg_match($deletePatternDynamic, $content) && !str_contains($content, 'permissions[\'delete\']')) {
        $content = preg_replace($deletePatternDynamic,
            '@if ($permissions[\'delete\'])' . "\n" .
            '                        $0' . "\n" .
            '                    @endif',
            $content
        );
    }

    if ($content !== $originalContent) {
        file_put_contents($path, $content);
        echo "Updated: $path\n";
        $count++;
    }
}

echo "Total files updated: $count\n";
