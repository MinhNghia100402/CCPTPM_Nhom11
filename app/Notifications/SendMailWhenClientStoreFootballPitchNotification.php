<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMailWhenClientStoreFootballPitchNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $order;
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        //$url = route('client.findOrderByCode') . '?code=' . $this->order->code;
        return (new MailMessage)
                    ->subject('Đã tiếp nhận yêu cầu '. $this->order->code)
                    ->greeting('Xin chào ' . $this->order->name)
                    ->line('Chúng tôi đã nhận được yêu cầu đặt sân của bạn.')
                    ->line('Hiện tại yêu cầu này đang được xử lý.')
                    //->action('Bạn có thể xem tình trạng sân bóng của bạn tại đây', $url)
                    ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
