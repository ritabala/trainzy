<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('staff'), 403, __('errors.module_access_denied'));
    }
    
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_staff'), 403);
        return view('staff.index');
    }

    public function show($userId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_staff'), 403);
        return view('staff.show', compact('userId'));
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_staff'), 403);
        return view('staff.create');
    }

    public function edit($userId)
    {
        // Allow editing if user has permission or if it's their own profile
        abort_if(
            !auth()->user()->getCachedPermissions()->contains('edit_staff') && 
            auth()->user()->id != $userId, 
            403
        );
        return view('staff.edit', compact('userId'));
    }
} 