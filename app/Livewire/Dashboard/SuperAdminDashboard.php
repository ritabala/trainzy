<?php

namespace App\Livewire\Dashboard;

use App\Models\Gym;
use Livewire\Component;
use App\Models\GymPackageSubscription;

class SuperAdminDashboard extends Component
{
    public function render()
    {
        abort_if(!auth()->user()->hasRole('super-admin'), 403);

        $totalGyms = Gym::count();
        $todayGyms = Gym::whereDate('created_at', now()->toDateString())->count();
        $totalActivePackages = GymPackageSubscription::where('is_active', true)->count();

        $recentGyms = Gym::orderBy('created_at', 'desc')->take(5)->get();

        return view('livewire.dashboard.super-admin-dashboard', [
            'totalGyms' => $totalGyms,
            'todayGyms' => $todayGyms,
            'totalActivePackages' => $totalActivePackages,
            'recentGyms' => $recentGyms
        ]);
    }
} 