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

        // Query the database to find a user with the provided email
        $user = DB::table('admins')->where('email', $email)->first();
    //   print_r($user);
    //   exit;
        // Check if a user with the provided email exists
        if ($user) {
            // echo "hello2";
            //     exit;
            // Verify the password
            if ($user->password === $password) {
                // Authentication passed...
                // Redirect the user to the dashboard or any other page
                // echo "hello";
                // exit;
                return redirect()->intended('/dashboard');
            }
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
    }

    /**
     * Logout the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        // Perform logout actions here if needed
        // Redirect the user to the login page
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
        // Update the status column instead of active column
        $post->status = $request->status; // assuming the request contains 'status' parameter
        $post->save();
    
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
    
    public function getStatus($id)
    {
        $post = User::findOrFail($id);
        return response()->json(['status' => $post->active]);
    }

}