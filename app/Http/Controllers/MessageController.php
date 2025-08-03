<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('messages'), 403, __('errors.module_access_denied'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_messages'), 403, __('errors.permission_denied'));
        return view('messages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_message'), 403, __('errors.permission_denied'));
        return view('messages.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_messages'), 403, __('errors.permission_denied'));
        return view('messages.show', compact('id'));
    }
} 