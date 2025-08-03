<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MembersAttendController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('member_attendance'), 403, __('errors.module_access_denied'));
    }

    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_member_attendance'), 403, __('errors.permission_denied'));
        return view('attendance.members.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_member_attendance'), 403, __('errors.permission_denied'));
        return view('attendance.members.create');
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('edit_member_attendance'), 403, __('errors.permission_denied'));
        return view('attendance.members.edit', compact('id'));
    }

    public function show($id)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_member_attendance'), 403, __('errors.permission_denied'));
        return view('attendance.members.show', compact('id'));
    }
} 