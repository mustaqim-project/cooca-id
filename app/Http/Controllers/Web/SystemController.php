<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class SystemController extends Controller
{
    /**
     * Clear and optimize application cache.
     *
     * @return string
     */
    public function clearAppCache()
    {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');
        Artisan::call('optimize');

        return 'View, Cache, Route, and Config cleared successfully!';
    }
}
