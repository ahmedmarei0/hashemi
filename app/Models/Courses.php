<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;
    protected $guarded=['id','created_at','updated_at'];
    public function lessons(){
        return $this->hasMany(\App\Models\Lessions::class,'course_id');
    }

    public function subject(){
        return $this->belongsTo(\App\Models\Subjects::class, 'subject_id', 'id');
    }
    public function added_by(){

        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
