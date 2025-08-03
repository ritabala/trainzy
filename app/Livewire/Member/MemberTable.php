<?php

namespace App\Livewire\Member;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Membership;
use App\Models\Frequency;
use App\Models\UserMembership;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Traits\InvalidatesUserSession;
use Illuminate\Support\Facades\Storage;

class MemberTable extends Component
{
    use WithPagination, LivewireAlert;
    use InvalidatesUserSession;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 5;
    public $membershipStatus = '';
    public $membershipId = '';
    public $frequencyId = '';
    public $membershipStatuses = [];
    public $memberships = [];
    public $frequencies = [];
    public $showMoreFilters = false;
    public $dateFilterType = ''; // Dropdown selection: 'startDate', 'expiryDate', 'createdAt'
    public $dateRangeStart;
    public $dateRangeEnd;

    protected $listeners = ['deleteMember'];


    public function mount()
    {
        $this->membershipStatuses = UserMembership::getMembershipStatuses();
        $this->memberships = Membership::all();
        $this->frequencies = Frequency::all();
    }

    public function updated($property)
    {
        if (in_array($property, [
            'search', 'membershipStatus', 'membershipId', 
            'frequencyId', 'dateRangeStart', 'dateRangeEnd', 'dateFilterType'
        ])) {
            $this->resetPage();
        }
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

    public function handleDeleteMember($id)
    {
        $this->alert('warning', __('members.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deleteMember',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.confirm'),
            'data' => [
                'member_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteMember($data)
    {
        try {
            DB::beginTransaction();
            
            $member = User::findOrFail($data['member_id']);
            
            // Delete profile photo from public disk
            if ($member->profile_photo_path) {
                Storage::disk('public')->delete($member->profile_photo_path);
            }
            
            // Delete documents from private disk and clean up folder
            $documents = $member->documents;
            foreach ($documents as $document) {
                Storage::disk('private')->delete($document->file_path);
                $document->delete();
            }
            // Delete the empty documents folder if it exists
            $documentsFolder = 'documents/member/' . $member->id;
            if (Storage::disk('private')->exists($documentsFolder)) {
                Storage::disk('private')->deleteDirectory($documentsFolder);
            }
            
            // Delete progress photos from private disk and clean up folders
            $progressPhotos = $member->progressPhotos;
            $progressPhotosDates = [];
            foreach ($progressPhotos as $photo) {
                Storage::disk('private')->delete($photo->file_path);
                // Extract the date folder path
                $datePath = dirname($photo->file_path);
                if (!in_array($datePath, $progressPhotosDates)) {
                    $progressPhotosDates[] = $datePath;
                }
                $photo->delete();
            }
            // Clean up empty date folders and main progress photos folder
            foreach ($progressPhotosDates as $datePath) {
                if (Storage::disk('private')->exists($datePath)) {
                    Storage::disk('private')->deleteDirectory($datePath);
                }
            }
            $progressPhotosFolder = 'progress-photos/' . $member->id;
            if (Storage::disk('private')->exists($progressPhotosFolder)) {
                Storage::disk('private')->deleteDirectory($progressPhotosFolder);
            }

            if (!$this->invalidateUserSessions($member)) {
                throw new \RuntimeException(__('errors.operation_failed'));
            }
            
            $member->delete();
            
            DB::commit();
            $this->alert('success', __('members.deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('errors.operation_failed') . ': ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->membershipId = '';
        $this->membershipStatus = '';
        $this->frequencyId = '';
        $this->dateFilterType = '';
        $this->dateRangeStart = '';
        $this->dateRangeEnd = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query()
            ->whereHas('roles', function($query) {
                $query->where('name', 'member-' . gym()->id);
            })
            ->with('latestMembership')
            ->where(function ($query) {
                if ($this->membershipStatus || $this->membershipId || $this->frequencyId || $this->dateFilterType) {
                    $query->whereHas('latestMembership', function ($q) {
                        if ($this->membershipStatus) {
                            $q->where('membership_status', $this->membershipStatus);
                        }
                        if ($this->membershipId) {
                            $q->where('membership_id', $this->membershipId);
                        }
                        if ($this->frequencyId) {
                            $q->whereHas('membershipFrequency', function ($q) {
                                $q->where('frequency_id', $this->frequencyId);
                            });
                        }
                        if ($this->dateFilterType) {
                            if (empty($this->dateRangeEnd)) {
                                $this->dateRangeEnd = now()->format('Y-m-d');
                            }
                            if (empty($this->dateRangeStart)) {
                                $this->dateRangeStart = Carbon::parse($this->dateRangeEnd)->subDays(6)->format('Y-m-d');
                            }

                            $dateColumnMap = [
                                'startDate' => 'membership_start_date',
                                'expiryDate' => 'membership_expiry_date',
                                'createdAt' => 'created_at'
                            ];

                            $selectedColumn = $dateColumnMap[$this->dateFilterType] ?? null;

                            if ($selectedColumn) {
                                if ($selectedColumn === 'created_at') {
                                    $q->whereRaw('DATE(created_at) >= ?', [$this->dateRangeStart])
                                      ->whereRaw('DATE(created_at) <= ?', [$this->dateRangeEnd]);
                                } else {
                                    $q->where($selectedColumn, '>=', $this->dateRangeStart)
                                      ->where($selectedColumn, '<=', $this->dateRangeEnd);
                                }
                            }
                        }
                    });
                }
            });

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhereHas('latestMembership.membership', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        $query->orderBy('users.'.$this->sortField, $this->sortDirection);
        $members = $query->paginate($this->perPage);

        return view('livewire.member.member-table', compact('members'));
    }
}
