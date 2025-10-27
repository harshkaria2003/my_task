<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseEnrolled implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $enrollment;

   
    public function __construct($enrollment)
    {
        
        $this->enrollment = $enrollment->load('student', 'course');
    }

    
    public function broadcastOn()
    {
        return new PrivateChannel('instructor.' . $this->enrollment->course->instructor_id);
    }

 
    public function broadcastWith()
    {
        return [
            'enrollment_id' => $this->enrollment->id,
            'student_name'  => $this->enrollment->student->name,
            'course_title'  => $this->enrollment->course->title,
            'enrolled_at'   => $this->enrollment->created_at->toDateTimeString(),
        ];
    }
}
