<!-- Header -->
<header class="main-header">
    <div class="header-left">
        <button class="btn btn-link d-lg-none" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
    </div>

    <div class="header-right">
        <div class="header-search">
            <i class="bi bi-search"></i>
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="q" class="form-control" placeholder="Cari dokumen..."
                    value="{{ request('q') }}">
            </form>
        </div>

        <div class="dropdown notification-dropdown">
            <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>
                @if (isset($recentActivities) && $recentActivities->count() > 0)
                    <span class="badge bg-danger rounded-pill">{{ $recentActivities->count() }}</span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-0 shadow"
                style="width: 320px; max-height: 400px; overflow-y: auto;">
                <li class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="mb-0 fw-bold">Aktivitas Terbaru</h6>
                    <small class="text-muted">Tracking</small>
                </li>
                @if (isset($recentActivities) && $recentActivities->count() > 0)
                    @foreach ($recentActivities as $activity)
                        <li>
                            <a class="dropdown-item p-3 border-bottom position-relative" href="#">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-sm bg-light text-primary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;">
                                            @if ($activity->action == 'create')
                                                <i class="bi bi-plus-lg"></i>
                                            @elseif($activity->action == 'update')
                                                <i class="bi bi-pencil"></i>
                                            @elseif($activity->action == 'delete')
                                                <i class="bi bi-trash"></i>
                                            @elseif($activity->action == 'download')
                                                <i class="bi bi-download"></i>
                                            @elseif($activity->action == 'view')
                                                <i class="bi bi-eye"></i>
                                            @elseif($activity->action == 'login')
                                                <i class="bi bi-person-circle"></i>
                                            @elseif($activity->action == 'logout')
                                                <i class="bi bi-box-arrow-right"></i>
                                            @elseif($activity->action == 'request_access')
                                                <i class="bi bi-key"></i>
                                            @elseif($activity->action == 'approve')
                                                <i class="bi bi-check-lg text-success"></i>
                                            @elseif($activity->action == 'reject')
                                                <i class="bi bi-x-lg text-danger"></i>
                                            @else
                                                <i class="bi bi-circle"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 small fw-bold">
                                            {{ $activity->user_id == auth()->id() ? 'Anda' : $activity->user->name ?? 'System' }}
                                        </h6>
                                        <p class="mb-1 small text-muted text-wrap">
                                            {{ ucfirst($activity->action) }}
                                            <span
                                                class="fw-medium text-dark">{{ str_replace('_', ' ', $activity->table_name) }}</span>
                                        </p>
                                        <small class="text-xs text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @else
                    <li class="p-4 text-center text-muted">
                        <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
                        Belum ada aktivitas
                    </li>
                @endif
                <li>
                    <a class="dropdown-item text-center small p-2 fw-medium text-primary bg-light" href="#">
                        Lihat Semua Aktivitas
                    </a>
                </li>
            </ul>
        </div>

        <div class="dropdown user-dropdown">
            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="d-none d-md-block text-start">
                    <div class="fw-medium" style="font-size: 0.875rem;">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                        {{ auth()->user()->divisi->nama_divisi ?? (auth()->user()->divisi ?? 'Division') }}</div>

                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                            class="bi bi-person me-2"></i>Profil</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- Flash Messages -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
