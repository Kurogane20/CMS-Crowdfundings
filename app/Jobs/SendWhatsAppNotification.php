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

    protected $phone;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param string $phone
     * @param string $message
     */
    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Kirim notifikasi WhatsApp menggunakan Guzzle HTTP Client
        $client = new Client();
        $response = $client->post('https://api.watzap.id/v1/send_image_url', [
            'json' => [
                'api_key' => 'BC3KF5E3LIAF7FW3',
                'number_key' => 'NTDtKsjQkGZbPTcL',
                'phone_no' => $this->phone,
                'url' => 'https://jadimanfaat.org/uploads/logo/1707337747ltdeq-favicon.png',
                'message' => $this->message
            ]
        ]);

        // Anda bisa menambahkan log atau penanganan lainnya di sini
    }
}
