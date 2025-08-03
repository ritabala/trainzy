<?php

namespace App\Http\Controllers;

use App\Models\BodyMetricType;
use Illuminate\Http\Request;

class BodyMetricTypeController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.body_metric.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.body_metric.create');
    }

    public function edit(BodyMetricType $body_metric)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('manage_settings'), 403);
        return view('settings.body_metric.edit', compact('body_metric'));
    }
} 