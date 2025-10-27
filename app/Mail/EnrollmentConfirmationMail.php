<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Enrollment;

class EnrollmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $enrollment;

    /**
     * Create a new message instance.
     *
     * @param  Enrollment  $enrollment
     * @return void
     */
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Course Enrollment Confirmation')
                    ->view('emails.enrollment_confirmation')
                    ->with([
                        'student' => $this->enrollment->student,
                        'course' => $this->enrollment->course,
                        'payment_completed' => $this->enrollment->payment_completed,
                        'transaction_id' => optional($this->enrollment->payment)->transaction_id,
                        'amount' => optional($this->enrollment->payment)->amount,
                    ]);
    }
}
