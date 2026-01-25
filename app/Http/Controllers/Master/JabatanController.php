<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasterJabatan;
use App\Models\MasterDivisi;
use App\Models\MasterDepartment;
use App\Models\BaseRole;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = MasterJabatan::with(['divisi', 'department', 'defaultRole']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_jabatan', 'like', "%{$search}%")
                    ->orWhere('nama_jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('id_divisi')) {
            $query->where('id_divisi', $request->id_divisi);
        }

        $data = $query->orderBy('level_jabatan')->orderBy('nama_jabatan')->paginate(15);
        $divisions = MasterDivisi::orderBy('nama_divisi')->get();
        $routePrefix = 'master.jabatan';
        $moduleName = 'Master Jabatan';
        $permissions = $this->getPermissions();

        return view('master.jabatan.index', compact('data', 'divisions', 'routePrefix', 'moduleName', 'permissions'));
    }

    public function create()
    {
        $divisions = MasterDivisi::orderBy('nama_divisi')->get();
        $departments = MasterDepartment::orderBy('nama_department')->get();
        $roles = BaseRole::orderBy('roles_name')->get();
        $routePrefix = 'master.jabatan';
        $moduleName = 'Master Jabatan';

        return view('master.jabatan.create', compact('divisions', 'departments', 'roles', 'routePrefix', 'moduleName'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_jabatan' => 'required|string|max:20|unique:master_jabatan,kode_jabatan',
            'nama_jabatan' => 'required|string|max:100',
            'id_divisi' => 'nullable|exists:master_divisi,id',
            'id_department' => 'nullable|exists:master_department,id',
            'id_role_default' => 'nullable|exists:base_roles,id',
            'level_jabatan' => 'nullable|integer|min:1|max:4',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        MasterJabatan::create($validated);

        return redirect()->route('master.jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $record = MasterJabatan::findOrFail($id);
        $divisions = MasterDivisi::orderBy('nama_divisi')->get();
        $departments = MasterDepartment::orderBy('nama_department')->get();
        $roles = BaseRole::orderBy('roles_name')->get();
        $routePrefix = 'master.jabatan';
        $moduleName = 'Master Jabatan';

        return view('master.jabatan.edit', compact('record', 'divisions', 'departments', 'roles', 'routePrefix', 'moduleName'));
    }

    public function update(Request $request, $id)
    {
        $jabatan = MasterJabatan::findOrFail($id);

        $validated = $request->validate([
            'kode_jabatan' => 'required|string|max:20|unique:master_jabatan,kode_jabatan,' . $id,
            'nama_jabatan' => 'required|string|max:100',
            'id_divisi' => 'nullable|exists:master_divisi,id',
            'id_department' => 'nullable|exists:master_department,id',
            'id_role_default' => 'nullable|exists:base_roles,id',
            'level_jabatan' => 'nullable|integer|min:1|max:4',
            'description' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();
        $jabatan->update($validated);

        return redirect()->route('master.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jabatan = MasterJabatan::findOrFail($id);
        $jabatan->deleted_by = auth()->id();
        $jabatan->save();
        $jabatan->delete();

        return redirect()->route('master.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }

    public function show($id)
    {
        $record = MasterJabatan::with(['divisi', 'department', 'defaultRole'])->findOrFail($id);
        $routePrefix = 'master.jabatan';
        $moduleName = 'Master Jabatan';
        $permissions = $this->getPermissions();
        return view('master.jabatan.show', compact('record', 'routePrefix', 'moduleName', 'permissions'));
    }

    /**
     * Get permission flags
     */
    protected function getPermissions()
    {
        $user = auth()->user();
        if (!$user) {
            return ['create' => false, 'edit' => false, 'delete' => false];
        }

        if ($user->isSuperAdmin()) {
            return ['create' => true, 'edit' => true, 'delete' => true];
        }

        // Simple RBAC check based on routes
        // This is simplified, ideally we check against menu ID like BaseDocumentController
        // But for quick fix on Master data:
        // Use a safe default for Division Admins on Master? Usually Master is IT/Admin only.

        // Let's try to find menu by code
        $menu = \App\Models\BaseMenu::where('code_name', 'master.jabatan')->first();
        if (!$menu) {
             // Fallback
             return ['create' => true, 'edit' => true, 'delete' => true];
        }

        return [
            'create' => $user->hasMenuFunction($menu->id, 2),
            'edit' => $user->hasMenuFunction($menu->id, 3),
            'delete' => $user->hasMenuFunction($menu->id, 4),
        ];
    }

    /**
     * Get departments by division (AJAX)
     */
    public function getDepartmentsByDivision($divisiId)
    {
        $departments = MasterDepartment::where('id_divisi', $divisiId)
            ->orderBy('nama_department')
            ->get(['id', 'nama_department']);

        return response()->json($departments);
    }
}
