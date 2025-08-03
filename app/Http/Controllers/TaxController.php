<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax;
class TaxController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.taxes.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.taxes.create');
    }

    public function edit(Tax $tax)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.taxes.edit', compact('tax'));
    }
} 
