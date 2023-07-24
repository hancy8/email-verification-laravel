<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class RegisterController extends Controller
{
    // use RegistersUsers;

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, $user)
    {
        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function show()
    {
        throw new AuthorizationException('This action is unauthorized.');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        event(new Verified($request->user()));

        return redirect()->intended('/');
    }

}
