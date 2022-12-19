<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendants extends Model
{
    use HasFactory;
    protected $guarded=['id','created_at','updated_at'];

    public function lesson(){
        return $this->belongsTo(\App\Models\Lessions::class);
    }
    public function added_by(){

        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}
