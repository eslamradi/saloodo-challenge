<?php

namespace App\Http\Controllers;

use App\Helpers\UnifiedJsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    protected $guard;

    public function __construct()
    {
        $this->middleware("auth:" . $this->guard(), ['except' => ['login', 'register']]);
    }

    protected function guard()
    {
        return request()->route('guard') ?? null;
    }

    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        $token = Auth::guard($this->guard())->attempt($credentials);
        if (!$token) {
            return UnifiedJsonResponse::error([], __('Unauthorized'), 400);
        }

        $user = Auth::guard($this->guard())->user();


        return UnifiedJsonResponse::success([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], __('Logged in Successfully'));
    }

    public function register(RegisterRequest $request, UserRepository $userRepository)
    {

        $user = $userRepository->create(array_merge($request->validated(), ['role' => $this->guard()]));

        $token = Auth::guard($this->guard())->login($user);

        return UnifiedJsonResponse::success([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], __('User created successfully'));
    }

    public function logout()
    {
        Auth::guard($this->guard())->logout();
        return UnifiedJsonResponse::success([], __('Successfully logged out'));
    }

    public function me()
    {
        return UnifiedJsonResponse::success(['user' => Auth::guard($this->guard())->user()]);
    }

    public function refresh()
    {
        return UnifiedJsonResponse::success([
            'user' => Auth::guard($this->guard())->user(),
            'authorization' => [
                'token' => Auth::guard($this->guard())->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
