<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaseRole;
use App\Models\BaseMenu;
use App\Models\BasePrivilege;
use App\Models\BaseFunction;
use App\Models\MasterDivisi;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = BaseRole::with(['divisi', 'group']);

        if ($request->filled('search')) {
            $query->where('roles_name', 'like', '%' . $request->search . '%');
        }

        $roles = $query->paginate(20);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $divisions = MasterDivisi::orderBy('nama_divisi')->get();

        return view('admin.roles.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'roles_name' => 'required|string|max:50',
            'id_divisi' => 'nullable|exists:master_divisi,id',
            'access_scope' => 'required|in:global,division,department',
        ]);

        BaseRole::create($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan');
    }

    public function edit(BaseRole $role)
    {
        $divisions = MasterDivisi::orderBy('nama_divisi')->get();

        return view('admin.roles.edit', [
            'role' => $role,
            'divisions' => $divisions,
        ]);
    }

    public function update(Request $request, BaseRole $role)
    {
        $validated = $request->validate([
            'roles_name' => 'required|string|max:50',
            'id_divisi' => 'nullable|exists:master_divisi,id',
            'access_scope' => 'required|in:global,division,department',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui');
    }

    public function destroy(BaseRole $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus role yang masih memiliki user');
        }

        // Delete privileges first
        $role->privileges()->delete();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus');
    }

    /**
     * Show role-menu assignment page
     */
    public function privileges(BaseRole $role)
    {
        $menus = BaseMenu::with('children')
            ->whereNull('parent_id')
            ->orderBy('sequence')
            ->get();

        $functions = BaseFunction::all();

        // Get current privileges for this role
        $currentPrivileges = BasePrivilege::where('id_roles', $role->id)
            ->get()
            ->groupBy('id_menu');

        // Get allowed functions per menu (if constrained)
        $allowedFunctions = \App\Models\BaseMenuFunction::all()
            ->groupBy('id_menu')
            ->map(function ($items) {
                return $items->pluck('id_function')->toArray();
            });

        return view('admin.roles.privileges', [
            'role' => $role,
            'menus' => $menus,
            'functions' => $functions,
            'currentPrivileges' => $currentPrivileges,
            'allowedFunctions' => $allowedFunctions,
        ]);
    }

    /**
     * Update role privileges
     */
    public function updatePrivileges(Request $request, BaseRole $role)
    {
        $menuIds = $request->input('menus', []);
        $functionIds = $request->input('functions', []);

        // Delete existing privileges
        BasePrivilege::where('id_roles', $role->id)->delete();

        // Create new privileges
        foreach ($menuIds as $menuId) {
            // Get functions for this menu
            $menuFunctions = $functionIds[$menuId] ?? [];

            if (empty($menuFunctions)) {
                // Just add menu access without specific functions
                BasePrivilege::create([
                    'id_roles' => $role->id,
                    'id_menu' => $menuId,
                    'id_function' => null,
                ]);
            } else {
                // Add privilege for each function
                foreach ($menuFunctions as $funcId) {
                    BasePrivilege::create([
                        'id_roles' => $role->id,
                        'id_menu' => $menuId,
                        'id_function' => $funcId,
                    ]);
                }
            }
        }

        // Clear menu cache
        \App\Services\MenuService::clearCache();

        return redirect()->route('admin.roles.privileges', $role)
            ->with('success', 'Privileges berhasil diperbarui');
    }
}
