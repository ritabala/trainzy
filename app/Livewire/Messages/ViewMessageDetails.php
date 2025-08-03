<?php

namespace App\Livewire\Messages;

use App\Models\Message;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ViewMessageDetails extends Component
{
    use LivewireAlert;

    public $message;
    public $messageId;

    public function mount($messageId)
    {
        $this->messageId = $messageId;
        $this->loadMessage();
    }

    public function loadMessage()
    {
        $this->message = Message::with([
            'creator:id,name,email',
            'activityClass:id,name',
            'recipients.user:id,name,email'
        ])->findOrFail($this->messageId);
    }

    public function getRecipientTypeLabel()
    {
        return match($this->message->recipient_type) {
            'members' => __('emails.messages.recipient_type.members'),
            'staff' => __('emails.messages.recipient_type.staff'),
            // default => $this->message->recipient_type
        };
    }

    public function getRecipientNames()
    {
        if (empty($this->message->recipient_ids)) {
            return match($this->message->recipient_type) {
                'members' => __('emails.messages.all_members'),
                'staff' => __('emails.messages.all_staff'),
                default => ''
            };
        }

        return $this->message->recipients->pluck('user.name')->join(', ');
    }

    public function render()
    {
        return view('livewire.messages.view-message-details');
    }
} 