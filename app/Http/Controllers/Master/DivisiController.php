<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MasterDivisi;
use App\Models\MasterDepartment;
use App\Models\BaseModule;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = MasterDivisi::with(['module', 'department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_divisi', 'like', "%{$search}%")
                    ->orWhere('nama_divisi', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('kode_divisi')->paginate(15);
        $routePrefix = 'master.divisi';
        $moduleName = 'Master Divisi';

        $permissions = $this->getPermissions();

        return view('master.divisi.index', compact('data', 'routePrefix', 'moduleName', 'permissions'));
    }

    public function create()
    {
        $modules = BaseModule::all();
        $departments = MasterDepartment::orderBy('nama_department')->get();
        $routePrefix = 'master.divisi';
        $moduleName = 'Master Divisi';
        return view('master.divisi.create', compact('modules', 'departments', 'routePrefix', 'moduleName'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_divisi' => 'required|string|max:20|unique:master_divisi,kode_divisi',
            'nama_divisi' => 'required|string|max:100',
            'id_department' => 'nullable|exists:master_department,id',
            'id_module' => 'nullable|exists:base_modules,id',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        MasterDivisi::create($validated);

        return redirect()->route('master.divisi.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $record = MasterDivisi::with(['module', 'department', 'jabatans', 'roles'])->findOrFail($id);
        $routePrefix = 'master.divisi';
        $moduleName = 'Master Divisi';
        $permissions = $this->getPermissions();
        return view('master.divisi.show', compact('record', 'routePrefix', 'moduleName', 'permissions'));
    }

    public function edit($id)
    {
        $record = MasterDivisi::findOrFail($id);
        $modules = BaseModule::all();
        $departments = MasterDepartment::orderBy('nama_department')->get();
        $routePrefix = 'master.divisi';
        $moduleName = 'Master Divisi';
        return view('master.divisi.edit', compact('record', 'modules', 'departments', 'routePrefix', 'moduleName'));
    }

    public function update(Request $request, $id)
    {
        $divisi = MasterDivisi::findOrFail($id);

        $validated = $request->validate([
            'kode_divisi' => 'required|string|max:20|unique:master_divisi,kode_divisi,' . $id,
            'nama_divisi' => 'required|string|max:100',
            'id_department' => 'nullable|exists:master_department,id',
            'id_module' => 'nullable|exists:base_modules,id',
            'description' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();
        $divisi->update($validated);

        return redirect()->route('master.divisi.index')
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $divisi = MasterDivisi::findOrFail($id);
        $divisi->deleted_by = auth()->id();
        $divisi->save();
        $divisi->delete();

        return redirect()->route('master.divisi.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }

    protected function getPermissions()
    {
        $user = auth()->user();
        if (!$user) {
            return ['create' => false, 'edit' => false, 'delete' => false];
        }

        if ($user->isSuperAdmin()) {
            return ['create' => true, 'edit' => true, 'delete' => true];
        }

        $menu = \App\Models\BaseMenu::where('code_name', 'master.divisi')->first();
        if (!$menu) {
            return ['create' => true, 'edit' => true, 'delete' => true];
        }

        return [
            'create' => $user->hasMenuFunction($menu->id, 2),
            'edit' => $user->hasMenuFunction($menu->id, 3),
            'delete' => $user->hasMenuFunction($menu->id, 4),
        ];
    }
}
