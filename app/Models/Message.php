<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $guarded=['id','created_at','updated_at'];
    public function student(){
        return $this->hasMany(\App\Models\User::class, 'student_id');
    }
    public function support(){
        return $this->hasMany(\App\Models\User::class, 'support_id');
    }
}
