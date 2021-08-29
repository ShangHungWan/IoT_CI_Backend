<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['required', 'boolean'],
        ]);

        $credentials = Arr::only($validated, [
            'email',
            'password',
        ]);

        if (Auth::attempt($credentials, $validated['remember'])) {
            $request->session()->regenerate();

            return response([
                'message' => 'success',
            ], 200);
        }

        return response([
            'message' => 'failed',
        ], 400);
    }

    public function register(Request $request)
    {
        $attrributes = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        return $this->service->create($attrributes);
    }

    public function me()
    {
        return Auth::user();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response([
            'message' => 'success',
        ], 200);
    }
}
