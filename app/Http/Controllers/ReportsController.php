<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function membership()
    {
        abort_if(!has_module_access('reports') || !auth()->user()->getCachedPermissions()->contains('view_membership_reports'), 403);
        return view('reports.membership-report');
    }

    public function revenue()
    {
        abort_if(!has_module_access('reports') || !auth()->user()->getCachedPermissions()->contains('view_revenue_reports'), 403);
        return view('reports.revenue-report');
    }
} 