<?php

namespace App\Events;

use App\Models\Enrollment;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseEnrolled implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    // Type-hint property
    public Enrollment $enrollment;

    /**
     * Create a new event instance.
     *
     * @param  Enrollment  $enrollment
     */
    public function __construct(Enrollment $enrollment)
    {
        // Eager load related models
        $this->enrollment = $enrollment->loadMissing('student', 'course');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('instructor.' . $this->enrollment->course->instructor_id);
    }

    /**
     * The data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'enrollment_id' => $this->enrollment->id,
            'student_name'  => $this->enrollment->student->name,
            'course_title'  => $this->enrollment->course->title,
            'enrolled_at'   => $this->enrollment->created_at->toDateTimeString(),
        ];
    }
}
