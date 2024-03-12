<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Callback extends Notification
{
    use Queueable;

    protected $userInfo;
    protected $packageInfo;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userInfo, $packageInfo)
    {
        $this->userInfo = $userInfo;
        $this->packageInfo = $packageInfo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('Callback Request | '.$this->packageInfo['title'])
        ->line('Hai Admin')
        ->line('New Callback request came, below are the details.')
        ->line('Details:')
        ->line('Package Name: '.$this->packageInfo['title']. " program.")
        ->line('User Requested: '.$this->userInfo['first_name']) 
        ->line('User Email: '.$this->userInfo['email'])
        ->line('Thank You');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
