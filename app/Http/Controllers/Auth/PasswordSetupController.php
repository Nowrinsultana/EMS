<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordSetupController extends Controller
{
    public function show(string $token): View|RedirectResponse
    {
        $user = User::where('setup_token', $token)->first();

        if (! $user) {
            abort(404, 'Invalid or expired setup link.');
        }

        return view('auth.setup-password', compact('token'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('setup_token', $data['token'])->first();

        if (! $user) {
            abort(404, 'Invalid or expired setup link.');
        }

        $user->update([
            'password' => Hash::make($data['password']),
            'setup_token' => null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
