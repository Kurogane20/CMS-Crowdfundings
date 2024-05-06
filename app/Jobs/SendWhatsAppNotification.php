<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phones;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param array $phones
     * @param string $message
     */
    public function __construct(array $phones, $message)
    {
        $this->phones = $phones;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Kirim notifikasi WhatsApp untuk setiap nomor telepon
        $client = new Client();
        
        foreach ($this->phones as $phone) {
            $response = $client->post('https://api.watzap.id/v1/send_image_url', [
                'json' => [
                    'api_key' => 'BC3KF5E3LIAF7FW3',
                    'number_key' => 'NTDtKsjQkGZbPTcL',
                    'phone_no' => $phone,
                    'url' => 'https://jadimanfaat.org/uploads/logo/1707337747ltdeq-favicon.png',
                    'message' => $this->message
                ]
            ]);
        }
        
        // Anda bisa menambahkan log atau penanganan lainnya di sini
    }
}
