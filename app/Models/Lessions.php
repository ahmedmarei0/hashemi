<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessions extends Model
{
    use HasFactory;
    protected $table = 'lessons';
    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'description',
        'video',
    ];

    public function added_by(){
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
    public function sheets(){
        return $this->hasMany(\App\Models\Sheets::class, 'lesson_id', 'id');
    }

    public function course(){
        return $this->belongsTo(\App\Models\Courses::class,'course_id','id');
    }
    public function attachments(){
        return $this->hasMany(\App\Models\Attachments::class, 'lesson_id');
    }
    public function attendants(){
        return $this->hasMany(\App\Models\Attendants::class, 'lesson_id');
    }
    // public function video(){
    //     if($this->video !==null)
    //     return env("STORAGE_URL")."/uploads/course/".$this->video;
    // }
    // public function getVideoAttribute($value =null)
    // {
    //     if($value !==null)
    //         return env("APP_URL").env("STORAGE_URL")."/uploads/course/".$value;
    // }
}
