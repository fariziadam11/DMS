<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of tags
     */
    public function index()
    {
        $tags = Tag::withCount('documentTags')
            ->orderBy('name')
            ->paginate(20);

        return view('tags.index', compact('tags'));
    }

    /**
     * Store a newly created tag
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tags,name',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Tag::create($validated);

        return redirect()->route('master.tags.index')->with('success', 'Tag berhasil dibuat.');
    }

    /**
     * Update the specified tag
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tags,name,' . $id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $tag->update($validated);

        return back()->with('success', 'Tag berhasil diperbarui.');
    }

    /**
     * Remove the specified tag
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return back()->with('success', 'Tag berhasil dihapus.');
    }

    /**
     * Show documents with this tag (redirect to search)
     */
    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        return redirect()->route('search', ['tag' => $slug]);
    }
}
