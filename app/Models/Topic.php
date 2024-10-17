<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable=['name','classroom_id','user_id'];

    public function classworks(){
        return $this->hasMany(Classwork::class,'topic_id','id');
    }
}
