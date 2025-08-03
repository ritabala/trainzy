<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('services'), 403, __('errors.module_access_denied'));
    }

    public function index()
    {
        if (auth()->user()->getCachedPermissions()->contains('view_services')) {
            return view('services.index');
        } else {
            abort(403, 'You do not have permission to view this page.');
        }
    }

    public function create()
    {
        if (auth()->user()->getCachedPermissions()->contains('add_service')) {
            return view('services.create');
        } else {
            abort(403, 'You do not have permission to add this service.');
        }
    }

    public function edit(Service $service)
    {   
        if (auth()->user()->getCachedPermissions()->contains('edit_service')) {
            return view('services.edit', compact('service'));
        } else {
            abort(403, 'You do not have permission to edit this service.');
        }
    }
}