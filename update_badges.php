<?php

$directory = __DIR__ . '/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

$count = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $originalContent = $content;

        // Pattern 1: One-liner
        // @if($item->sifat_dokumen == 'Rahasia')<span class="badge badge-secret"><i class="bi bi-lock"></i></span>@else<span class="badge badge-public"><i class="bi bi-unlock"></i></span>@endif
        $pattern1 = '/@if\s*\(\$([a-zA-Z0-9_]+)->sifat_dokumen\s*==\s*[\'"]Rahasia[\'"]\)\s*<span class="badge badge-secret"><i class="bi bi-lock"><\/i><\/span>\s*@else\s*<span class="badge badge-public"><i class="bi bi-unlock"><\/i><\/span>\s*@endif/';

        $replacement1 = '@if($$1->sifat_dokumen == \'Rahasia\')<span class="badge badge-secret"><i class="bi bi-lock"></i></span>@elseif($$1->sifat_dokumen == \'Internal\')<span class="badge bg-warning text-dark"><i class="bi bi-shield-lock"></i></span>@else<span class="badge badge-public"><i class="bi bi-unlock"></i></span>@endif';

        $content = preg_replace($pattern1, $replacement1, $content);

        // Pattern 2: Multiline (approximate)
        // We match loosely with dot matches all for whitespace
        $pattern2 = '/@if\s*\(\$([a-zA-Z0-9_]+)->sifat_dokumen\s*==\s*[\'"]Rahasia[\'"]\)\s+<span class="badge badge-secret"><i class="bi bi-lock"><\/i><\/span>\s+@else\s+<span class="badge badge-public"><i class="bi bi-unlock"><\/i><\/span>\s+@endif/s';

        // We need to capture indentation if possible, but let's just use a standard multiline replacement
        $content = preg_replace_callback($pattern2, function($matches) {
            $var = $matches[1]; // doc or item
            return "@if (\${$var}->sifat_dokumen == 'Rahasia')
                                        <span class=" . '"badge badge-secret"' . "><i class=" . '"bi bi-lock"' . "></i></span>
                                    @elseif (\${$var}->sifat_dokumen == 'Internal')
                                        <span class=" . '"badge bg-warning text-dark"' . "><i class=" . '"bi bi-shield-lock"' . "></i></span>
                                    @else
                                        <span class=" . '"badge badge-public"' . "><i class=" . '"bi bi-unlock"' . "></i></span>
                                    @endif";
        }, $content);

        if ($content !== $originalContent) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
            $count++;
        }
    }
}

echo "Total updated files: $count\n";
