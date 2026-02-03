<!-- Dynamic Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('favicon.png') }}" alt="Logo" width="32" height="32">
            <div>
                <h5 class="mb-0">DMS Dapentel</h5>
                <small>Document Management System</small>
            </div>
        </div>
    </div>

    <div class="sidebar-search">
        <form action="{{ route('search') }}" method="GET">
            <input type="text" name="q" placeholder="Cari dokumen..." value="{{ request('q') }}">
        </form>
    </div>

    <nav class="sidebar-nav">
        @foreach ($menuSections ?? [] as $section)
            @if (isset($section['section']))
                <div class="nav-section">{{ $section['section'] }}</div>
            @endif

            @foreach ($section['items'] ?? [] as $item)
                @php
                    $hasChildren = isset($item['children']) && count($item['children']) > 0;
                    $isActive = isset($item['route']) && request()->routeIs($item['route'] . '*');
                    $childActive = false;

                    if ($hasChildren) {
                        foreach ($item['children'] as $child) {
                            if (
                                isset($child['route']) &&
                                request()->routeIs(str_replace('.index', '', $child['route']) . '*')
                            ) {
                                $childActive = true;
                                break;
                            }
                        }
                    }
                @endphp

                @if ($hasChildren)
                    <div class="nav-item has-submenu {{ $childActive ? 'open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="bi {{ $item['icon'] ?? 'bi-folder' }}"></i>
                            <span>{{ $item['name'] }}</span>
                        </a>
                        <div class="submenu">
                            @foreach ($item['children'] as $child)
                                @php
                                    $childUrl = '#';
                                    $childIsActive = false;

                                    if (isset($child['route'])) {
                                        try {
                                            $childUrl = route($child['route']);
                                            $childIsActive = request()->routeIs(
                                                str_replace('.index', '', $child['route']) . '*',
                                            );
                                        } catch (\Exception $e) {
                                            $childUrl = '#';
                                        }
                                    }
                                @endphp
                                <a href="{{ $childUrl }}" class="nav-link {{ $childIsActive ? 'active' : '' }}">
                                    {{ $child['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    @php
                        $itemUrl = '#';
                        if (isset($item['route'])) {
                            try {
                                $itemUrl = route($item['route']);
                            } catch (\Exception $e) {
                                $itemUrl = $item['url'] ?? '#';
                            }
                        }
                    @endphp
                    <div class="nav-item">
                        <a href="{{ $itemUrl }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                            <i class="bi {{ $item['icon'] ?? 'bi-circle' }}"></i>
                            <span>{{ $item['name'] }}</span>
                            @if (isset($item['badge']) && $item['badge'] == 'pending_access' && $pendingAccessCount > 0)
                                <span class="badge bg-danger">{{ $pendingAccessCount }}</span>
                            @endif
                        </a>
                    </div>
                @endif
            @endforeach
        @endforeach
    </nav>
</div>
