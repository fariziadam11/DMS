<?php

namespace App\Http\Controllers;

use App\Models\FileAccessRequest;
use Illuminate\Http\Request;

class MyRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * My access requests
     */
    public function index()
    {
        $requests = FileAccessRequest::with(['divisi', 'responder'])
            ->where('id_user', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('my-request.index', compact('requests'));
    }
}
