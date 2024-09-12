<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class LoginController extends Controller
{

    public function create()
    {
        return view('login');
    }

    public function store(Request $request){

        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);

        //رح افحص اذالباسوورد و الايميل موجود او لا
        
        // $user=User::where('email','=',$request->email)->first();
        //    هيك عملت عملية التحقق واللوجاين مع بعض و اختصرت كل اشيAuth::attempt انا لما استخدمت هادي 

        $result=Auth::attempt($request->only([
            'email','password']),
            $request->boolean('remember')
         );
         if($result){
            return redirect()->intended('/');
         }
        
        // if($user && Hash::check($request->password,$user->password)){
        //     //اذا كان نتيجة الفحص ترو ف هيك بيكون اليوسر authentecated
        //     // رح اخزن حالة هادا اليوسر authentecated انه 
        //     Auth::login($user);
        //     return redirect()->route('classrooms.index');
        
        // }
        return back()->withInput()->withErrors([
            'email'=>'Invalid credentials'
        ]);
        
    }
}
