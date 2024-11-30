<?php

namespace App\Http\Controllers;

use App\Enums\ClassworkType;
use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
        $this->authorize('viewAny',[Classwork::class,$classroom]);

        $classworks=$classroom->classworks()
        ->with('topic')
        ->filter($request->query())
        ->latest()
        ->where(function($query){
            $query->whereHas('users',function($query){
                $query->where('id','=',Auth::id());
            })->orWhereHas('user',function($query){
                $query->where('id','=',Auth::id()); // في حال بدي بس الكلاس ورك يلي انشأها التيتشر هيا بس تظهر 
            });
        })
        // ->where(function($query){

        //     $query->whereRaw('EXISTS(SELECT 1 FROM classwork_user
        //     WHERE classwork_user.classwork_id = classworks.id 
        //     AND classwork_user.user_id = ?
        //     )',[
        //    Auth::id()]);

        //    $query->orWhereRaw('EXISTS (SELECT 1 FROM classroom_user 
        //     WHERE classroom_user.classroom_id = classworks.classroom_id 
        //     AND classroom_user.user_id = ?
        //     AND classroom_user.role = ? )', [Auth::id() , 'teacher']);

        // })
     
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
        // $response = Gate::inspect('classworks.create',[$classroom]);
        // if(! $response->allowed()){
        //     abort(403,$response->message() ?? '');
        // }
        $this->authorize('create',[Classwork::class,$classroom]);

        // Gate::authorize('classworks.create',[$classroom]);

        // if(! Gate::allows('classworks.create',[$classroom])){
        //     abort(403);
        // }


        $type = $this->gettype($request);

        $classwork=new Classwork();



        return view('classworks.create',compact('classroom','classwork','type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Classroom $classroom)
    {

        // if(Gate::denies('classworks.create',[$classroom])){
        //     abort(403);
        // }
        $this->authorize('create',[Classwork::class,$classroom]);
       

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
            'type'=>ClassworkType::from($type)->value,
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

       
  

        return redirect()->route('classrooms.classworks.index',$classroom->id)->with('success',__('Classwork created!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom,Classwork $classwork)
    {
        //
        // Gate::authorize('classworks.view',[$classwork]);

        $this->authorize('view',$classwork);
        
        $submissions =Auth::user()->submissions()->where('classwork_id',$classwork->id)->get();
      
        return view('classworks.show',compact('classroom','classwork','submissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,Classroom $classroom,Classwork $classwork)
    {
        //
        $this->authorize('update',$classwork);

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
        $this->authorize('update',$classwork);

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

        return back()->with('success',__('Classwork updated!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom,Classwork $classwork)
    {
        //
        $this->authorize('delete',$classwork);
    }
}
