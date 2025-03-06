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
        // Check if the user is active
        if (!$user->is_active) {
            return back()->with('error', 'Your account is disabled.');
        }

        // Set session for login
        Session::put('admin_logged_in', true);
        Session::put('user_role', $user->role);
        Session::put('user_id', $user->id);
        Session::save();

        // Redirect based on role
        if ($user->role === 'Admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('products.index');
        }
    }

    return back()->with('error', 'Invalid credentials');
}

public function logout()
{
    Session::forget('admin_logged_in');
    return redirect('/login')->with('success', 'You have been logged out.');
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

        DB::table('users')->where('id', Session::get('user_id'))->update([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'updated_at' => now()
        ]);

        return redirect('/admin')->with('success', 'Credentials updated successfully.');
    }

    public function dashboard()
    {
        if (!Session::has('user_id') || Session::get('user_role') !== 'Admin') {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }
    
        return view('admin.dashboard');
    }




public function showCreateEmployeeForm()
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }
    
    return view('admin.add_employee');
}

public function storeEmployee(Request $request)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'phone' => 'required|unique:users',
    ]);

    // Generate username
    $username = strtolower($request->first_name . '.' . $request->last_name);
    
    // Generate password from first and last name
    $password = strtolower($request->first_name . $request->last_name);

    DB::table('users')->insert([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'phone' => $request->phone,
        'username' => $username,
        'password' => Hash::make($password),
        'role' => 'Employee',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.dashboard')->with('success', "Employee added. Username: $username, Password: $password");
}

public function manageEmployees()
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    $employees = DB::table('users')->where('role', 'Employee')->get();
    return view('admin.employees', compact('employees'));
}

public function updateEmployee(Request $request, $id)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    $request->validate([
        'username' => 'required|unique:users,username,' . $id,
        'phone' => 'required|unique:users,phone,' . $id,
        'password' => 'nullable|min:4',
    ]);

    $updateData = [
        'username' => $request->username,
        'phone' => $request->phone,
    ];

    if ($request->password) {
        $updateData['password'] = Hash::make($request->password);
    }

    DB::table('users')->where('id', $id)->update($updateData);

    return redirect()->route('admin.employees')->with('success', 'Employee updated successfully.');
}
public function toggleEmployeeStatus($id)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    $employee = DB::table('users')->where('id', $id)->first();
    if (!$employee) {
        return redirect()->back()->with('error', 'Employee not found.');
    }

    DB::table('users')->where('id', $id)->update([
        'is_active' => !$employee->is_active,
    ]);

    return redirect()->route('admin.employees')->with('success', 'Employee status updated.');
}

}
