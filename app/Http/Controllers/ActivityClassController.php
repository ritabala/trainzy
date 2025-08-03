<?php

namespace App\Http\Controllers;

use App\Models\ActivityClass;

class ActivityClassController extends Controller
{
    public function __construct()
    {
        abort_if(!has_module_access('activity_classes'), 403, __('errors.module_access_denied'));
    }

    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_activity_classes'), 403);
        return view('activity-classes.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_activity_class'), 403);
        return view('activity-classes.create');
    }

    public function edit(ActivityClass $activityClass)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('edit_activity_class'), 403);
        return view('activity-classes.edit', compact('activityClass'));
    }

    public function show(ActivityClass $activityClass)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('schedule_activity_class'), 403);
        return view('activity-classes.show', compact('activityClass'));
    }

    public function staffSchedule()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_staff_schedule'), 403);
        return view('activity-classes.staff-schedule');
    }
} 