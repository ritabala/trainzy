<?php

namespace App\Http\Controllers;

use App\Models\StaffType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffTypeController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.staff_types.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.staff_types.create');
    }

    public function edit(StaffType $staffType)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.staff_types.edit', compact('staffType'));
    }
} 