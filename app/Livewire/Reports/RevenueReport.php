<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use Carbon\Carbon;
use League\Csv\Writer;
use Illuminate\Pagination\LengthAwarePaginator;

class RevenueReport extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'membership_name';
    public $sortDirection = 'asc';
    public $year;
    public $selectedYear;
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'membership_name'],
        'sortDirection' => ['except' => 'asc'],
        'selectedYear' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        $this->year = Carbon::now()->year;
        $this->selectedYear = $this->year;
    }

    public function updatedSelectedYear()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
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

    public function getRevenueStats()
    {
        // Get membership revenue
        $membershipRevenue = Payment::membershipPayments($this->search)
            ->inYear($this->selectedYear)
            ->get()
            ->groupBy(['membership_name', function ($item) {
                return $item->payment_date->month;
            }])
            ->map(function ($membershipMonths) {
                return $membershipMonths->map(function ($monthPayments) {
                    return [
                        'total_revenue' => $monthPayments->sum('amount_paid'),
                        'total_payments' => $monthPayments->count()
                    ];
                });
            });

        // Get non-membership revenue
        $nonMembershipRevenue = Payment::nonMembershipPayments()
            ->inYear($this->selectedYear)
            ->get()
            ->groupBy(function ($item) {
                return $item->payment_date->month;
            })
            ->map(function ($monthPayments) {
                return [
                    'total_revenue' => $monthPayments->sum('amount_paid'),
                    'total_payments' => $monthPayments->count()
                ];
            });

        // Create revenue matrix
        $revenueMatrix = collect();
        $monthlyTotals = array_fill(1, 12, 0);
        $grandTotal = 0;

        // Process membership revenue
        foreach ($membershipRevenue as $membershipName => $monthlyData) {
            $yearTotal = 0;
            $formattedMonthlyData = [];

            for ($month = 1; $month <= 12; $month++) {
                $amount = isset($monthlyData[$month]) ? $monthlyData[$month]['total_revenue'] : 0;
                $formattedMonthlyData[$month] = $amount;
                $yearTotal += $amount;
                $monthlyTotals[$month] += $amount;
            }

            $revenueMatrix->push([
                'membership_name' => $membershipName,
                'monthly_data' => $formattedMonthlyData,
                'year_total' => $yearTotal
            ]);

            $grandTotal += $yearTotal;
        }

        // Process non-membership revenue
        $yearTotal = 0;
        $formattedMonthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $amount = isset($nonMembershipRevenue[$month]) ? $nonMembershipRevenue[$month]['total_revenue'] : 0;
            $formattedMonthlyData[$month] = $amount;
            $yearTotal += $amount;
            $monthlyTotals[$month] += $amount;
        }

        $revenueMatrix->push([
            'membership_name' => 'Other Revenue',
            'monthly_data' => $formattedMonthlyData,
            'year_total' => $yearTotal
        ]);

        $grandTotal += $yearTotal;

        // Sort the matrix if needed
        if ($this->sortField === 'membership_name') {
            $revenueMatrix = $revenueMatrix->sortBy('membership_name', SORT_REGULAR, $this->sortDirection === 'desc');
        } elseif ($this->sortField === 'year_total') {
            $revenueMatrix = $revenueMatrix->sortBy('year_total', SORT_REGULAR, $this->sortDirection === 'desc');
        }

        // Get the total count for pagination
        $total = $revenueMatrix->count();
        
        // Get the current page from the query string
        $page = request()->query('page', 1);
        
        // Slice the collection for the current page
        $items = $revenueMatrix->slice(($page - 1) * $this->perPage, $this->perPage)->values();
        
        // Create a paginator instance
        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $this->perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return [
            'revenue_data' => $paginator,
            'monthly_totals' => $monthlyTotals,
            'grand_total' => $grandTotal
        ];
    }

    public function exportToCSV()
    {
        // For export, get all data without pagination
        $stats = $this->getRevenueStats();
        $csv = Writer::createFromString('');
        
        // Add headers
        $headers = [__('reports.revenue.membership')];
        for ($month = 1; $month <= 12; $month++) {
            $headers[] = Carbon::create()->month($month)->format('M') ;
        }
        $headers[] = __('reports.revenue.year_total');
        $csv->insertOne($headers);
        
        // Add membership rows
        foreach ($stats['revenue_data'] as $row) {
            $rowData = [$row['membership_name']];
            foreach ($row['monthly_data'] as $amount) {
                $rowData[] = currency_format($amount);
            }
            $rowData[] = currency_format($row['year_total']);
            $csv->insertOne($rowData);
        }
        
        // Add monthly totals
        $totalRow = [__('reports.revenue.monthly_total')];
        foreach ($stats['monthly_totals'] as $total) {
            $totalRow[] = currency_format($total);
        }
        $totalRow[] = currency_format($stats['grand_total']);
        $csv->insertOne($totalRow);

        $filename = 'revenue-report-' . now()->format('Y-m-d') . '.csv';
        
        return response()->streamDownload(function () use ($csv) {
            echo $csv->toString();
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_revenue_reports'), 403);
        
        return view('livewire.reports.revenue-report', [
            'revenueStats' => $this->getRevenueStats(),
            'years' => range(Carbon::now()->year, Carbon::now()->subYears(5)->year)
        ]);
    }
} 