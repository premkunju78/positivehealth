<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointConfirmationToClient extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $appointment_details;
    protected $consultantDetail;
    public function __construct($appointment_details, $consultantDetail)
    {
        $this->appointment_details = $appointment_details;
        $this->consultantDetail = $consultantDetail;

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
                    ->subject('New Appointment: '.$this->appointment_details['timeslot_from'].' - '.$this->appointment_details['timeslot_to'])
                    ->greeting('Hi '.$this->appointment_details['name'])
                    ->line('A new Appointment has been scheduled with '.$this->consultantDetail['first_name'])
                    ->line('Appointment Details:')
                    ->line('Appointment Date/Time: '.$this->appointment_details['timeslot_from']. '  To  '.$this->appointment_details['timeslot_to'])
                    ->line('Description: '.$this->appointment_details['description'])
                    ->line('Thank you for using our application!');
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
