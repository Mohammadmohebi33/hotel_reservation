<?php

namespace App\Jobs;

use App\Mail\BookingConfirmedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $booking;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param array $booking
     */
    public function __construct(string $email, array $booking)
    {
        $this->email = $email;
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new BookingConfirmedMail($this->booking));
    }
}
