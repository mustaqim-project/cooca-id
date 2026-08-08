@extends('layouts.admin')

@section('title', 'Blocked IP Addresses — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Blocked IPs</span>
        </div>
        <h1 class="page-title">IP Firewall & Blocklist</h1>
        <p class="page-subtitle">Restrict abusive, botnet, or suspicious IP addresses from accessing the SaaS platform.</p>
    </div>
</div>

<div class="grid-31">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Currently Blocked IP Addresses</div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Reason</th>
                            <th>Blocked Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blockedIps ?? [] as $ip)
                            <tr>
                                <td><code class="text-danger font-bold">{{ $ip->ip_address }}</code></td>
                                <td class="text-xs">{{ $ip->reason ?? 'Suspicious activity' }}</td>
                                <td class="text-xs text-muted">{{ optional($ip->created_at)->format('d M Y, H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.blocked-ips.destroy', $ip->id) }}" method="POST" onsubmit="return confirm('Unblock this IP?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-success">Unblock</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted" style="padding: 30px;">No IP addresses blocked.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Block New IP</div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.blocked-ips.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">IP Address *</label>
                    <input type="text" name="ip_address" class="form-input" placeholder="192.168.1.1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason</label>
                    <input type="text" name="reason" class="form-input" placeholder="Brute force login attempts">
                </div>
                <button type="submit" class="btn btn-danger w-full mt-4">🚫 Block IP Address</button>
            </form>
        </div>
    </div>
</div>
@endsection
