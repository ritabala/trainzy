<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageUserRolesController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('role_management'), 403, __('errors.module_access_denied'));
    }

    function index()
    {
        return view('manage-user-roles.index');
    }

}
