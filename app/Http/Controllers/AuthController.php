<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    
    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

public function register(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
      'email' => [
    'required',
    'email:rfc,dns',
    'unique:users,email',
    'regex:/^[\w.%+-]+@[\w.-]+\.[a-zA-Z]{2,6}$/'
],

        'password' => [
            'required',
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
        ],
        'role'     => 'required|in:student,instructor',
    ], [
        'password.regex' => 'Password must be at least 8 characters and include an uppercase letter, a number, and a special character.',
    ]);

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => $request->role,
    ]);

    if ($request->wantsJson()) {
        return response()->json([
            'message' => 'Registration successful! Please login.',
            'redirect' => route('login')
        ]);
    }

    return redirect()->route('login')->with('success', 'youRegistration successful! Please login.');
}


    public function apiRegister(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
            ],
            'role'     => 'required|in:student,instructor',
        ], [
            'password.regex' => 'Password must be at least 8 characters and include an uppercase letter, a number, and a special character.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

public function login(Request $request)
{
   
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

   
    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    if (!preg_match($passwordPattern, $request->password)) {
        $formatError = 'Password must be at least 8 characters and include an uppercase letter, a number, and a special character.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $formatError], 422);
        }

        return back()->withErrors(['password' => $formatError])->withInput();
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        $errorMessage = 'Email or password is wrong';

        if ($request->expectsJson()) {
            return response()->json(['message' => $errorMessage], 401);
        }

        return back()->withErrors(['login_error' => $errorMessage])->withInput();
    }

   
    Auth::login($user);
    $request->session()->regenerate();


    if (!preg_match($passwordPattern, $request->password)) {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'weak_password',
                'message' => 'Your password is too weak. Please change it to meet the new policy.',
                'redirect' => route('password.change', ['email' => $user->email]),
            ]);
        }

        return redirect()
            ->route('password.change', ['email' => $user->email])
            ->with('warning', 'Your password is too weak. Please change it.');
    }


    if ($request->expectsJson()) {
        return response()->json([
            'user'  => $user,
            'token' => null,
        ]);
    }

   
    return redirect()->route(
        $user->role === 'instructor'
            ? 'instructor.dashboard'
            : 'student.courses.index'
    );
}




 
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password is incorrect'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user'    => $user,
            'token'   => $token,
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

  
    public function apiLogout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out']);
        }

        return response()->json(['message' => 'No authenticated user'], 401);
    }
}
