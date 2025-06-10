<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'username',
        'email',
        'password',
        'phone_number',
        'gender',
        'birth_date',
        'address',
        'about_me',
        'profile_picture',
        'background_picture',
        'google_id',
        'notice',
    ];

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
        'birth_date' => 'date',
        'password' => 'hashed',
    ];

    /**
     * Relasi many-to-one ke model Role.
     * Setiap User memiliki satu Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relasi one-to-many ke model Blog.
     * User bertindak sebagai penulis (author).
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * Relasi one-to-many ke model Comment.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    /**
     * Relasi one-to-many ke model VolunteerApplication.
     */
    public function volunteerApplications()
    {
        return $this->hasMany(VolunteerApplication::class);
    }

    /**
     * Relasi many-to-many untuk blog yang disukai oleh user.
     */
    public function likedBlogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_likes');
    }

    // --- Implementasi untuk JWT & Email Verification ---

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }
}