<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use ZendService\Apple\Apns\Client\Message;

class NotificationsApple extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $message = \PushNotification::Message($this->data['message'], [
            'custom' => [
                'notification_id' => $this->data['notification_id'],
                'message' => $this->data['message'],
                'status' => $this->data['status'],
                'type' => $this->data['type'],
                'created_at' => $this->data['created_at'],
                'data' => $this->data['data']
            ]
        ]);


        $collection = \PushNotification::app('HoboJet')
            ->to($this->data['token'])
            ->send($message);


        $response = NULL;
        foreach ($collection->pushManager as $push) {
            $response = $push->getAdapter()->getResponse();
            $response = $response->getCode();

        }
        \Log::useDailyFiles(storage_path('logs/queue.log'));
        \Log::info(
            '---SEND---' . "\n" .
            '-- TOKEN' . "\n" .
            $this->data['token'] .
            '-- MESSAGE' . "\n" .
            $this->data['message'] . "\n" .
            '-- RESPNSE' . "\n" .
            $response
        );


    }
}
