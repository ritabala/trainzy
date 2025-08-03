<?php

namespace App\Livewire\Staff;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Str;
use App\Models\StaffType;
use App\Traits\InvalidatesUserSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StaffTable extends Component
{
    use WithPagination, LivewireAlert;
    use InvalidatesUserSession;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 5;
    public $staffType;
    public $isActive= "";
    public $showPasswordModal = false;
    public $selectedStaffId;
    public $password;
    public $password_confirmation;
    protected $listeners = ['deleteStaff'];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'isActive' => ['except' => ''],
        'perPage' => ['except' => 5],
        'staffType' => ['except' => ''],
    ];

    protected $rules = [
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required|min:8',
    ];

    public function updated($property)
    {
        if (in_array($property, [
            'search',
            'staffType',
            'isActive'
        ])) {
            $this->resetPage();
        }
    }

    public function getSelectedStaffTypeNameProperty()
    {
        if ($this->staffType === '') {
            return __('staff.select_type');
        }

        return StaffType::find($this->staffType)?->name ??  __('staff.select_type');
    }


    public function formatLabel($value)
    {
        return Str::title(preg_replace('/([a-z])([A-Z])/', '$1 $2', $value));
    }

    public function sortBy($field)
    {
        $this->sortField = $field;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function handleDeleteStaff($id)
    {
        $this->alert('warning', __('staff.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deleteStaff',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.yes_delete'),
            'data' => [
                'staff_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteStaff($data)
    {
        try {
            DB::beginTransaction();
            
            $staff = User::findOrFail($data['staff_id']);
            
            // Delete profile photo from public disk
            if ($staff->profile_photo_path) {
                Storage::disk('public')->delete($staff->profile_photo_path);
            }
            
            // Delete documents from private disk and clean up folder
            $documents = $staff->documents;
            foreach ($documents as $document) {
                Storage::disk('private')->delete($document->file_path);
                $document->delete();
            }
            // Delete the empty documents folder if it exists
            $documentsFolder = 'documents/staff/' . $staff->id;
            if (Storage::disk('private')->exists($documentsFolder)) {
                Storage::disk('private')->deleteDirectory($documentsFolder);
            }

            if (!$this->invalidateUserSessions($staff)) {
                throw new \RuntimeException(__('staff.invalidate_ses'));
            }
            
            $staff->delete();
            
            DB::commit();
            $this->alert('success', __('staff.deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('staff.delete_failed', ['message' => $e->getMessage()]));
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->staffType = '';
        $this->isActive = '';
        $this->resetPage();
    }

    public function openPasswordModal($staffId)
    {
        $this->selectedStaffId = $staffId;
        $this->resetPasswordFields();
        $this->showPasswordModal = true;
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
        $this->resetPasswordFields();
    }

    public function resetPasswordFields()
    {
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetErrorBag();
    }

    public function updatePassword()
    {
        $this->validate();

        try {
            $user = User::findOrFail($this->selectedStaffId);
            $user->update([
                'password' => bcrypt($this->password)
            ]);

            $this->closePasswordModal();
            session()->flash('message', __('staff.update_pwd'));
        } catch (\Exception $e) {
            session()->flash('password_error', __('staff.failed_update_pwd'));
        }
    }

    public function render()
    {
        $query = User::query()
            ->with('staffDetail')
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['staff-' . gym()->id, 'admin-' . gym()->id]);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->staffType, function ($query) {
                $query->whereHas('staffDetail', function ($query) {
                    $query->where('staff_type_id', $this->staffType);
                });
            })
            ->when($this->isActive !== '', fn($query) =>
            $query->where('is_active', $this->isActive))
            ->orderBy($this->sortField, $this->sortDirection);

        $staff = $query->paginate($this->perPage);
        return view('livewire.staff.staff-table', [
            'staff' => $staff,
            'staffTypes' => StaffType::all()
        ]);
    }
} 