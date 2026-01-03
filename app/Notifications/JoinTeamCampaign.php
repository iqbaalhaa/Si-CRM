<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Campaign;
use App\Models\User;

class JoinTeamCampaign extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Campaign $campaign, public ?User $assigner = null) {}

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
        $assignerName = $this->assigner?->name ?? 'System';
        return (new MailMessage)
            ->subject('Anda Bergabung ke Tim Campaign')
            ->greeting('Halo ' . ($notifiable->name ?? ''))
            ->line($assignerName . ' meng-assign Anda ke campaign: ' . ($this->campaign->name ?? 'Campaign'))
            ->action('Buka Campaign', route('campaign.show', $this->campaign->id))
            ->line('Terima kasih.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Join Team Campaign',
            'message' => ($this->assigner?->name ?? 'System') . ' meng-assign Anda ke campaign: ' . ($this->campaign->name ?? 'Campaign'),
            'url' => route('campaign.show', $this->campaign->id),
        ];
    }
}
