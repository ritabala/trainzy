<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffAttendController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('staff_attendance'), 403, __('errors.module_access_denied'));
    }

    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_staff_attendance'), 403, __('errors.permission_denied'));
        return view('attendance.staff.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_staff_attendance'), 403, __('errors.permission_denied'));
        return view('attendance.staff.create');
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('edit_staff_attendance'), 403, __('errors.permission_denied'));
        return view('attendance.staff.edit', compact('id'));
    }

    public function show($id)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_staff_attendance'), 403, __('errors.permission_denied'));
        return view('attendance.staff.show', compact('id'));
    }
} 