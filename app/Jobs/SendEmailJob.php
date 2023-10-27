<?php

namespace App\Jobs;

use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $messageBody;

    protected $messageSubject;

    protected $toEmailAddress;

    /**
     * Create a new job instance.
     */
    public function __construct($messageBody, $messageSubject, $toEmailAddress)
    {
        $this->messageBody = $messageBody;
        $this->messageSubject = $messageSubject;
        $this->toEmailAddress = $toEmailAddress;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->toEmailAddress)
            ->queue(new SendMail($this->messageBody, $this->messageSubject, $this->toEmailAddress));
    }
}
