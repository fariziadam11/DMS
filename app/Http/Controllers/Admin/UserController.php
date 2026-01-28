<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MasterDivisi;
use App\Models\MasterDepartment;
use App\Models\MasterJabatan;
use App\Models\BaseRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['divisi', 'department', 'jabatan', 'roles']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('id_divisi')) {
            $query->where('id_divisi', $request->id_divisi);
        }

        $users = $query->orderBy('name')->paginate(15);
        $divisions = MasterDivisi::orderBy('nama_divisi')->get();

        return view('admin.users.index', compact('users', 'divisions'));
    }

    public function create()
    {
        $divisions = MasterDivisi::orderBy('nama_divisi')->get();
        $roles = BaseRole::orderBy('roles_name')->get();

        return view('admin.users.create', compact('divisions', 'roles'));
    }

    public function getDepartments($divisiId)
    {
        // Since Divisi belongsTo Department, selecting a Divisi determines the Department.
        // We find the division and return its parent department.
        $divisi = MasterDivisi::with('department')->find($divisiId);

        if ($divisi && $divisi->department) {
            return response()->json([
                [
                    'id' => $divisi->department->id,
                    'nama_department' => $divisi->department->nama_department
                ]
            ]);
        }

        return response()->json([]);
    }

    public function getJabatans($divisiId)
    {
        $jabatans = MasterJabatan::where('id_divisi', $divisiId)
            ->orderBy('nama_jabatan')
            ->get(['id', 'nama_jabatan']);
        return response()->json($jabatans);
    }

    public function getDefaultRole($jabatanId)
    {
        $jabatan = MasterJabatan::find($jabatanId);
        if ($jabatan && $jabatan->id_role_default) {
            return response()->json(['role_id' => $jabatan->id_role_default]);
        }
        return response()->json(['role_id' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:125|unique:users,username',
            'nip' => 'required|string|max:20|unique:users,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'id_divisi' => 'nullable|exists:master_divisi,id',
            'id_department' => 'nullable|exists:master_department,id',
            'id_jabatan' => 'nullable|exists:master_jabatan,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:base_roles,id',
            'valid_from' => 'required|date',
            'valid_till' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $validTill = $request->has('is_permanent') ? null : $validated['valid_till'];

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'nip' => $validated['nip'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'id_divisi' => $validated['id_divisi'] ?? null,
            'id_department' => $validated['id_department'] ?? null,
            'id_jabatan' => $validated['id_jabatan'] ?? null,
            'valid_from' => $validated['valid_from'],
            'valid_till' => $validTill,
            'is_active' => 1,
        ]);

        // Sync Roles
        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        } else {
             // Fallback if no roles sent but Jabatan was selected (though JS should handle this)
            if (!empty($validated['id_jabatan'])) {
                $jabatan = MasterJabatan::find($validated['id_jabatan']);
                if ($jabatan && $jabatan->id_role_default) {
                     $user->roles()->sync([$jabatan->id_role_default]);
                }
            }
        }

        // Create User Profile
        $names = explode(' ', $validated['name'], 2);
        $firstName = $names[0];
        $lastName = $names[1] ?? '';

        $divisiName = null;
        if (!empty($validated['id_divisi'])) {
            $divisi = MasterDivisi::find($validated['id_divisi']);
            $divisiName = $divisi ? $divisi->nama_divisi : null;
        }

        \App\Models\UsersProfile::create([
            'id_user' => $user->id,
            'nip' => $validated['nip'],
            'first_name' => $firstName,
            'last_name' => $lastName,
            'divisi' => $divisiName,
            'department' => $validated['id_department'] ? MasterDepartment::find($validated['id_department'])->nama_department : null,
            'email' => $validated['email'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        $user->load(['divisi', 'roles', 'profile']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $divisions = MasterDivisi::orderBy('nama_divisi')->get();
        $roles = BaseRole::orderBy('roles_name')->get();
        // Load Depts and Jabatans based on current user selection for the view
        $departments = [];
        $jabatans = [];

        if ($user->id_divisi) {
             // Get parent department
             $divisi = MasterDivisi::with('department')->find($user->id_divisi);
             if ($divisi && $divisi->department) {
                 $departments = [$divisi->department];
             }
             // Get jabatans in division
             $jabatans = MasterJabatan::where('id_divisi', $user->id_divisi)->orderBy('nama_jabatan')->get();
        }

        $user->load('roles');

        return view('admin.users.edit', compact('user', 'divisions', 'roles', 'departments', 'jabatans'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:125|unique:users,username,' . $user->id,
            'nip' => 'required|string|max:20|unique:users,nip,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'id_divisi' => 'nullable|exists:master_divisi,id',
            'id_department' => 'nullable|exists:master_department,id',
            'id_jabatan' => 'nullable|exists:master_jabatan,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:base_roles,id',
            'valid_from' => 'required|date',
            'valid_till' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $validTill = $request->has('is_permanent') ? null : $validated['valid_till'];

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'nip' => $validated['nip'],
            'email' => $validated['email'],
            'id_divisi' => $validated['id_divisi'] ?? null,
            'id_department' => $validated['id_department'] ?? null,
            'id_jabatan' => $validated['id_jabatan'] ?? null,
            'valid_from' => $validated['valid_from'],
            'valid_till' => $validTill,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Sync Roles
        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        } else {
            // Check if we should auto-assign based on Jabatan ONLY if no roles were provided AND it's a fresh assignment?
            // Or if the user explicitly deselected all roles?
            // Safer to just sync empty array if 'roles' field is present but empty, but Request->validate won't pass 'roles' if it's missing from input.
            // However, usually detailed UI sends 'roles' as empty array if cleared.
            // Let's rely on manual selection. If user changes Jabatan, the UI should suggest the role.
            $user->roles()->sync([]);
        }

        // Note: The previous logic of "Auto assign role from Jabatan if changed" is now dangerous because it might overwrite manual roles.
        // We will delegate the "suggestion" to the Frontend (JS) which will select the role in the Select2 dropdown.
        // Backend simply saves what is submitted.

        // Update Profile
        if ($user->profile) {
            $user->profile->update([
                'nip' => $validated['nip'],
            ]);

            if (!empty($validated['id_divisi'])) {
                $divisi = MasterDivisi::find($validated['id_divisi']);
                $user->profile->update(['divisi' => $divisi ? $divisi->nama_divisi : null]);
            }
             if (!empty($validated['id_department'])) {
                $dept = MasterDepartment::find($validated['id_department']);
                $user->profile->update(['department' => $dept ? $dept->nama_department : null]);
            }
        }

        // Clear menu cache for the user so they see changes immediately
        \App\Services\MenuService::clearCache($user->id);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
