<?php


namespace Arif\FleetCartApi\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (config('fleetcart_api.use_mailable')) {
            return (new MailMessage)
                ->greeting(trans('fleetcart_api::emails.reset_password_request.greeting'))
                ->line(trans('fleetcart_api::emails.reset_password_request.line_1', ['token' => $this->token->token]))
                ->line(trans('fleetcart_api::emails.reset_password_request.line_2'))
                ->subject(trans("fleetcart_api::emails.reset_password_request.subject"));
        }
        return (new MailMessage)
                ->view('fleetcart_api::emails.password_reset', ['token' => $this->token->token])
            ->subject(trans("fleetcart_api::emails.reset_password_request.subject"));
    }
}
