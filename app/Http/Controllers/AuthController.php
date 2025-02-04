<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $user = DB::table('users')->where('username', $request->username)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        // Set session for admin login
        Session::put('admin_logged_in', true);
        Session::save();

        // Redirect to admin dashboard instead of POS
        return redirect()->route('admin.dashboard');
    }

    return back()->with('error', 'Invalid credentials');
}


    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect('/login');
    }

    public function showUpdateCredentials()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        return view('admin.update_credentials');
    }

    public function updateCredentials(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'username' => 'required',
            'password' => 'required|min:4',
        ]);

        DB::table('users')->where('username', 'admin')->update([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'updated_at' => now()
        ]);

        return redirect('/admin')->with('success', 'Credentials updated successfully.');
    }

    public function dashboard()
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    return view('admin.dashboard'); // Create this Blade file
}

}
