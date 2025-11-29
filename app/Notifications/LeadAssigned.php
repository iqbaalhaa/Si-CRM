<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadAssigned extends Notification
{


    use Queueable;

    public Customer $customer;
    public ?User $assigner;

    /**
     * Create a new notification instance.
     */
    public function __construct(Customer $customer, ?User $assigner = null)
    {
        $this->customer = $customer;
        $this->assigner = $assigner;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $assignerName = $this->assigner?->name ?? 'System';

        return [
            'title'        => 'Lead baru ditugaskan ke Anda',
            'message'      => "Customer {$this->customer->name} telah ditugaskan ke Anda oleh {$assignerName}.",
            'customer_id'  => $this->customer->id,
            'customer_name' => $this->customer->name,
            'assigner_id'  => $this->assigner?->id,
            'assigner_name' => $assignerName,
            'assigned_at'  => now()->toDateTimeString(),
            'url'          => route('assign.index'),
        ];
    }
}