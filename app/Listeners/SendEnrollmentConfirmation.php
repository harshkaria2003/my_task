<?php

namespace App\Listeners;

use App\Events\CourseEnrolled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\EnrollmentConfirmationMail;
use Illuminate\Support\Facades\Mail;

class SendEnrollmentConfirmation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CourseEnrolled  $event
     * @return void
     */
public function handle(CourseEnrolled $event)
{
    $enrollment = $event->enrollment->load(['student', 'course', 'payment']);
    
    Mail::to($enrollment->student->email)->queue(new EnrollmentConfirmationMail($enrollment));
}


}
