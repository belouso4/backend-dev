<?php

namespace App\Models;

use App\Notifications\ResetPasswordApi;
use App\Traits\HasRolesAndPermissions;
use Carbon\Carbon;
//use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyApiEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions;

    protected $fillable = [
        'name',
        'email',
        'password',
        'banned_until',
        'status'
    ];

    protected $dates = [
        'banned_until'
    ];

    public function setBannedUntilAttribute($value) {
        $this->attributes['banned_until'] = is_null($value) ? null : Carbon::parse($value);
    }

//    public function setStatusAttribute($value) {
//        $this->attributes['status'] = $value ? 1 : 0;
//    }

    protected $perPage = 10;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function likes(){
        return $this->belongsToMany( 'App\Models\Post', 'users_posts_likes', 'user_id', 'post_id');
    }

//    public function role() {
//        return $this->belongsTo(User::class);
//    }

    public function sendApiEmailVerificationNotification()
    {
        $this->notify( new VerifyApiEmailNotification );
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = env('FRONTEND_URL').'/reset-password/?token='.$token;

        $this->notify(new ResetPasswordApi($url));
    }
}
