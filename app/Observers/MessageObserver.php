<?php

namespace App\Observers;

use App\Models\Message;

class MessageObserver
{
    public function creating(Message $message)
    {
        if (gym()) {
            $message->gym_id = gym()->id;
        }
    }
}
