<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Membership;
use App\Models\MembershipFrequency;
use App\Models\UserMembership;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('members'), 403, __('errors.module_access_denied'));
    }

    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_members'), 403, __('errors.permission_denied'));
        return view('member.index');
    }

    public function show($userId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_members'), 403, __('errors.permission_denied'));
        return view('member.show', compact('userId'));
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_member'), 403, __('errors.permission_denied'));
        return view('member.create');
    }

    public function store(Request $request)
    {
    }

    public function edit($userId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('edit_member'), 403, __('errors.permission_denied'));
        return view('member.edit', compact('userId'));
    }

    public function update(Request $request, User $user)
    {
    }

    public function destroy(User $user)
    {
    }
}
