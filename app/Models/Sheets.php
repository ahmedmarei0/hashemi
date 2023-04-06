<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sheets extends Model
{
    use HasFactory;
    protected $guarded=['id','created_at','updated_at'];

    public function added_by(){
        return $this->belongsTo(\App\Models\User::class, 'id', 'user_id');
    }

    public function lesson(){
        return $this->belongsTo(\App\Models\Lessions::class);
    }
    // public function getFileAttribute($value =null)
    // {
    //     if($value !==null)
    //     return env("APP_URL").env("STORAGE_URL")."/uploads/sheets/".$value;
    // }
}
