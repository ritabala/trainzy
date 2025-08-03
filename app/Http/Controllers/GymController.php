<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GymController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_gyms'), 403);
        return view('gyms.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('add_gym'), 403);
        return view('gyms.create');
    }

    public function edit($gymId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('edit_gym'), 403);
        return view('gyms.edit', compact('gymId'));
    }

    public function show($gymId)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_gyms'), 403);
        return view('gyms.show', compact('gymId'));
    }


    public function personate(Gym $gym)
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('personate_admin'), 403);
        return view('gyms.edit', compact('gym'));
    }

    public function packageSubscriptionHistory()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_gyms'), 403);
        return view('gyms.subscription-history');
    }

    public function payments()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_all_gym_payments'), 403);
        return view('gyms.payments');
    }
}
