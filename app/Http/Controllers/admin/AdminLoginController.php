<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function index()
    {
        // Redirect if already authenticated as admin
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }
    
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if ($validator->passes()) {
            // CRITICAL: Logout from web guard first to prevent conflicts
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }
            
            // Clear all sessions to ensure clean state
            $request->session()->flush();
            $request->session()->regenerate();
            
            // Attempt admin authentication
            if (Auth::guard('admin')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ], $request->get('remember'))) {
                
                $admin = Auth::guard('admin')->user();
                
                if ($admin->role == 2) {
                    // Regenerate session after successful login
                    $request->session()->regenerate();
                    
                    return redirect()->route('admin.dashboard');
                } else {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')
                        ->with('error', 'You are not authorized to access admin panel');
                }
            } else {
                return redirect()->route('admin.login')
                    ->with('error', 'Either Email/Password is incorrect!');
            }
        } else {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
    
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully');
    }
}