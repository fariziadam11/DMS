@extends('layouts.app')
@section('title', 'Manajemen Menu')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active">Menu</li>
@endsection
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Manajemen Menu</h1>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Menu</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari menu..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-5">
                    <select name="parent_id" class="form-select">
                        <option value="">Menu Utama (Parent)</option>
                        @foreach ($parentMenus as $p)
                            <option value="{{ $p->id }}" {{ request('parent_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->menu_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="60">Order</th>
                        <th>Icon</th>
                        <th>Nama Menu</th>
                        <th>Parent</th>
                        <th>Route / Path</th>

                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $menu->sequence }}</span></td>
                            <td><i class="bi {{ $menu->icon ?? 'bi-folder' }} fs-5"></i></td>
                            <td>
                                <strong>{{ $menu->menu_name }}</strong>
                                @if ($menu->children->count() > 0)
                                    <br><small class="text-muted">{{ $menu->children->count() }} sub-menu</small>
                                @endif
                            </td>
                            <td>{{ $menu->parent?->menu_name ?? '-' }}</td>
                            <td><code>{{ $menu->code_name ?? ($menu->path ?? '-') }}</code></td>

                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-outline-warning"><i
                                            class="bi bi-pencil"></i> Edit</a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin hapus menu ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada menu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($menus->hasPages())
            <div class="card-footer">{{ $menus->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection
