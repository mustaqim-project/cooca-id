<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AiApiKey;
use App\Models\AiUsageCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AiUsageController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::user();

        $keys = AiApiKey::where('customer_id', $customer->getKey())->get();
        $cycles = AiUsageCycle::whereIn('license_id', $keys->pluck('license_id')->unique())
            ->where('cycle_start', '<=', now())
            ->where('cycle_end', '>=', now())
            ->get();

        return view('customer.ai.usage', compact('keys', 'cycles'));
    }
}
