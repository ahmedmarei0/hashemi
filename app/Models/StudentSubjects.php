<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubjects extends Model
{
    use HasFactory;
    protected $guarded=['id','created_at','updated_at'];
    protected $fillabel=[
        'user_id',
        'subject_id',
        'state',
        'expired_date',
    ];

    public function added_by(){

        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
    public function students_subject(){

        return $this->belongsTo(\App\Models\User::class,  'user_id', 'id');
    }
}
