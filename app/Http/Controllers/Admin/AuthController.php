<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm(): \Illuminate\View\View
    {
        return view('admin.login');
    }

    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $admin = Admin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            session(['admin_id' => $admin->id]);
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        session()->forget('admin_id');
        return redirect()->route('admin.login');
    }
} 