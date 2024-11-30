<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $casts = [
        'birthday'=>'datetime',
    ];

    // belongTo use with (one to one relation),(one to many relation)
    public function user(){
        return $this->belongTo(User::class,'user_id','id');
    }

}
