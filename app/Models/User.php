<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use LaratrustUserTrait;
    use HasFactory;
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        // 'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    /*protected $appends = [
        'profile_photo_url',
    ];*/

    public function subjects()
    {
        return $this->belongsToMany(Subjects::class, 'student_subjects', 'user_id', 'subject_id')
                    ->withPivot('id','user_id', 'subject_id', 'state','expired_date')->wherePivot('state', 'active')->with('added_by:id,name');
    }

    public function sheet(){
        return $this->hasOne(\App\Models\Sheets::class, 'user_id', 'id');
    }
    public function getUserAvatar(){
        if($this->avatar==null)
            return env('DEFAULT_IMAGE_AVATAR');
        else
            return env('STORAGE_URL').'/uploads/users/'.$this->avatar;
    }

    public function scopeWithoutTimestamps()
    {
        $this->timestamps = false;
        return $this;
    }
    public function contacts(){
        return $this->hasMany(\App\Models\Contact::class);
    }
    public function articles(){
        return $this->hasMany(\App\Models\Article::class);
    }
    public function traffics(){
        return $this->hasMany(\App\Models\RateLimit::class);
    }
    public function report_errors(){
        return $this->hasMany(\App\Models\ReportError::class);
    }

    public function warning(){
        return $this->hasMany(\App\Models\Warnings::class);
    }


}
