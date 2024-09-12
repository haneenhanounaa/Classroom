<?php

namespace App\Observers;

use App\Models\Classroom;
use Illuminate\Support\Str;


class ClassroomObserver
{
    /**
     * Handle the Classroom "created" event.
     */
    public function creating(Classroom $classroom): void
    { 
        $classroom->code=Str::random(8);
    }

    /**
     * Handle the Classroom "updated" event.
     */
    public function updated(Classroom $classroom): void
    {
        //
    }

    /**
     * Handle the Classroom "deleted" event.
     */
    public function deleted(Classroom $classroom): void
    {
        $classroom->status='deleted';
        $classroom->save();  
      }

    /**
     * Handle the Classroom "restored" event.
     */
    public function restored(Classroom $classroom): void
    {
        $classroom->status='active';
        $classroom->save();
    }

    /**
     * Handle the Classroom "force deleted" event.
     */
    public function forceDeleted(Classroom $classroom): void
    {
        //
    }
}
