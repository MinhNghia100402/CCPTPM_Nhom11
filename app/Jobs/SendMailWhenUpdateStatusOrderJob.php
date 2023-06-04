<?php

namespace App\Jobs;

use App\Notifications\SendMailWhenClientStoreFootballPitchNotification;
use App\Notifications\SendMailWhenUpdateStatusOrderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendMailWhenUpdateStatusOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $obj;
    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //$this->obj->notify((new SendMailWhenUpdateStatusOrderNotification($this->obj)));
        Notification::send($this->obj, (new SendMailWhenUpdateStatusOrderNotification($this->obj)));
        //Notification::send($this->obj, (new SendMailWhenClientStoreFootballPitchNotification($this->obj)));
    }
}
