<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Import DB facade
use App\Models\User;


class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $user = DB::table('admins')->where('email', $email)->first();
        if ($user) {
            if ($user->password === $password) {
                return redirect()->intended('/dashboard');
            }
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
    }

    /**
     * Logout the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        return redirect('/login');
    }


    public function dashboard()
    {
        $posts = User::all();
        return view('auth.dashboard', ['posts' => $posts]);
    }

    public function updatestatus(Request $request, $id)
    {
        $post = User::findOrFail($id);
        $post->status = $request->status; 
        $post->save();
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
    
    public function getStatus($id)
    {
        $post = User::findOrFail($id);
        return response()->json(['status' => $post->active]);
    }
}