<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->getCachedPermissions()->contains('view_dashboard')) {
            return view('dashboard.index');
        } else {
            abort(403, 'You do not have permission to view this page.');
        }
    }
} 