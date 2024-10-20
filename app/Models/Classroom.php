<?php

namespace App\Models;

use App\Models\Scopes\UserClassroomScope;
use App\Observers\ClassroomObserver;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = ['name','section','subject','room','theme','cover_image_path','code','user_id'];

    protected static  function booted(){

        static::observe(ClassroomObserver::class);

        // static::addGlobalScope('user',function(Builder $query){
        //     $query->where('user_id','=',Auth::id());
        // });
        static::addGlobalScope(new UserClassroomScope);

        // the listener that return to define in ObServer

        // static::creating(function(Classroom $classroom){
        //         $classroom->code=Str::random(8);
        // });
        // static::deleted(function(Classroom $classroom){
        //         $classroom->status='deleted';
        //         $classroom->save();
        // });
        // static::restored(function(Classroom $classroom){
        //         $classroom->status='active';
        //         $classroom->save();
        // });


    }
    //1 to many Relationship
    public function classworks():HasMany{
        return $this->hasMany(Classwork::class,'classroom_id','id');
    }

    public function topics():HasMany{
        return $this->hasMany(Topic::class,'classroom_id','id');
    }

    //many to many Relationship
    public function users(){
        return $this->belongsToMany(
            User::class,    //Related model
        'classroom_user',  //pivot table
        'classroom_id',   //FK for current model in the pivot table
        'user_id',       //FK for related model in the pivot table
        'id',           //PK for current model
        'id'           //PK for related model
    )->withPivot('role','created_at')
    // ->as('join')
    ;
    }

    public function teatchers(){
        return $this->users()->wherePivot('role','=','teacher');
    }

    public function students(){
        return $this->users()->wherePivot('role','=','student');
    }
 
    //local scopes
    public function scopeActive(Builder $query)
    {
        $query->where('status','=','active');
    }

    public function scopeRecent(Builder $query){

        $query->orderBy('updated_at','DESC');   
    }
    public function scopeStatus(Builder $query ,$status){

        $query->where('status','=',$status);
    }

    // public function join($user_id,$role='student'){
    //    return DB::table('classroom_user')->insert([
    //         'classroom_id' => $this->id,
    //         'user_id' => $user_id,
    //         'role' => $role,
    //         'created_at' => now(),
    //     ]);
    // }

      public function join($user_id,$role='student'){

        $exist= $this->users()->where('id','=',$user_id)->exists();

        if($exist){
            throw new Exception('User already joined the classroom');
        }
        return $this->users()->attach($user_id,[
            'role'=>$role,
            'created_at'=>now(),
    
    ]);
       
    }





    public function getNameAttribute($value){
            return strtoupper($value);
    }
 
//     public function getCoverImagePathAttribute($value){
//         if($value){
//             return Storage::url($value);
// }
//         return('https://placehold.co/800x300'); 
//     }

    // $classroom->cover_imge_url

    public function getCoverImageUrlAttribute(){
        if($this->cover_image_path){
           return Storage::url($this->cover_image_path);
}
        return 'https://placehold.co/800x300';
    }
    
     public function getUrlAttribute(){
        return route('classrooms.show',$this->id);
     }

     public function createdClassrooms(){
        return $this->hasMany(Classroom::class,'user_id');
    }



     

}


// edite 01