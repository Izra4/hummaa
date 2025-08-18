<?php

namespace App\Notifications;

use App\Models\TryoutResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TryoutCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TryoutResult $tryoutResult
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tryout Completed - ' . $this->tryoutResult->tryout->nama_tryout)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have successfully completed the tryout: ' . $this->tryoutResult->tryout->nama_tryout)
            ->line('Your Score: ' . $this->tryoutResult->skor_akhir . '%')
            ->line('Completed at: ' . $this->tryoutResult->waktu_selesai->format('d M Y H:i'))
            ->action('View Results', url('/tryout/hasil/' . $this->tryoutResult->hasil_id))
            ->line('Keep up the great work!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'tryout_completed',
            'tryout_name' => $this->tryoutResult->tryout->nama_tryout,
            'score' => $this->tryoutResult->skor_akhir,
            'result_id' => $this->tryoutResult->hasil_id,
            'completed_at' => $this->tryoutResult->waktu_selesai,
        ];
    }
}