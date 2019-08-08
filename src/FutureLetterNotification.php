<?php

namespace Buzkall\FutureLetters;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FutureLetterNotification extends Notification
{
    use Queueable;
    protected $future_letter;

    /**
     * Create a new notification instance.
     *
     * @param $future_letter
     */
    public function __construct($future_letter)
    {
        $this->future_letter = $future_letter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $greeting = 'Hi,';
        if ($this->future_letter->user) {
            $greeting = 'Hi @' . $this->future_letter->user->name . ', ';
        }
        return (new MailMessage)
            ->greeting($greeting)
            ->subject($this->future_letter->subject)
            ->line($this->future_letter->message)
            ->salutation('--FutureLetters-- 
 You wrote this on the ' . $this->future_letter->created_at->format('d/m/Y H:i'));
    }

}
