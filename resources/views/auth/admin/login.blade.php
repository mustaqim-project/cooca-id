@extends('layouts.guest')
@section('content')
<main class="auth-layout" style="margin-top: var(--navbar-height); display: grid; grid-template-columns: 1fr 1fr; min-height: calc(100vh - var(--navbar-height));">
    <!-- LEFT VISUAL PANEL -->
    <div class="auth-left auth-panel" style="background: linear-gradient(160deg, #020617 0%, #0F172A 40%, #1E3A5F 80%, #020617 100%); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 48px 40px;">
        <div class="orb" style="width:500px;height:500px;background:#2563EB;top:-150px;right:-100px;position:absolute;border-radius:50%;filter:blur(80px);opacity:.15;pointer-events:none;"></div>
        <div class="orb" style="width:300px;height:300px;background:#38BDF8;bottom:-80px;left:-60px;position:absolute;border-radius:50%;filter:blur(80px);opacity:.15;pointer-events:none;"></div>
        <div class="grid-bg" style="position:absolute;inset:0;background-image:linear-gradient(rgba(56,189,248,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(56,189,248,.04) 1px,transparent 1px);background-size:60px 60px;"></div>
        <div class="left-content" style="position:relative;z-index:2;max-width:420px;text-align:center;">
            <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                <div class="logo-icon" style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;font-weight:800;">C</div>
                <span class="brand-name" style="font-size:1.8rem;font-weight:800;">{{ setting('site_name', 'COOCA') }}</span>
            </div>
            <h2 style="font-size:2rem;font-weight:800;line-height:1.2;margin-bottom:16px;letter-spacing:-0.02em;">System Management & <span class="text-gradient">Control Console.</span></h2>
            <p style="font-size:.95rem;color:rgba(248,250,252,.6);margin-top:12px;">Authorized access only. Manage tenants, configurations, and core infrastructure.</p>
            <div class="trust-items" style="display:flex;flex-direction:column;gap:12px;margin-top:36px;text-align:left;">
                <div class="trust-item" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:rgba(255,255,255,.04);border:1px solid rgba(56,189,248,.1);border-radius:12px;">
                    <div class="trust-icon" style="width:36px;height:36px;border-radius:10px;background:rgba(56,189,248,.1);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:1rem;flex-shrink:0;"><i class="bi bi-shield-lock-fill"></i></div>
                    <div class="trust-text" style="font-size:.85rem;color:rgba(248,250,252,.7);"><strong style="color:#F8FAFC;">High Security</strong> — Full audit logging enabled on all administrative actions.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-right auth-panel" style="background:var(--bg); display:flex; align-items:center; justify-content:center; padding:48px 40px;">
        <div class="form-panel" style="width:100%; max-width:420px;">
            <!-- Mobile only brand -->
            <div class="d-flex align-items-center gap-3 d-md-none mb-4">
                <div class="logo-icon" style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;font-weight:800;">C</div>
                <span class="brand-name" style="font-size:1.8rem;font-weight:800;">{{ setting('site_name', 'COOCA') }}</span>
            </div>

            <div class="form-title" style="font-size:1.8rem;font-weight:800;margin-bottom:6px;letter-spacing:-0.02em;">Admin Console</div>
            <p class="form-subtitle" style="font-size:.92rem;margin-bottom:32px;">Please enter your credentials to continue.</p>

            <!-- ERROR -->
            @if ($errors->any())
            <div class="error-msg" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:12px 16px;font-size:.85rem;color:#EF4444;margin-bottom:16px;display:block;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <!-- FORM -->
            <form action="{{ route('admin.login.submit') }}" method="POST" id="loginForm">
                @csrf
                <div class="input-wrap" style="position:relative;margin-bottom:16px;">
                    <label class="input-label" style="font-size:.82rem;font-weight:600;margin-bottom:8px;display:block;color:var(--text);">Email Address</label>
                    <div style="position:relative;">
                        <i class="bi bi-envelope input-icon" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:1rem;pointer-events:none;"></i>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-field" style="width:100%;padding:14px 16px 14px 46px;border-radius:12px;border:1px solid var(--border);background:var(--card-alt);color:var(--text);font-family:var(--font);font-size:.95rem;outline:none;transition:all var(--transition);" placeholder="admin@cooca.id" required autocomplete="email">
                    </div>
                </div>
                <div class="input-wrap" style="position:relative;margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <label class="input-label" style="font-size:.82rem;font-weight:600;margin-bottom:8px;display:block;color:var(--text);">Password</label>
                    </div>
                    <div style="position:relative;">
                        <i class="bi bi-lock input-icon" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:1rem;pointer-events:none;"></i>
                        <input type="password" name="password" class="input-field" style="width:100%;padding:14px 16px 14px 46px;border-radius:12px;border:1px solid var(--border);background:var(--card-alt);color:var(--text);font-family:var(--font);font-size:.95rem;outline:none;transition:all var(--transition);" placeholder="Your password" required autocomplete="current-password">
                    </div>
                </div>
                <div class="check-wrap" style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                    <input type="checkbox" name="remember" id="rememberMe" style="width:18px;height:18px;accent-color:var(--primary);border-radius:4px;cursor:pointer;flex-shrink:0;">
                    <label for="rememberMe" style="font-size:.85rem;color:var(--text-muted);cursor:pointer;">Keep me logged in</label>
                </div>
                <button type="submit" class="btn-cooca btn-cooca-primary" style="width:100%;padding:15px;font-size:1rem;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;gap:8px;border:none;cursor:pointer;font-weight:600;">
                    <span>Log In to Console</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <p style="text-align:center;font-size:.82rem;margin-top:28px;color:var(--text-muted);">
                By logging in, you agree to our <a href="{{ route('terms') }}">Terms of Service</a> and <a href="{{ route('privacy') }}">Privacy Policy</a>.
            </p>
        </div>
    </div>
</main>
@endsection
