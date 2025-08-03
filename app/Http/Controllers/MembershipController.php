<?php

namespace App\Http\Controllers;

use App\Models\Membership;

class MembershipController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('memberships'), 403, __('errors.module_access_denied'));
    }

    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_memberships'), 403);
        return view('membership.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_membership'), 403);
        return view('membership.create');
    }

    public function edit(Membership $membership)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('edit_membership'), 403);
        return view('membership.edit', compact('membership'));
    }

    public function userMemberships()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_user_memberships'), 403);
        return view('membership.user-memberships');
    }
}
