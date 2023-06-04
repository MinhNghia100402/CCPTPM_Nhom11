<?php

namespace App\Notifications;

use App\Enums\OrderStatusEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMailWhenUpdateStatusOrderNotification extends Notification
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
        $status = match ($this->order->status) {
            OrderStatusEnum::Finish => 'Đã đặt thành công',
            OrderStatusEnum::Paid => 'Đã thanh toán',
        };
        return (new MailMessage)
            ->subject('Yêu cầu đặt sân của bạn #'. $this->order->code . ' - '. $status)
            ->greeting('Xin chào ' . $this->order->name)
            ->lineIf($this->order->status == OrderStatusEnum::Finish, 
            "Chúng tôi rất vui vì bạn đã sử dụng dịch vụ của chúng tôi. Chúc bạn có một buổi đá bóng vui vẻ.")
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
