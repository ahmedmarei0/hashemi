<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    use HasFactory;
    protected $guarded=['id','created_at','updated_at'];
    public function courses(){
        return $this->hasMany(\App\Models\Courses::class,'subject_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_subjects', 'subject_id', 'user_id')
                    ->where('state', 'active');
    }

    public function added_by(){

        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
