<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClassworkController extends Controller
{

    protected function gettype(Request $request){

        
        $type=$request->query('type');
        $allowed_types=[
            Classwork::TYPE_ASSIGNEMENT,
            Classwork::TYPE_MATERIAL,
            Classwork::TYPE_QUESTION
        ];
        if(!in_array($type,$allowed_types)){
            $type=Classwork::TYPE_ASSIGNEMENT ;
        }
        return $type;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,Classroom $classroom)
    {
        $classworks=$classroom->classworks()->with('topic')
        ->filter($request->query())
        ->latest()
        ->paginate(5);

        return view('classworks.index',[ 'classroom'=>$classroom,
        'classworks'=>$classworks,
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,Classroom $classroom)
    {
       
        $type = $this->gettype($request);
        $classwork=new Classwork();

        return view('classworks.create',compact('classroom','classwork','type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Classroom $classroom)
    {
       

        $type = $this->gettype($request);

        $request->validate([
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string'],
            'topic_id'=>['nullable','int','exists:topics,id'],
            'options.grade'=>[Rule::requiredIf(fn()=>$type=='assignement'),'numeric','min:0'],
            'options.due'=>['nullable','date','after:published_at'],

        ]);

        $request->merge([
            'user_id'=>Auth::id(),
            // 'classroom_id'=>$classroom->id رح ياخدها من الريلاشن
            'type'=>$type,
        ]);

        // dd($request->all());

        try{
        DB::transaction(function() use($classroom,$request){
    
    
            $classwork=$classroom->classworks()->create($request->all());


            $classwork->users()->attach($request->input('students'));
        });
    }catch(QueryException $e){
        return back()->with('error',$e->getMessage());
    }

       
  

        return redirect()->route('classrooms.classworks.index',$classroom->id)->with('success','Classwork created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom,Classwork $classwork)
    {
        //
        $submissions =Auth::user()->submissions()->where('classwork_id',$classwork->id)->get();
      
        return view('classworks.show',compact('classroom','classwork','submissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,Classroom $classroom,Classwork $classwork)
    {
        //
        $type = $this->gettype($request);

        $assigned= $classwork->users()->pluck('id')->toArray();

        return view('classworks.edit',compact('classroom','classwork','type','assigned'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Classroom $classroom, Classwork $classwork)
    {
        //
        $type= $classwork->type;
        
        $request->validate([
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string'],
            'topic_id'=>['nullable','int','exists:topics,id'],
            'options.grade'=>[Rule::requiredIf(fn()=>$type=='assignement'),'numeric','min:0'],
            'options.due'=>['nullable','date','after:published_at'],

        ]);
        $classwork->update($request->all());
        $classwork->users()->sync($request->input('students'));

        return back()->with('success','Classwork updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom,Classwork $classwork)
    {
        //
    }
}
