<?php

namespace App\Livewire\Messages;

use Livewire\Component;

class MessageManagement extends Component
{
    public function addMessage()
    {
        return redirect()->route('messages.create');
    }

    public function render()
    {
        abort_if(!auth()->user()->getCachedPermissions()->contains('view_messages'), 403);
        return view('livewire.messages.message-management');
    }
} 