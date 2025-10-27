<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = ['student_id', 'course_id', 'payment_completed'];

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');  
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    } 

    public function student()
    {

    return $this->belongsTo(User::class, 'student_id');
    
    }

}
