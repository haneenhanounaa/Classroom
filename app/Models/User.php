<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function setEmailAttribute($value){

            $this->attributes['email']=ucfirst( ($value));
    }
    //

    //many to many Relationship 
    public function classrooms(){
        return $this->belongsToMany(
            Classroom::class,    //Related model
        'classroom_user',  //pivot table
        'user_id',   //FK for current model in the pivot table
        'classroom_id',       //FK for related model in the pivot table
        'id',           //PK for current model
        'id'           //PK for related model
    )->withPivot('role','created_at')
    // ->as('join')
    ;
    }
    //one to many (userOwner)
    public function createdClassrooms(){
        return $this->hasMany(Classroom::class,'user_id');
    }

    public function classworks(){
    
     return $this->belongsToMany(Classwork::class)->withPivot(['grade','submitted_at','status','created_at'])->using(ClassworkUser::class);
       
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function submissions(){
        return $this->hasMany(Submission::class);
    }
}
