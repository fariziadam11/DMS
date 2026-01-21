@extends('layouts.app')
@section('title', 'Atur Privileges - ' . $role->roles_name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Role</a></li>
    <li class="breadcrumb-item active">Privileges</li>
@endsection

@push('styles')
    <style>
        .menu-tree {
            list-style: none;
            padding-left: 0;
        }

        .menu-tree .privilege-submenu {
            list-style: none;
            padding-left: 30px;
            margin-top: 10px;
            display: block;
            /* Ensure visible */
        }

        .menu-item {
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .menu-item.has-children {
            background: #e9ecef;
        }

        .menu-item .form-check {
            margin: 0;
        }

        .privilege-submenu .menu-item {
            background: #fff;
            border: 1px solid #dee2e6;
        }

        .function-badges {
            margin-left: 30px;
            margin-top: 10px;
            padding: 10px;
            background: #f0f8ff;
            border-left: 3px solid #007bff;
        }

        .function-badges .form-check-inline {
            margin-right: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Atur Privileges</h1>
            <p class="text-muted mb-0">Role: <strong>{{ $role->roles_name }}</strong></p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
            Kembali</a>
    </div>

    <form action="{{ route('admin.roles.privileges.update', $role) }}" method="POST">
        @csrf @method('PUT')

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Pilih Menu yang Dapat Diakses</span>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">Pilih Semua</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">Hapus Semua</button>
                </div>
            </div>
            <div class="card-body">
                {{-- Debug info --}}
                <ul class="menu-tree">
                    @foreach ($menus as $menu)
                        <li class="menu-item {{ $menu->children->count() > 0 ? 'has-children' : '' }}">
                            <div class="d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input parent-menu" type="checkbox" name="menus[]"
                                        value="{{ $menu->id }}" id="menu{{ $menu->id }}"
                                        {{ isset($currentPrivileges[$menu->id]) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="menu{{ $menu->id }}">
                                        <i class="bi {{ $menu->icon ?? 'bi-folder' }} me-2"></i>
                                        <strong>{{ $menu->menu_name }}</strong>
                                    </label>
                                </div>
                            </div>

                            {{-- FUNCTION CHECKBOXES FOR PARENT --}}
                            @if ($functions->count() > 0)
                                <div class="function-badges mb-2">
                                    <div class="text-primary mb-2">
                                        <small><strong>Functions:</strong></small>
                                    </div>
                                    @foreach ($functions as $func)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox"
                                                name="functions[{{ $menu->id }}][]" value="{{ $func->id }}"
                                                id="func{{ $menu->id }}_{{ $func->id }}"
                                                {{ isset($currentPrivileges[$menu->id]) && $currentPrivileges[$menu->id]->contains('id_function', $func->id) ? 'checked' : '' }}>
                                            <label class="form-check-label small"
                                                for="func{{ $menu->id }}_{{ $func->id }}">
                                                {{ $func->function_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if ($menu->children->count() > 0)
                                <ul class="privilege-submenu">
                                    @foreach ($menu->children as $child)
                                        <li class="menu-item">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input child-menu" type="checkbox"
                                                        name="menus[]" value="{{ $child->id }}"
                                                        id="menu{{ $child->id }}" data-parent="{{ $menu->id }}"
                                                        {{ isset($currentPrivileges[$child->id]) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="menu{{ $child->id }}">
                                                        {{ $child->menu_name }}
                                                    </label>
                                                </div>
                                            </div>

                                            {{-- FUNCTION CHECKBOXES FOR CHILD --}}
                                            @if ($functions->count() > 0)
                                                <div class="function-badges mb-2">
                                                    <div class="text-primary mb-2">
                                                        <small><strong>Functions:</strong></small>
                                                    </div>
                                                    @foreach ($functions as $func)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="functions[{{ $child->id }}][]"
                                                                value="{{ $func->id }}"
                                                                id="func{{ $child->id }}_{{ $func->id }}"
                                                                {{ isset($currentPrivileges[$child->id]) && $currentPrivileges[$child->id]->contains('id_function', $func->id) ? 'checked' : '' }}>
                                                            <label class="form-check-label small"
                                                                for="func{{ $child->id }}_{{ $func->id }}">
                                                                {{ $func->function_name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>

                @if ($menus->count() == 0)
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-folder-x fs-1"></i>
                        <p>Belum ada menu. <a href="{{ route('admin.menus.create') }}">Tambah menu</a></p>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
                Kembali</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Privileges</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.getElementById('selectAll').addEventListener('click', function() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
        });

        document.getElementById('deselectAll').addEventListener('click', function() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        });

        // When parent is checked, check all children
        document.querySelectorAll('.parent-menu').forEach(parent => {
            parent.addEventListener('change', function() {
                const parentId = this.value;
                document.querySelectorAll(`.child-menu[data-parent="${parentId}"]`).forEach(child => {
                    child.checked = this.checked;
                });
            });
        });

        // When child is checked, also check parent
        document.querySelectorAll('.child-menu').forEach(child => {
            child.addEventListener('change', function() {
                if (this.checked) {
                    const parentId = this.dataset.parent;
                    document.getElementById('menu' + parentId).checked = true;
                }
            });
        });
    </script>
@endpush
