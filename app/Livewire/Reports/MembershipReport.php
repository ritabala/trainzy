<?php

namespace App\Livewire\Reports;

use App\Models\Membership;
use App\Models\UserMembership;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use League\Csv\Writer;

class MembershipReport extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $dateRange = 'this_month';
    public $startDate;
    public $endDate;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
        'dateRange' => ['except' => 'this_month'],
    ];

    public function mount()
    {
        $this->updateDateRange();
    }

    public function updatedDateRange()
    {
        $this->updateDateRange();
    }

    public function updateDateRange()
    {
        $now = Carbon::now();
        
        switch ($this->dateRange) {
            case 'this_month':
                $this->startDate = $now->startOfMonth()->toDateString();
                $this->endDate = $now->endOfMonth()->toDateString();
                break;
            case 'last_month':
                $this->startDate = $now->subMonth()->startOfMonth()->toDateString();
                $this->endDate = $now->endOfMonth()->toDateString();
                break;
            case 'last_3_months':
                $this->startDate = $now->subMonths(3)->startOfMonth()->toDateString();
                $this->endDate = Carbon::now()->endOfMonth()->toDateString();
                break;
            case 'last_6_months':
                $this->startDate = $now->subMonths(6)->startOfMonth()->toDateString();
                $this->endDate = Carbon::now()->endOfMonth()->toDateString();
                break;
            case 'this_year':
                $this->startDate = $now->startOfYear()->toDateString();
                $this->endDate = $now->endOfYear()->toDateString();
                break;
            case 'custom':
                // Keep existing custom dates if set
                if (!$this->startDate) {
                    $this->startDate = $now->startOfMonth()->toDateString();
                }
                if (!$this->endDate) {
                    $this->endDate = $now->endOfMonth()->toDateString();
                }
                break;
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getMembershipStatsQuery()
    {
        return Membership::withMembershipStats(
            $this->startDate,
            $this->endDate,
            $this->search
        )->orderBy($this->sortField, $this->sortDirection);
    }

    public function getMembershipStats()
    {
        return $this->getMembershipStatsQuery()->paginate($this->perPage);
    }

    public function exportToCSV()
    {
        $data = $this->getMembershipStatsQuery()->get();
        
        $csv = Writer::createFromString('');
        
        // Add headers
        $csv->insertOne([
            __('reports.membership.name'),
            __('reports.membership.status'),
            __('reports.membership.active_customers'),
            __('reports.membership.lost_customers'),
            __('reports.membership.renewals')
        ]);
        
        // Add data rows
        foreach ($data as $row) {
            $csv->insertOne([
                $row->name,
                $row->is_active ? __('common.active') : __('common.inactive'),
                $row->active_customers,
                $row->lost_customers,
                $row->renewals
            ]);
        }

        $filename = 'membership-report-' . now()->format('Y-m-d') . '.csv';
        
        return response()->streamDownload(function () use ($csv) {
            echo $csv->toString();
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        return view('livewire.reports.membership-report', [
            'membershipStats' => $this->getMembershipStats()
        ]);
    }
} 