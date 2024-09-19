<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class SessionsController extends Controller
{
    //
    public function create()
    {
        Log::info('Accediendo a la vista de login');
        return view('aut.login');
    }

    public function store(Request $request)
    {
        if(auth()->attempt(request(['name','email','Password']))== false){
            return back()->whithErrors(['message'=>'El email y la contraseña son incorrectos']);

        }
        else{

            if(auth()->user()->role =='admin'){
                return  redirect()->route('admin.index');
                
            }else{
                return  redirect()->to('/');
                
            }
        }
       auth()->login();
       
       
  
    }

    public function destroy (){
        auth () ->logout;
        return redirect()->to ('/');
    }

}