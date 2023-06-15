<?php

namespace App\Models;

use App\Helper\Helper;
use App\Notifications\ResetPasswordApi;
use App\Traits\HasRolesAndPermissions;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyApiEmailNotification;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRolesAndPermissions,
        Searchable;

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

    protected $perPage = 10;

    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
    ];

//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => Helper::getPathIfExist('avatar/small/', $this->avatar),
            'model' => 'user',
        ];
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->belongsToMany( 'App\Models\Post', 'users_posts_likes', 'user_id', 'post_id');
    }

    public function setBannedUntilAttribute($value)
    {
        $this->attributes['banned_until'] =
            is_null($value)
                ? null
                : Carbon::parse($value);
    }

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
