<?php

namespace App\Livewire\Messages;

use App\Models\Message;
use App\Models\User;
use App\Models\ActivityClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\On;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Mail\MessageMail;

class CreateEditMessage extends Component
{
    use LivewireAlert;

    public $message = [];
    public $messageId;
    public $editMode = false;
    public $users = [];
    public $userSearchQuery = '';
    public $perPage = 5;
    public $userPage = 1;
    public $selectedActivityClass = null;
    public $selectedUsers = [];

    public function rules()
    {
        return [
            'message.subject' => 'required|string|max:255',
            'message.body' => 'required|string',
            'message.recipient_type' => 'required|string|in:members,staff',
            'message.activity_class_id' => 'nullable|exists:activity_classes,id,gym_id,' . gym()->id,
        ];
    }

    public function messages()
    {
        return [
            'message.subject.required' => __('messages.messages.subject_required'),
            'message.body.required' => __('messages.messages.body_required'),
            'message.recipient_type.required' => __('messages.messages.recipient_type_required'),
        ];
    }

    public function mount($messageId = null)
    {
        if ($messageId) {
            $this->messageId = $messageId;
            $this->editMode = true;
            $this->loadMessage();
        } else {
            $this->message = [
                'subject' => '',
                'body' => '',
                'recipient_type' => '',
                'activity_class_id' => null,
            ];
        }

        // Normalize recipient_type in case it's accidentally set as an array
        $this->normalizeRecipientType();
    }

    protected function normalizeRecipientType()
    {
        if (!is_string($this->message['recipient_type'])) {
            $this->message['recipient_type'] = is_array($this->message['recipient_type'])
                ? ($this->message['recipient_type'][0] ?? 'all_members')
                : 'all_members';
        }
    }

    public function loadMessage()
    {
        $message = Message::findOrFail($this->messageId);
        $this->message = $message->toArray();
        $this->normalizeRecipientType();
        $this->selectedUsers = $message->recipient_ids ?? [];
    }

    public function updatedSelectedActivityClass($value)
    {
        $this->message['activity_class_id'] = $value;
        $this->selectedUsers = [];
    }

    public function updatedMessageRecipientType($value)
    {
        // Normalize to string if needed
        $this->message['recipient_type'] = is_array($value) ? ($value[0] ?? 'all_members') : $value;
        $this->selectedUsers = [];
    }

    public function getFilteredUsersProperty()
    {
        $query = User::query();

        if (in_array($this->message['recipient_type'], ['members', 'selected_members'])) {
            $query->whereHas('roles', fn($q) => $q->where('name', 'member-' . gym()->id));
            
            // Activity class filtering only applies to members
            if ($this->message['activity_class_id']) {
                $query->whereHas('latestMembership', function ($q) {
                    $q->where('membership_status', 'active')
                      ->whereHas('membership', function ($q) {
                          $q->whereHas('activityClasses', fn($q) => $q->where('activity_classes.id', $this->message['activity_class_id']));
                      });
                });
            }
        } else {
            $query->whereHas('roles', fn($q) => $q->whereIn('name', ['staff-' . gym()->id, 'admin-' . gym()->id]));
        }

        if ($this->userSearchQuery) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->userSearchQuery . '%')
                  ->orWhere('email', 'like', '%' . $this->userSearchQuery . '%');
            });
        }

        return $query->paginate($this->perPage, ['*'], 'userPage', $this->userPage);
    }

    public function getSelectedUsersDataProperty()
    {
        if (empty($this->selectedUsers)) {
            return collect();
        }

        return User::whereIn('id', $this->selectedUsers)->get();
    }

    public function getActivityClassesProperty()
    {
        return ActivityClass::where('is_active', true)->get();
    }

    public function save()
    {
        $this->normalizeRecipientType();
        $this->validate();

        try {
            $message = DB::transaction(function () {
                $message = $this->editMode 
                    ? Message::findOrFail($this->messageId)
                    : new Message();

                $message->fill([
                    'subject' => $this->message['subject'],
                    'body' => $this->message['body'],
                    'recipient_type' => $this->message['recipient_type'],
                    'activity_class_id' => $this->message['activity_class_id'],
                    'created_by' => auth()->id(),
                    'recipient_ids' => $this->selectedUsers
                ]);

                $message->save();

                // Handle message_recipients
                if ($this->editMode) {
                    // Delete existing recipients
                    $message->recipients()->delete();
                }

                // Create new message_recipients entries
                $recipientData = collect($this->selectedUsers)->map(function ($userId) {
                    return [
                        'user_id' => $userId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                })->toArray();

                $message->recipients()->createMany($recipientData);

                return $message;
            });

            // Send emails to recipients
            $this->sendEmailsToRecipients($message);

            session()->flash('success', $this->editMode ? __('emails.messages.updated') : __('emails.messages.created'));
            return redirect()->route('messages.show', $message->id);

        } catch (\Throwable $e) {
            session()->flash('error', __('emails.messages.save_error') . $e->getMessage());
            return null;
        }
    }

    protected function sendEmailsToRecipients(Message $message)
    {
        try {
            $recipients = collect();
            
            if (empty($message->recipient_ids)) {
                $query = User::query();
                
                if ($message->recipient_type === 'members') {
                    $query->whereHas('roles', fn($q) => $q->where('name', 'member-' . gym()->id));
                    
                    // If activity class is specified, filter members by that class
                    if ($message->activity_class_id) {
                        $query->whereHas('latestMembership', function ($q) {
                            $q->where('membership_status', 'active')
                              ->whereHas('membership', function ($q) {
                                  $q->whereHas('activityClasses', fn($q) => $q->where('activity_classes.id', $message->activity_class_id));
                              });
                        });
                    }
                } else {
                    $query->whereHas('roles', fn($q) => $q->whereIn('name', ['staff-' . gym()->id, 'admin-' . gym()->id]));
                }
                
                $recipients = $query->get();
            } else {
                // Get selected users directly
                $recipients = User::whereIn('id', $message->recipient_ids)->get();
            }

            foreach ($recipients as $recipient) {
                Mail::to($recipient->email)
                    ->queue(new MessageMail($message, $recipient));
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send message emails: ' . ': ' . $e->getMessage());
            // Don't throw the error to prevent the save operation from failing
        }
    }

    public function cancel()
    {
        return redirect()->route('messages.index');
    }

    public function render()
    {
        return view('livewire.messages.create-edit-message', [
            'filteredUsers' => $this->filteredUsers,
            'activityClasses' => $this->activityClasses,
            'hasMoreUsers' => $this->filteredUsers->hasMorePages(),
            'selectedUsersData' => $this->selectedUsersData,
        ]);
    }

    public function toggleUserSelection($userId)
    {
        if (in_array($userId, $this->selectedUsers)) {
            $this->selectedUsers = array_values(array_diff($this->selectedUsers, [$userId]));
        } else {
            $this->selectedUsers = array_values(array_merge($this->selectedUsers, [$userId]));
        }
    }

    public function removeSelectedUser($userId)
    {
        $this->selectedUsers = array_values(array_diff($this->selectedUsers, [$userId]));
    }

    public function updatedUserSearchQuery()
    {
        $this->userPage = 1;
    }
}
