<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingCmsController extends Controller
{
    /**
     * Display the landing page CMS form.
     */
    public function index(Request $request)
    {
        $settings = [
            'site.logo' => Setting::get('site.logo'),
            'site.favicon' => Setting::get('site.favicon'),
            'site.preloader_text' => Setting::get('site.preloader_text', 'COOCA'),
            
            'home.hero.badge' => Setting::get('home.hero.badge'),
            'home.hero.title' => Setting::get('home.hero.title'),
            'home.hero.subtitle' => Setting::get('home.hero.subtitle'),
            'home.hero.description' => Setting::get('home.hero.description'),
            'home.hero.btn_primary' => Setting::get('home.hero.btn_primary'),
            'home.hero.btn_outline' => Setting::get('home.hero.btn_outline'),
            'home.stats.1.value' => Setting::get('home.stats.1.value'),
            'home.stats.1.label' => Setting::get('home.stats.1.label'),
            'home.stats.2.value' => Setting::get('home.stats.2.value'),
            'home.stats.2.label' => Setting::get('home.stats.2.label'),
        ];

        return view('admin.cms.landing.index', compact('settings'));
    }

    /**
     * Update the landing page settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'site_favicon' => ['nullable', 'image', 'mimes:ico,png,svg', 'max:1024'],
            'site_preloader_text' => ['nullable', 'string', 'max:50'],
            
            'home_hero_badge' => ['nullable', 'string', 'max:255'],
            'home_hero_title' => ['nullable', 'string', 'max:1000'],
            'home_hero_subtitle' => ['nullable', 'string', 'max:1000'],
            'home_hero_description' => ['nullable', 'string', 'max:1000'],
            'home_hero_btn_primary' => ['nullable', 'string', 'max:100'],
            'home_hero_btn_outline' => ['nullable', 'string', 'max:100'],
            'home_stats_1_value' => ['nullable', 'string', 'max:50'],
            'home_stats_1_label' => ['nullable', 'string', 'max:255'],
            'home_stats_2_value' => ['nullable', 'string', 'max:50'],
            'home_stats_2_label' => ['nullable', 'string', 'max:255'],
        ]);

        // Handle File Uploads
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->storePublicly('settings', 'public');
            Setting::set('site.logo', '/storage/' . $path, 'string');
        }

        if ($request->hasFile('site_favicon')) {
            $path = $request->file('site_favicon')->storePublicly('settings', 'public');
            Setting::set('site.favicon', '/storage/' . $path, 'string');
        }

        // Handle Text Fields
        $textFields = [
            'site_preloader_text' => 'site.preloader_text',
            'home_hero_badge' => 'home.hero.badge',
            'home_hero_title' => 'home.hero.title',
            'home_hero_subtitle' => 'home.hero.subtitle',
            'home_hero_description' => 'home.hero.description',
            'home_hero_btn_primary' => 'home.hero.btn_primary',
            'home_hero_btn_outline' => 'home.hero.btn_outline',
            'home_stats_1_value' => 'home.stats.1.value',
            'home_stats_1_label' => 'home.stats.1.label',
            'home_stats_2_value' => 'home.stats.2.value',
            'home_stats_2_label' => 'home.stats.2.label',
        ];

        foreach ($textFields as $inputKey => $settingKey) {
            if ($request->has($inputKey)) {
                Setting::set($settingKey, $request->input($inputKey), 'string');
            }
        }

        return back()->with('success', 'Landing page settings updated successfully.');
    }
}
