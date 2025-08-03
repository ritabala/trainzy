<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_packages'), 403);
        return view('packages.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_package'), 403);
        return view('packages.create');
    }

    public function edit($packageId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('edit_package'), 403);
        return view('packages.edit', compact('packageId'));
    }
} 