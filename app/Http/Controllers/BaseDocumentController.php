<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class BaseDocumentController extends Controller
{
    protected $model;
    protected $viewPath;
    protected $routePrefix;
    protected $moduleName;
    protected $storagePath = 'documents';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->model::query();

        // Apply division filter if user is not super admin
        if (!auth()->user()->isSuperAdmin()) {
            $query->accessibleByUser(auth()->id());
        }

        // Apply search
        if ($request->filled('search')) {
            $query->globalSearch($request->search);
        }

        // Apply filters
        if ($request->filled('id_divisi')) {
            $query->byDivision($request->id_divisi);
        }
        if ($request->filled('sifat_dokumen')) {
            $query->byClassification($request->sifat_dokumen);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $items = $query->with('divisi')->paginate(10);

        return view($this->viewPath . '.index', [
            'items' => $items,
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'divisions' => $this->getUserDivisions(),
            'permissions' => $this->getPermissions(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkFunctionAccess(2); // Create

        return view($this->viewPath . '.create', [
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'divisions' => $this->getUserDivisions(),
            'permissions' => $this->getPermissions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkFunctionAccess(2); // Create

        // 1. Check for File Collision for Auto-Versioning
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $originalName = $uploadedFile->getClientOriginalName();

            // Find existing ACTIVE document with same filename
            $existing = $this->model::where('file_name', $originalName)->first();

            if ($existing) {
                // COLLISION DETECTED -> Trigger Version Update

                // Check Permission to Update THIS specific record
                // (Using 'write' policy which handles Owner/SuperAdmin/DivAdmin)
                $canUpdate = false;
                try {
                    // We assume authorizeAccess throws exception on failure, but it returns true on success.
                    // Actually authorizeAccess aborts (throws HttpException).
                    // So we catch it.
                    // However, we want to know IF we can update.
                    // Let's wrap in try-catch.
                    // We need a helper or just try permission logic.
                    // authorizeAccess returns true or aborts.
                    $this->authorizeAccess($existing, 'write');
                    $canUpdate = true;
                } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                    $canUpdate = false;
                }

                if ($canUpdate) {
                    // Proceed to Update Logic

                    // Validate Request (treating as Update with ID to ignore unique checks)
                    $validated = $this->validateRequest($request, $existing->id);

                    $oldValues = $existing->toArray();

                    // Handle File Upload
                    $validated['file'] = $this->handleFileUpload($uploadedFile);
                    $validated['file_name'] = $originalName;
                    $validated['version'] = ($existing->version ?? 1) + 1;

                    // Detect Change Notes
                    $notes = $request->get('change_notes', 'Pembaruan otomatis via upload ulang (Nama file sama)');

                    // Create New Version
                    $this->createDocumentVersion($existing, $uploadedFile, $notes);

                    // Update Record
                    $existing->update($validated);

                    // Audit Log (Update)
                    AuditLog::logUpdate($existing->getTable(), $existing->id, $oldValues, $validated);

                    // Redirect
                    return redirect()->route($this->routePrefix . '.index')
                        ->with('success', 'Dokumen terdeteksi! Database diperbarui sebagai Versi ' . $validated['version']);
                } else {
                    // Collision found but no permission to update
                    return back()->withInput()->with('error', 'Gagal: File dengan nama "' . $originalName . '" sudah ada (Divisi: ' . ($existing->divisi->nama_divisi ?? '-') . ') dan Anda tidak memiliki izin untuk memperbaruinya.');
                }
            }
        }

        // Standard Creation Logic (No Collision)
        $validated = $this->validateRequest($request);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $validated['file'] = $this->handleFileUpload($file);
            $validated['file_name'] = $file->getClientOriginalName();
        }

        $record = $this->model::create($validated);

        // Create initial version if file exists
        if (isset($validated['file'])) {
            $this->createDocumentVersion($record, $request->file('file'));
        }

        // Audit log
        AuditLog::logCreate($record->getTable(), $record->id, $validated);

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $record = $this->model::with(['divisi', 'versions', 'creator'])->findOrFail($id);

        // Check access
        $this->authorizeAccess($record);

        // Log view
        AuditLog::logView($record->getTable(), $record->id);

        // Check granular document permission to override RBAC if needed
        $permissions = $this->getPermissions();
        if ($record->userHasFileAccess(auth()->id())) {
             $permissions['download'] = true;
        }

        return view($this->viewPath . '.show', [
            'record' => $record,
            'item' => $record, // Alias for backward compatibility
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->checkFunctionAccess(3); // Edit

        $record = $this->model::findOrFail($id);
        $this->authorizeAccess($record, 'write');

        return view($this->viewPath . '.edit', [
            'record' => $record,
            'item' => $record, // Alias for backward compatibility
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'divisions' => $this->getUserDivisions(),
            'permissions' => $this->getPermissions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->checkFunctionAccess(3); // Edit

        $record = $this->model::findOrFail($id);
        $this->authorizeAccess($record, 'write');

        $oldValues = $record->toArray();
        $validated = $this->validateRequest($request, $id);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $validated['file'] = $this->handleFileUpload($file);
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['version'] = $record->version + 1;

            // Create new version
            $this->createDocumentVersion($record, $file, $request->get('change_notes'));
        }

        $record->update($validated);

        // Audit log
        AuditLog::logUpdate($record->getTable(), $record->id, $oldValues, $validated);

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->checkFunctionAccess(4); // Delete

        $record = $this->model::findOrFail($id);
        $this->authorizeAccess($record, 'delete');

        $oldValues = $record->toArray();
        $record->delete();

        // Audit log
        AuditLog::logDelete($record->getTable(), $record->id, $oldValues);

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Download file
     */
    public function download($id)
    {
        $record = $this->model::findOrFail($id);

        // Check strict granular permission first (Approved Request)
        // We pass 'download' action to authorizeAccess.
        // But authorizeAccess usually checks request OR division.
        // We need to know if the PASS came from Request (which overrides RBAC) or Division (which requires RBAC).

        // Let's modify logic:
        // Try authorizeAccess with 'download'
        // If it passes via Request, we are good.
        // If it passes via Division, we MUST also check checkFunctionAccess(5).

        // Easier approach:
        // Check if user has explicit 'download' permission on this record via Request
        // Check if user has explicit 'download' permission on this record via Request
        $user = auth()->user();
        $accessRequest = \App\Models\FileAccessRequest::where('document_type', $record->getTable())
            ->where('document_id', $record->id)
            ->where('id_user', $user->id)
            ->where('status', 'approved')
            ->whereJsonContains('permissions', 'download')
            ->first();

        if ($accessRequest && $accessRequest->isValid()) {
            // Bypass RBAC, just allow

            // Increment Download Count if limit is set (AND it's not the owner/superadmin - but fetching logic implies this is a request)
            if ($accessRequest->download_limit) {
                $accessRequest->incrementDownload();
            }

            AuditLog::logDownload($record->getTable(), $record->id);

            if (!$record->file || !Storage::exists($this->storagePath . '/' . $record->file)) {
                abort(404, 'File tidak ditemukan');
            }

            return Storage::download($this->storagePath . '/' . $record->file, $record->file_name);
        } elseif ($accessRequest && !$accessRequest->isValid()) {
             abort(403, 'Akses unduhan Anda telah kadaluarsa atau mencapai batas jumlah.');
        }

        // Check General Access via Model Logic (Umum, Internal-Div, Creator, Division Admin)
        if ($record->userHasFileAccess($user->id)) {
             // If model says yes (e.g. Umum, or Internal+SameDiv), we allow.
             // We do NOT check checkFunctionAccess(5) here because "Umum" implies public access
             // and usually overrides module-level restriction for basic file viewing.

             if (!$record->file) {
                abort(404, 'File tidak ditemukan');
            }

            $path = $this->storagePath . '/' . $record->file;

            if (!Storage::exists($path)) {
                abort(404, 'File tidak ditemukan');
            }

            AuditLog::logDownload($record->getTable(), $record->id);

            return Storage::download($path, $record->file_name);
        }

        // Fallback to strict RBAC + Division check (unlikely to pass if userHasFileAccess failed, but safe fallback)
        $this->checkFunctionAccess(5); // Download permission
        $this->authorizeAccess($record, 'download');

        if (!$record->file) {
            abort(404, 'File tidak ditemukan');
        }

        $path = $this->storagePath . '/' . $record->file;

        if (!Storage::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        AuditLog::logDownload($record->getTable(), $record->id);

        return Storage::download($path, $record->file_name);
    }

    /**
     * Preview file inline (without forcing download)
     */
    public function preview($id)
    {
        $record = $this->model::findOrFail($id);

        // Same logic as download for permissions
        $user = auth()->user();
        $hasSpecificPermission = \App\Models\FileAccessRequest::where('document_type', $record->getTable())
            ->where('document_id', $record->id)
            ->where('id_user', $user->id)
            ->where('status', 'approved')
            ->whereJsonContains('permissions', 'download') // Preview usually requires download rights or at least read?
            // User asked for "download button missing", implying preview might work with just read?
            // But preview usually serves the file content.
            // If strictly "View" (Read), maybe preview is allowed but download not?
            // "View" permission implies seeing metadata + previewing content usually.
            // "Download" implies saving raw file.
            // Let's assume Preview requires 'read' (View) OR 'download'.
            // If user has 'read', allow preview?
            // User Prompt said: "Anda tidak memiliki hak akses untuk mengunduh file pada menu ini." which is error from download button.
            // Preview button usually uses `preview` route.
            // Let's allow preview if 'read' permission exists.
            ->exists();

        // Check read permission specific
        $hasReadPermission = \App\Models\FileAccessRequest::where('document_type', $record->getTable())
            ->where('document_id', $record->id)
            ->where('id_user', $user->id)
            ->where('status', 'approved')
            ->whereJsonContains('permissions', 'read')
            ->exists();

        if ($hasSpecificPermission || $hasReadPermission) {
             // Allow
             // No RBAC check needed if specific permission exists
        } elseif ($record->userHasFileAccess($user->id)) {
             // Allow based on model policy (Umum, Internal+Div)
             // We do NOT check checkFunctionAccess(5) here because "Umum" implies public access
             // and usually overrides module-level restriction for basic file viewing.
        } else {
             // Fallback
             // Previewing might be considered 'download' in terms of function access (5)?
             // Or 'read' (1 - View Index/Show)?
             // Usually previewing the PDF is part of 'View'.
             // Let's check 'View' access (Show).
             // But existing code called checkFunctionAccess(5) for preview.
             // Let's keep it 5 for consistency UNLESS granular override.

             // If RBAC check fails but we want to allow, we handle it.
             // If no granular, we enforce RBAC.
             $this->checkFunctionAccess(5);
             $this->authorizeAccess($record, 'download');
        }

        if (!$record->file) {
            abort(404, 'File tidak ditemukan');
        }

        $path = $this->storagePath . '/' . $record->file;

        if (!Storage::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        AuditLog::logDownload($record->getTable(), $record->id);

        // Get file content and MIME type
        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);

        // Return response with inline disposition
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $record->file_name . '"');
    }


    /**
     * Download specific version
     */
    public function downloadVersion($id, $versionId)
    {
        $record = $this->model::findOrFail($id);
        $this->authorizeAccess($record);

        $version = DocumentVersion::findOrFail($versionId);

        if (!$version->file) {
            abort(404, 'File tidak ditemukan');
        }

        $path = 'versions/' . $version->file;

        if (!Storage::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::download($path, $version->file_name);
    }

    /**
     * Handle file upload
     */
    protected function handleFileUpload($file)
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($this->storagePath, $filename);
        return $filename;
    }

    /**
     * Create document version
     */
    protected function createDocumentVersion($record, $file, $notes = null)
    {
        $versionFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('versions', $versionFilename);

        DocumentVersion::createVersion(
            $record->getTable(),
            $record->id,
            $versionFilename,
            $file->getClientOriginalName(),
            $file->getSize(),
            auth()->id(),
            $notes
        );
    }

    /**
     * Get user accessible divisions
     */
    protected function getUserDivisions()
    {
        return auth()->user()->getAccessibleDivisions();
    }

    /**
     * Authorize access to record
     */
    protected function authorizeAccess($record, $action = 'read')
    {
        $user = auth()->user();

        // Super Admin access
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Creator access
        if ($record->created_by === $user->id) {
            return true;
        }

        // Division Admin/Member access (Internal)
        // If user is part of the division, they usually have full access depending on role

        // 1. Universal Access for 'Umum' usage (Public/General)
        // Fixes: "Klasifikasi umum search permission (untuk file yang klasifikasinya umum, itu bisa langsung dilihat)"
        if ($action === 'read' && $record->sifat_dokumen === 'Umum') {
            return true;
        }

        // 2. Division Admin (Manager) has full access to ALL documents in division (including Rahasia)
        if ($user->isDivisionAdmin($record->id_divisi)) {
            return true;
        }

        // 2. Division Member has access to Umum & Internal, but NOT Rahasia
        if ($user->hasDivisionAccess($record->id_divisi)) {
             // Exception: Rahasia documents still need strict check?
             // Existing logic said: "Internal: Only visible to division members"
             // "Rahasia: STRICT: Even same division staff cannot access unless they are admin or have approved request"
             if ($record->isSecret()) {
                // If checking for 'read' (Show Page), we ALLOW it so they can see metadata and request access
                if ($action === 'read') {
                    return true;
                }
                // For download/edit/delete, we continue to check for approved request below
             } else {
                 return true;
             }
        }

        // Check for Approved Access Request with Specific Permission
        $accessRequest = \App\Models\FileAccessRequest::where('document_type', $record->getTable())
            ->where('document_id', $record->id)
            ->where('id_user', $user->id)
            ->where('status', 'approved')
            ->first();

        if ($accessRequest) {
            // Check limits (Time & Download Count)
            if (!$accessRequest->isValid()) {
                 // If expired/limit reached, this specific request is no longer valid.
                 // We simply stop checking this request and let it fall through to denial.
                 // Optionally we could abort specific message here if we know this was the only way they had access.
                 // But for now, let it fall through.
            } else {
                // Map controller actions to permission keys
                $permissionMap = [
                    'read' => 'read',
                    'view' => 'read', // Alias
                    'download' => 'download',
                    'write' => 'edit',
                    'edit' => 'edit', // Alias
                    'delete' => 'delete',
                ];

                $requiredPermission = $permissionMap[$action] ?? $action;

                if ($accessRequest->hasPermission($requiredPermission)) {
                    return true;
                }

                // Helpful error message for granular denial
                if ($action !== 'read') { // Don't abort for read, let it fall through or generic 403
                     abort(403, 'Anda tidak memiliki izin ' . strtoupper($requiredPermission) . ' untuk dokumen ini.');
                }
            }
        }

        // Final check based on action
        if ($action === 'read') {
             // User has no valid request or valid division access
             $message = $record->isSecret()
                ? 'Dokumen ini bersifat rahasia. Anda perlu meminta akses terlebih dahulu.'
                : 'Anda tidak memiliki akses ke dokumen ini.';
             abort(403, $message);
        }

        // For other actions, if we reached here, access is denied
        abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
    }

    /**
     * Validate request - override in child class
     */
    abstract protected function validateRequest(Request $request, $id = null);

    /**
     * Get permission flags for current menu
     */
    protected function getPermissions()
    {
        $user = auth()->user();
        if (!$user) {
            return ['create' => false, 'edit' => false, 'delete' => false, 'download' => false];
        }

        if ($user->isSuperAdmin()) {
            return ['create' => true, 'edit' => true, 'delete' => true, 'download' => true];
        }

        $menu = \App\Models\BaseMenu::where('code_name', $this->routePrefix)->first();
        if (!$menu) {
            return ['create' => true, 'edit' => true, 'delete' => true, 'download' => true];
        }

        // Helper to check permission including inheritance
        $check = function($funcId) use ($user, $menu) {
            if ($user->hasMenuFunction($menu->id, $funcId)) return true;
            if ($menu->parent_id && $user->hasMenuFunction($menu->parent_id, $funcId)) return true;
            return false;
        };

        return [
            'create' => $check(2),
            'edit' => $check(3),
            'delete' => $check(4),
            'download' => $check(5),
        ];
    }

    /**
     * Check valid function access (Create, Edit, Delete, Download)
     *
     * @param int $functionId
     * @return bool
     */
    protected function checkFunctionAccess($functionId)
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return true;
        }

        // Find current menu based on route prefix
        $menu = \App\Models\BaseMenu::where('code_name', $this->routePrefix)->first();

        if (!$menu) {
            // Fallback: If menu not found in DB, we rely on standard authorized logic only
            return true;
        }

        // Check explicit menu access first
        $hasAccess = $user->hasMenuFunction($menu->id, $functionId);

        // If not found, check parent (inheritance)
        if (!$hasAccess && $menu->parent_id) {
            $hasAccess = $user->hasMenuFunction($menu->parent_id, $functionId);
        }

        if (!$hasAccess) {
            $functionName = '';
            switch ($functionId) {
                case 2: $functionName = 'menambah data'; break;
                case 3: $functionName = 'mengubah data'; break;
                case 4: $functionName = 'menghapus data'; break;
                case 5: $functionName = 'mengunduh file'; break;
                default: $functionName = 'melakukan aksi ini';
            }

            abort(403, 'Anda tidak memiliki hak akses untuk ' . $functionName . ' pada menu ini.');
        }

        return true;
    }
}
