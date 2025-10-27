<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $fillable = ['name', 'email', 'password', 'role'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
   protected $casts = [
    'email_verified_at' => 'datetime',
    'password_updated_at' => 'datetime', 
];


     public function enrollments() {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function courses() {
        return $this->hasMany(Course::class, 'instructor_id');
    } 
 


public function wishlistItems()
{
    return $this->hasMany(Wishlist::class);
}


public function wishedCourses()
{
    return $this->belongsToMany(Course::class, 'wishlists')
                ->withTimestamps()
                ->select('courses.*'); 
}

public function enrolledCourses()
{
    return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
                ->select('courses.*');
}




}
