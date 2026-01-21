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

        $items = $query->with('divisi')->paginate(15);

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

        return view($this->viewPath . '.show', [
            'record' => $record,
            'item' => $record, // Alias for backward compatibility
            'moduleName' => $this->moduleName,
            'routePrefix' => $this->routePrefix,
            'permissions' => $this->getPermissions(),
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
        $this->checkFunctionAccess(5); // Download

        $record = $this->model::findOrFail($id);
        $this->authorizeAccess($record);

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
        $this->authorizeAccess($record);

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

        if ($user->isSuperAdmin()) {
            return true;
        }

        // Check if user has explicit approved request
        // Use table name instead of class name for document_type
        $hasApprovedRequest = \App\Models\FileAccessRequest::where('document_type', $record->getTable())
            ->where('document_id', $record->id)
            ->where('id_user', $user->id)
            ->where('status', 'approved')
            ->exists();

        if ($hasApprovedRequest) {
            return true;
        }

        if (!$user->hasDivisionAccess($record->id_divisi)) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Check if secret document requires special access
        // Staff from the SAME division can view secret documents
        // Staff from OTHER divisions need explicit access request or be Division Admin
        if ($record->isSecret()) {
            $isOwnDivision = $user->id_divisi == $record->id_divisi;

            // If not from own division and not division admin, need approved request
            if (!$isOwnDivision && !$user->isDivisionAdmin($record->id_divisi) && !$hasApprovedRequest) {
                abort(403, 'Dokumen ini bersifat rahasia.');
            }
        }

        return true;
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
