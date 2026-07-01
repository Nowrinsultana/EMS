<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'phone_number' => $request->phone_number,
            'passport_number' => $request->passport_number,
            'staff_id' => $request->staff_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($user->department_id) {
            return redirect()->intended(route('panel.index', ['dptid' => $user->department_id]));
        }

        return redirect()->intended('/dashboard');
    }
}
