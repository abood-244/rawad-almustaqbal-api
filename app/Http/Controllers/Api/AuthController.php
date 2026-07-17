<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Resources\AuthResource;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $authService)
    {
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return $this->error('بيانات الدخول غير صحيحة', null, 401);
        }

        return $this->success([
            'user' => AuthResource::make($result['user']),
            'token' => $result['token'],
        ], 'تم تسجيل الدخول بنجاح');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        
        return $this->success(null, 'تم تسجيل الخروج بنجاح');
    }
}
