<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PackagesTable extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $status = '';
    public $sortField = 'package_name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'package_name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    protected $listeners = ['deletePackage'];

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

    public function handleDeletePackage($id)
    {
        $this->alert('warning', __('package.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deletePackage',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.yes_delete'),
            'data' => [
                'package_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deletePackage($data)
    {
        try {
            $package = Package::findOrFail($data['package_id']);
            $package->delete();
            
            $this->alert('success', __('package.deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('package.delete_failed', ['message' => $e->getMessage()]));
        }
    }

    public function render()
    {
        $query = Package::query()
            ->with(['modules', 'currency'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('package_name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== '', function ($query) {
                $query->where('is_active', $this->status === 'active');
            });

        // Only allow sorting by allowed fields
        $allowedSorts = ['id', 'package_name', 'monthly_price', 'annual_price', 'lifetime_price', 'is_active'];
        $sortField = in_array($this->sortField, $allowedSorts) ? $this->sortField : 'package_name';

        $packages = $query
            ->orderBy($sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.packages.packages-table', [
            'packages' => $packages
        ]);
    }
} 