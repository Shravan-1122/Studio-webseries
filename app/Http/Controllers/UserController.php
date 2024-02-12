<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usertables;
use App\Models\User;
use App\Models\Webseries;
use App\Models\Theme;

class UserController extends Controller
{
    public function index()
    {
        
        return view('register');
    }
    
   
    public function store(Request $request)
    {
        $storeData = $request->validate([
            'email' => 'required|max:255|unique:users',
            'name'=>'required',
            'password' => 'required|max:255',
        ]);
        $request->session()->put('email', $request->email);

        $user = User::create($storeData);
        return view('login');
      
    }
   
   
    
    public function index2()
    {
        return view('login')->withSuccess('Register successfully you can login now');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
        ]);     
    
        $user = User::where('email', $request->email)->first();  
    
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'The provided email or password is incorrect.']);
        }
    
        if (($request->password!= $user->password)) {
            return redirect()->back()->withErrors(['password' => 'The provided email or password is incorrect.']);
        }
    
        $request->session()->put('name', $user->name);
        return redirect("weblist")->withSuccess('Login successful.');
    }
  

    public function logout(Request $request)
{
    $request->session()->forget('name');
   
    $request->session()->flush();

    return redirect()->route('login')->withSuccess('Logout successful.');
}

}
