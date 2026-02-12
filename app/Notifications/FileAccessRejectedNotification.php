<?php

namespace App\Notifications;

use App\Models\FileAccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FileAccessRejectedNotification extends Notification
{
    use Queueable;

    /**
     * The file access request instance.
     *
     * @var FileAccessRequest
     */
    public $accessRequest;

    /**
     * Create a new notification instance.
     *
     * @param  FileAccessRequest  $accessRequest
     * @return void
     */
    public function __construct(FileAccessRequest $accessRequest)
    {
        $this->accessRequest = $accessRequest;
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
            ->subject('Permintaan Akses File Ditolak - Dapentelkom DMS')
            ->view('emails.file-access-rejected', [
                'accessRequest' => $this->accessRequest,
                'requester' => $notifiable,
            ]);
    }
}
