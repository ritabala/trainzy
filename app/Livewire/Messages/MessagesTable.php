<?php

namespace App\Livewire\Messages;

use App\Models\Message;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageMail;
use App\Models\User;

class MessagesTable extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $dateRangeStart = '';
    public $dateRangeEnd = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $nonSortableFields = ['actions'];
    public $perPage = 10;

    protected $listeners = ['deleteMessage'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateRangeStart()
    {
        $this->resetPage();
    }

    public function updatingDateRangeEnd()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if (in_array($field, $this->nonSortableFields)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->dateRangeStart = '';
        $this->dateRangeEnd = '';
        $this->resetPage();
        $this->resetValidation();
    }

    public function handleDeleteMessage($id)
    {
        $this->alert('warning', __('emails.messages.confirm_delete'), [
            'position' => 'center',
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => 'deleteMessage',
            'showCancelButton' => true,
            'cancelButtonText' => __('common.cancel'),
            'confirmButtonText' => __('common.yes_delete'),
            'data' => [
                'message_id' => $id,
            ],
            'customClass' => [
                'popup' => 'text-sm leading-relaxed lg:w-1/2 lg:max-w-full'
            ]
        ]);
    }

    public function deleteMessage($data)
    {
        try {
            $message = Message::findOrFail($data['message_id']);
            $message->delete();
            $this->alert('success', __('emails.messages.deleted'));
        } catch (\Exception $e) {
            $this->alert('error', __('emails.messages.delete_failed'));
        }
    }

    public function getRecipientNames($message)
    {
        if (empty($message->recipient_ids)) {
            return match($message->recipient_type) {
                'members' => __('emails.messages.all_members'),
                'staff' => __('emails.messages.all_staff'),
                default => ''
            };
        }

        return $message->recipients->pluck('user.name')->join(', ');
    }

    public function resendMessage($messageId)
    {
        try {
            $message = Message::findOrFail($messageId);
            $recipients = collect();
            

            if (empty($message->recipient_ids)) {
                $query = User::query();
                
                if ($message->recipient_type === 'members') {
                    $query->whereHas('roles', fn($q) => $q->where('name', 'member-' . gym()->id));
                    
                    if ($message->activity_class_id) {
                        $query->whereHas('latestMembership', function ($q) use ($message) {
                            $q->where('membership_status', 'active')
                              ->whereHas('membership', function ($q) use ($message) {
                                  $q->whereHas('activityClasses', fn($q) => $q->where('activity_classes.id', $message->activity_class_id));
                              });
                        });
                    }
                } else {
                    $query->whereHas('roles', fn($q) => $q->whereIn('name', ['staff-' . gym()->id, 'admin-' . gym()->id]));
                }
                
                $recipients = $query->get();
            } else {
                $recipients = User::whereIn('id', $message->recipient_ids)->get();
            }

            foreach ($recipients as $recipient) {
                Mail::to($recipient->email)
                    ->send(new MessageMail($message, $recipient));
            }
            $this->alert('success', __('emails.messages.resend_success'));
        } catch (\Exception $e) {
            $this->alert('error', __('emails.messages.resend_failed') . ': ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Message::query()
            ->with(['creator', 'recipients.user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('subject', 'like', '%' . $this->search . '%')
                      ->orWhere('body', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dateRangeStart, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateRangeStart);
            })
            ->when($this->dateRangeEnd, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateRangeEnd);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.messages.messages-table', [
            'messages' => $query->paginate($this->perPage)
        ]);
    }
} 