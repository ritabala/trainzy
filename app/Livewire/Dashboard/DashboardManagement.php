<?php

namespace App\Livewire\Dashboard;
use App\Models\ActivityClass;
use App\Models\Membership;
use App\Models\User;
use App\Models\Payment;
use App\Models\Invoice;
use Livewire\Component;

class DashboardManagement extends Component
{
    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_dashboard'), 403);

        $activeClasses = ActivityClass::where('is_active', 1)->count();

        $activeMemberships = Membership::where('is_active', 1)->count();

        $members = User::whereHas('roles', function($query) {
            $query->where('name', 'member-' . gym()->id);
        })->orderBy('created_at', 'desc')->limit(5)->count();

        $recentPayments = Payment::orderBy('created_at', 'desc')->where('status', 'completed')->limit(5)->get();

        $recentMembers = User::whereHas('roles', function($query) {
            $query->where('name', 'member-' . gym()->id);
        })->orderBy('created_at', 'desc')->limit(5)->get();

        $staff = User::query()
        ->whereHas('roles', function($query) {
            $query->where('name', 'staff-' . gym()->id);
        })->count();

        $monthlyRevenue = Payment::where('created_at', '>=', now()->startOfMonth())->sum('amount_paid');

        $unpaidInvoicesAmount = Invoice::where('status', 'unpaid')->sum('total_amount');

        return view('livewire.dashboard.dashboard-management', [
            'activeClasses' => $activeClasses,
            'activeMemberships' => $activeMemberships,
            'totalMembers' => $members,
            'recentMembers' => $recentMembers,
            'recentPayments' => $recentPayments,
            'monthlyRevenue' => $monthlyRevenue,
            'totalStaff' => $staff,
            'unpaidInvoicesAmount' => $unpaidInvoicesAmount,
        ]);
    }
} 