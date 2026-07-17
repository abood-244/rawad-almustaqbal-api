<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SettingService;
use App\Http\Requests\UpdateSettingsRequest;
use App\Traits\ApiResponse;

class SettingController extends Controller
{
    use ApiResponse;

    public function __construct(private SettingService $settingService)
    {
    }

    public function index()
    {
        $settings = $this->settingService->getAllSettings();
        
        return $this->success($settings, 'تم جلب الإعدادات بنجاح');
    }

    public function update(UpdateSettingsRequest $request)
    {
        $data = $request->validated();
        
        $this->settingService->updateSettings($data);

        return $this->success(null, 'تم حفظ الإعدادات بنجاح');
    }
}
