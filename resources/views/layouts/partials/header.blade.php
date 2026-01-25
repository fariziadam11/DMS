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

        <button class="notification-btn">
            <i class="bi bi-bell"></i>
            <span class="badge bg-danger rounded-pill">3</span>
        </button>

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
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
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
