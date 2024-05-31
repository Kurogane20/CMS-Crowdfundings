<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\BankTransferReceived;
use Illuminate\Support\Facades\Mail;

class SendBankTransferReceivedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paymentData;

    /**
     * Create a new job instance.
     */
    public function __construct($paymentData)
    {
        $this->paymentData = $paymentData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->paymentData['email'])->send(new BankTransferReceived($this->paymentData));
    }
}
