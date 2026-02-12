<?php

namespace App\Http\Controllers;

use App\Models\FileAccessRequest;
use App\Models\FolderPermission;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List access requests for admin
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = FileAccessRequest::with(['requester', 'divisi', 'responder']);

        // Filter by user's managed divisions
        if (!$user->isSuperAdmin()) {
            $managedDivisions = $user->roles()
                ->where('access_scope', 'division')
                ->pluck('id_divisi')
                ->toArray();

            // Also include user's own division (for View access)
            if ($user->id_divisi) {
                $managedDivisions[] = $user->id_divisi;
            }

            $managedDivisions = array_unique($managedDivisions);

            $query->whereIn('id_divisi', $managedDivisions);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('access.index', compact('requests'));
    }

    /**
     * Approve access request
     */
    public function approve(Request $request, $id)
    {
        $accessRequest = FileAccessRequest::findOrFail($id);

        // Check if user can approve
        $this->authorizeManage($accessRequest);

        $permissions = $request->input('permissions', []);
        $validTill = $request->input('valid_till');
        $downloadLimit = $request->input('download_limit');

        $accessRequest->update([
            'status' => FileAccessRequest::STATUS_APPROVED,
            'permissions' => $permissions,
            'responded_by' => auth()->id(),
            'responded_at' => now(),
            'response_reason' => $request->get('reason'),
            'valid_till' => $validTill ?: null,
            'download_limit' => $downloadLimit ?: null,
            'download_count' => 0,
        ]);

        \App\Models\AuditLog::log(
            'approve',
            'file_access_requests',
            $accessRequest->id,
            null,
            ['status' => 'approved', 'permissions' => $permissions]
        );

        // Send email notification to requester
        $accessRequest->requester->notify(new \App\Notifications\FileAccessApprovedNotification($accessRequest));

        return back()->with('success', 'Permintaan akses disetujui.');
    }

    /**
     * Reject access request
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $accessRequest = FileAccessRequest::findOrFail($id);

        // Check if user can reject
        $this->authorizeManage($accessRequest);

        $accessRequest->reject(auth()->id(), $validated['reason']);

        \App\Models\AuditLog::log(
            'reject',
            'file_access_requests',
            $accessRequest->id,
            null,
            ['status' => 'rejected', 'reason' => $validated['reason']]
        );

        // Send email notification to requester
        $accessRequest->requester->notify(new \App\Notifications\FileAccessRejectedNotification($accessRequest));

        return back()->with('success', 'Permintaan akses ditolak.');
    }

    /**
     * Assign file access to user
     */
    public function assignAccess(Request $request)
    {
        $validated = $request->validate([
            'id_folder' => 'required|exists:document_folders,id',
            'id_user' => 'required|exists:users,id',
            'permission_type' => 'required|in:read,write,delete,full',
        ]);

        $validated['created_by'] = auth()->id();

        FolderPermission::updateOrCreate(
            ['id_folder' => $validated['id_folder'], 'id_user' => $validated['id_user']],
            ['permission_type' => $validated['permission_type'], 'created_by' => $validated['created_by']]
        );

        return back()->with('success', 'Akses berhasil diberikan.');
    }

    /**
     * Remove file access from user
     */
    public function removeAccess($id)
    {
        $permission = FolderPermission::findOrFail($id);
        $permission->delete();

        return back()->with('success', 'Akses berhasil dicabut.');
    }

    /**
     * Check if current user can manage this request
     */
    protected function authorizeManage($accessRequest)
    {
        $user = auth()->user();

        // 1. Prevent Self-Approval
        if ($accessRequest->id_user === $user->id) {
            abort(403, 'Anda tidak dapat menyetujui atau menolak permintaan Anda sendiri.');
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        // 2. Check specific "Approval" privilege
        $menu = \App\Models\BaseMenu::where('code_name', 'access.index')->first();
        $approvalFunc = \App\Models\BaseFunction::where('function_name', 'Approval')->first();

        if ($menu && $approvalFunc) {
            if (!$user->hasMenuFunction($menu->id, $approvalFunc->id)) {
                abort(403, 'Anda tidak memiliki hak akses "Approval" untuk menu ini.');
            }
        }

        // 3. Check Division Scope (Must handle requests within their managed scope)
        $canManage = $user->roles()
            ->where('id_divisi', $accessRequest->id_divisi)
            ->where('access_scope', 'division')
            ->exists();

        // Also allow if user has global scope (though covered by isSuperAdmin usually,
        // sometimes global roles aren't "Super Admin" named)
        if (!$canManage) {
            $hasGlobal = $user->roles()->where('access_scope', 'global')->exists();
            if ($hasGlobal) {
                $canManage = true;
            }
        }

        if (!$canManage) {
            abort(403, 'Anda tidak berhak mengelola permintaan ini (Bukan area divisi Anda).');
        }

        return true;
    }
}
