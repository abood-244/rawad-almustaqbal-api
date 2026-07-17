<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\AuthResource;
use App\Traits\ApiResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function __construct(private UserService $userService)
    {
    }

    /**
     * Get current user profile.
     */
    public function show(Request $request)
    {
        $profile = $this->userService->getProfile($request->user());
        return $this->success(AuthResource::make($profile), 'تم جلب الملف الشخصي بنجاح');
    }

    /**
     * Update current user profile (name, email).
     */
    public function update(UpdateProfileRequest $request)
    {
        $profile = $this->userService->updateProfile($request->user(), $request->validated());
        return $this->success(AuthResource::make($profile), 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * Change user password.
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $success = $this->userService->changePassword(
            $request->user(),
            $request->validated('current_password'),
            $request->validated('new_password')
        );

        if (!$success) {
            return $this->error('كلمة المرور الحالية غير صحيحة', null, 400);
        }

        return $this->success(null, 'تم تغيير كلمة المرور بنجاح');
    }
}
