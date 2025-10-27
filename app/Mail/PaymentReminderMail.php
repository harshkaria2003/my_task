<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $student;
    public $course;
    public $payment_link;

    public function __construct(Enrollment $enrollment)
    {
        $this->student = $enrollment->student;
        $this->course = $enrollment->course;
        $this->payment_link = route('student.checkout', $enrollment->id);
    }

    public function build()
    {
        return $this->subject('⏰ Payment Reminder: Complete Your Enrollment')
                    ->view('emails.payment_reminder');
    }
}
