<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlockedIpController extends Controller
{
    /**
     * Display a listing of the blocked IPs.
     */
    public function index()
    {
        $blockedIps = BlockedIp::latest()->paginate(15);
        return view('admin.blocked-ips.index', compact('blockedIps'));
    }

    /**
     * Store a newly created blocked IP in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:blocked_ips,ip_address',
            'reason' => 'nullable|string|max:255',
            'blocked_until' => 'nullable|date|after:now',
        ]);

        BlockedIp::create($request->only('ip_address', 'reason', 'blocked_until'));
        
        Cache::forget("blocked_ip_{$request->ip_address}");

        return redirect()->route('admin.blocked-ips.index')->with('success', 'IP Address has been blocked.');
    }

    /**
     * Remove the specified blocked IP from storage.
     */
    public function destroy(BlockedIp $blockedIp)
    {
        $ip = $blockedIp->ip_address;
        $blockedIp->delete();
        
        Cache::forget("blocked_ip_{$ip}");

        return redirect()->route('admin.blocked-ips.index')->with('success', 'IP Address block has been removed.');
    }
}
