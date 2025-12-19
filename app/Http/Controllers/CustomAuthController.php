<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;

class CustomAuthController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }
        return view('signin');
    }  
      

    public function customSignin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],
        [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',

        ]

    );
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            // Log login activity
            ActivityLogger::logLogin(Auth::user());
            
            return redirect()->intended('index')
                        ->withSuccess('Signed in');
        }
         
      
        return redirect("/")->withErrors([
            'email' => 'The provided credentials are incorrect.',
            'login' => 'Login failed! Please check your email and password.'
        ])->withInput($request->only('email'));
    }
    public function registration()
    {
        return view('register');
    }
      

    public function customRegister(Request $request)
    {  
        $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirmpassword' => 'required|min:6',
        ],
         [
            'name.required' => 'Userame is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'confirmpassword.required' => 'Confirm Password is required',

        ]
    );
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("/")->withSuccess('You have signed-in');
    }


    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'confirmpassword' => Hash::make($data['confirmpassword'])
      ]);
    }    
    

    public function dashboard()
    {
        if(Auth::check()){
            return view('index');
        }
  
        return redirect("/")->withSuccess('You are not allowed to access');
    }
    

    public function signOut() {
        // Log logout activity before logging out
        if (Auth::check()) {
            ActivityLogger::logLogout(Auth::user());
        }
        
        Session::flush();
        Auth::logout();
  
        return redirect('/');
    }
}
