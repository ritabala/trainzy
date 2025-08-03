<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageContent;
    public $message;
    public $recipient;

    public function __construct(Message $message, User $recipient)
    {
        $this->messageContent = $message;
        $this->recipient = $recipient;
    }

    public function build()
    {
        return $this->subject($this->messageContent->subject)
            ->view('emails.message')
            ->with([
                'messageContent' => $this->messageContent,
                'recipient' => $this->recipient
            ]);
    }
} 