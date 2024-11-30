<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use App\Http\Requests\ClassroomRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ClassroomsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index(Request $request)
    {

        $classrooms = Classroom::active()
            ->recent()
            ->orderBy('created_at', 'DESC')
            ->get(); // return Collection of classroom

        $success = session('success');
        //  Session::remove('success');



        return view('classrooms.index', compact('classrooms', 'success'));
    }

    public function create()
    {
        return view('classrooms.create', [
            'classroom' => new Classroom
        ]);
    }

    public function store(ClassroomRequest $request): RedirectResponse
    {


        $validated = $request->validated();
        //    dd($validated);
        // $class = Classroom::query()->create([

        // ]);
        //    $classroom = new Classroom();
        //    $classroom->name=$request->post('name');
        //    $classroom->section=$request->post('section');
        //    $classroom->subject=$request->post('subject');
        //    $classroom->room=$request->post('room');$classroom->code=Str::random(8);
        //    $classroom->save();//insert

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image'); //uploadedFile

            $path = $file->store('/covers', 'public');
            // $request->merge(['cover_image_path'=>$path]);
            $validated['cover_image_path'] = $path;
        }

        // $request->merge([
        //         'code'=>Str::random(8), وقفته لانه شلت الريكوست 
        // ]);
        // $request->all() قبل كنت مستخدمة هذا داخل الcreate بس فضل انه استخدم الvalidate

        // $validated['code'] = Str::random(8);
        $validated['user_id'] = Auth::id();

        DB::beginTransaction();

        try{
        $classroom = Classroom::create($validated);

        $classroom->join(Auth::id(),'teacher');


        DB::commit();
    }catch(QueryException $e){
        DB::rollBack();
        return back()
            ->with('error',$e->getMessage())
            ->withInput();
    }

        //PRG Post Redirect Get
        return redirect()->route('classrooms.index')->with('success', 'Classroom created');
    }

    public function show($id)
    {

        $classroom = Classroom::withTrashed()->findOrFail($id);
        // if(!$classroom){
        //     abort(404);
        // }اختصرتها فوق ب findOrFail

        $invitation_link=URL::signedRoute('classrooms.join',[
            'classroom'=>$classroom->id,
            'code'=>$classroom->code, 
        ]);

        return view::make('classrooms.show')
            ->with(['classroom' => $classroom,
        'invitation_link'=>$invitation_link,
    ]);
    }

    public function edit(Classroom $classroom)
    {

        // $classroom = Classroom::findOrFail($id);



        return view('classrooms.edit', [
            'classroom' => $classroom
        ]);
    }

    public function update(ClassroomRequest $request, Classroom $classroom)
    {

        // $classroom = Classroom::findOrFail($id);
        // $classroom->name=$request->post('name');

        $validated = $request->validated();



        if ($classroom->cover_image_path) {
            $imagePath = public_path('storage/' . $classroom->cover_image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image'); //uploadedFile

                $path = $file->store('/covers', 'public');
                // $request->merge(['cover_image_path'=>$path]);
                $validated['cover_image_path'] = $path;
            }
        }



        $classroom->update($validated);




        Session::flash('success', 'Classroom updated');
        Session::flash('error', 'Test for error message');

        return Redirect::route('classrooms.index')

            //  ->with('success','Classroom Updated')
            //  ->with('error','Classroom Updated')

        ;
    }

    public function destroy($id)
    {

        $classroom = Classroom::destroy($id);



        return Redirect::route('classrooms.index')->with('success', 'Classroom Delete');
    }
    public function trashed()
    {
        $classrooms = Classroom::onlyTrashed()->latest('deleted_at')->get();

        return view('classrooms.trashed', compact('classrooms'));
    }

    public function restore($id)
    {
        $classroom = Classroom::onlyTrashed()->findOrFail($id);
        $classroom->restore();

        return redirect()->route('classrooms.index')->with('success', "Classrooms ({$classroom->name})restored");
    }
    public function forceDelete($id)
    {
        $classroom = Classroom::withTrashed()->findOrFail($id);
        $classroom->forceDelete();

        return redirect()->route('classrooms.trashed')->with('success', "Classrooms ({$classroom->name})deleted forever");
    }
}
