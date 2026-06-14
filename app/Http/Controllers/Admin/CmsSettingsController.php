<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Settings\SettingsService;
use App\Http\Requests\Admin\SettingRequest;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService
    ) {}

    /**
     * Display settings page
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings' => Setting::orderBy('group')->orderBy('key')->get(),
        ]);
    }

    /**
     * Get settings by group
     */
    public function getByGroup(string $group): JsonResponse
    {
        $settings = Setting::where('group', $group)
            ->orderBy('key')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Store a new setting
     */
    public function store(SettingRequest $request): JsonResponse
    {
        $setting = Setting::create($request->validated());

        $this->settingsService->clearCache($setting->key);

        return response()->json([
            'success' => true,
            'message' => 'Setting created successfully',
            'data' => $setting,
        ]);
    }

    /**
     * Update an existing setting
     */
    public function update(SettingRequest $request, string $id): JsonResponse
    {
        $setting = Setting::findOrFail($id);
        $setting->update($request->validated());

        $this->settingsService->clearCache($setting->key);

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully',
            'data' => $setting,
        ]);
    }

    /**
     * Delete a setting
     */
    public function destroy(string $id): JsonResponse
    {
        $setting = Setting::findOrFail($id);
        $key = $setting->key;
        
        $setting->delete();

        $this->settingsService->clearCache($key);

        return response()->json([
            'success' => true,
            'message' => 'Setting deleted successfully',
        ]);
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate(SettingRequest $request): JsonResponse
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $settingData) {
            $setting = Setting::where('key', $settingData['key'])->first();
            
            if ($setting) {
                $setting->update($settingData);
                $this->settingsService->clearCache($setting->key);
            } else {
                $setting = Setting::create($settingData);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
        ]);
    }

    /**
     * Get all settings groups
     */
    public function groups(): JsonResponse
    {
        $groups = Setting::select('group')
            ->distinct()
            ->pluck('group');

        return response()->json([
            'success' => true,
            'data' => $groups,
        ]);
    }
}
