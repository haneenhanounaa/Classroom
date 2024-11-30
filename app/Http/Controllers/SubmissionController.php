<?php

namespace App\Http\Controllers;

use App\Models\Classwork;
use App\Models\ClassworkUser;
use App\Models\Submission;
use App\Rules\ForbiddenFile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Gate;


class SubmissionController extends Controller
{
    public function store(Request $request,Classwork $classwork){

        
            Gate::authorize('submissions.create',[$classwork]);

            $request->validate([
                'files'=>'required|array',
                'files.*'=>['file',new ForbiddenFile('application/x-msdownload','application/x-httpd-php')]
            ]);

            $assigned = $classwork->users()->where('id',Auth::id())->exists();
            if(!$assigned){
                abort(403);
            }


            DB::beginTransaction();
            try{

            $data=[];

            foreach ($request->file('files') as $file) {

                $data[]=[
                'classwork_id' => $classwork->id,
                'content' => $file->store("submissions/{$classwork->id}"),
                'type' => 'file',
               
                ];

            }
            $user = Auth::user();
            $user->submissions()->createMany($data);

                  
            

            ClassworkUser::where([
                'user_id' => Auth::id(),
                'classwork_id' => $classwork->id,
            ])->update([
                'status' => 'submitted',
                'submitted_at'=>now(),
            ]);
            DB::commit();
           
        
        }catch(Throwable $e){
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }

        return back()->with('success', 'Work Submitted');


    }

    public function file(Submission $submission){

        $user = Auth::user();

        // Check if the user is classroom teacher

        $isTeacher=$submission->classwork->classroom->teatchers()->where('id',$user->id)->exists();

        
        $isOwner = $submission->user_id == $user->id;

        if(!$isTeacher && !$isOwner){
          abort(403);
        }
        else dd($isTeacher);


        return response()->file(storage_path('app/' . $submission->content));

    }
}
