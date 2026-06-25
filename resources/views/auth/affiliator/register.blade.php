@extends('layouts.guest')

@section('title', 'Customer_register - ' . ($setting->company_name ?? config('app.name')))

@section('content')
<!-- ========== NAVBAR ========== -->


<!-- ========== REGISTRATION MAIN ========== -->
<main class="auth-layout">
    <!-- LEFT PANEL (value proposition) -->
        <div class="auth-left auth-panel">
        <div class="orb" style="width:500px;height:500px;background:#10B981;top:-150px;right:-100px;opacity:0.4;"></div>
        <div class="orb" style="width:300px;height:300px;background:#059669;bottom:-80px;left:-60px;opacity:0.4;"></div>
        <div class="grid-bg"></div>
        <div class="left-content">
            <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                <div class="logo-icon" style="background:linear-gradient(135deg, #10B981, #059669);">C</div>
                <span class="brand-name" style="font-size:1.8rem;font-weight:800;">COOCA Partners</span>
            </div>
            <h2>Join the Fastest Growing <span class="text-gradient" style="background:linear-gradient(135deg, #34D399, #10B981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">B2B SaaS</span> Affiliate Program.</h2>
            <p class="mt-3" style="font-size:.95rem;color:rgba(248,250,252,.7);">Turn your network into a recurring revenue stream. Free to join.</p>
            <div class="trust-items">
                <div class="trust-item">
                    <div class="trust-icon" style="background:rgba(16,185,129,0.1);color:#10B981;border:1px solid rgba(16,185,129,0.2);"><i class="bi bi-wallet2"></i></div>
                    <div><strong style="color:#F8FAFC;">High-Ticket Commissions</strong><div class="trust-text">Earn from every ERP module payment</div></div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon" style="background:rgba(16,185,129,0.1);color:#10B981;border:1px solid rgba(16,185,129,0.2);"><i class="bi bi-people-fill"></i></div>
                    <div><strong style="color:#F8FAFC;">Build Your Team</strong><div class="trust-text">Get 5% override from your sub-affiliates</div></div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon" style="background:rgba(16,185,129,0.1);color:#10B981;border:1px solid rgba(16,185,129,0.2);"><i class="bi bi-megaphone-fill"></i></div>
                    <div><strong style="color:#F8FAFC;">Marketing Assets</strong><div class="trust-text">Ready-to-use banners, videos, and copy</div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: MULTI-STEP FORM -->
    <div class="auth-right auth-panel">
        <div class="form-panel">
            <div class="d-md-none d-flex align-items-center gap-3 mb-4"><div class="logo-icon">C</div><span style="font-size:1.6rem;font-weight:800;">COOCA</span></div>
            <div class="progress-bar-c"><div class="progress-fill" id="progressFill" style="width:33.3%"></div></div>
            <div class="step-nav" id="stepNav"><div class="step-dot active" id="dot1"></div><div class="step-dot" id="dot2"></div><div class="step-dot" id="dot3"></div></div>

            <!-- ERROR SUMMARY -->
            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 12px; background: rgba(239,68,68,0.1); border: 1px solid var(--danger); color: #f87171; padding: 15px; margin-bottom: 20px;">
                    <ul style="margin-bottom: 0; padding-left: 20px; font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('affiliator.register.submit') }}" id="registerForm">
                @csrf
                <!-- STEP 1 -->
                <div class="step-page active" id="step1">
                    <div class="form-title">Create your account</div>
                    <p class="form-subtitle">Step 1 of 3 · Start your 30-day free trial. <a href="{{ route('affiliator.login') }}">Already have one?</a></p>
                    <button type="button" class="social-btn"><svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg> Sign up with Google</button>
                    <div class="divider"><span>or with email</span></div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Full Name</label>
                        <div class="position-relative">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" name="name" class="input-field" placeholder="Ahmad Kurniawan" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Work Email</label>
                        <div class="position-relative">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" name="email" class="input-field" id="regEmail" placeholder="you@company.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Password</label>
                        <div class="position-relative">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" name="password" class="input-field" id="regPassword" placeholder="Min. 8 characters" required>
                        </div>
                    </div>

                    <div class="input-wrap">
                        <label class="input-label">Confirm Password</label>
                        <div class="position-relative">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" name="password_confirmation" class="input-field" placeholder="Confirm your password" required>
                        </div>
                    </div>

                    <div class="check-wrap"><input type="checkbox" id="terms" required><label for="terms">I agree to COOCA's <a href="terms.html">Terms of Service</a> and <a href="privacy.html">Privacy Policy</a></label></div>
                    <button type="button" class="btn-submit" onclick="goStep(2)">Continue <i class="bi bi-arrow-right"></i></button>
                </div>

                <!-- STEP 2: BUSINESS INFO -->
                <div class="step-page" id="step2">
                    <div class="form-title">Tell us about yourself</div>
                    <p class="form-subtitle">Step 2 of 3 · Bank details for your commission payouts.</p>
                    
                    <div class="input-wrap">
                        <label class="input-label">Bank Name</label>
                        <div class="position-relative">
                            <i class="bi bi-building input-icon"></i>
                            <input type="text" name="bank_name" class="input-field" placeholder="e.g. BCA, Mandiri" value="{{ old('bank_name') }}">
                        </div>
                    </div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Parent Referral Code (Optional)</label>
                        <div class="position-relative">
                            <i class="bi bi-ticket-detailed input-icon"></i>
                            <input type="text" name="parent_referral_code" class="input-field" placeholder="Enter referral code if you have one" value="{{ old('parent_referral_code') }}">
                        </div>
                    </div>

                    <div class="input-wrap mt-3">
                        <label class="input-label">Bank Account Number</label>
                        <div class="position-relative">
                            <i class="bi bi-credit-card input-icon"></i>
                            <input type="text" name="bank_account" class="input-field" placeholder="1234567890" value="{{ old('bank_account') }}">
                        </div>
                    </div>
                    
                    <div style="display:flex;gap:12px;margin-top:30px;">
                        <button type="button" class="btn-cooca btn-cooca-outline" style="flex:1;" onclick="goStep(1)"><i class="bi bi-arrow-left"></i> Back</button>
                        <button type="button" class="btn-cooca btn-cooca-primary" style="flex:1;" onclick="goStep(3)">Continue <i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>

                <!-- STEP 3: CONFIRM & LAUNCH -->
                <div class="step-page" id="step3">
                    <div class="form-title">You're almost there!</div>
                    <p class="form-subtitle">Step 3 of 3 · Confirm your setup and launch your free trial.</p>
                    <div class="glass p-4 rounded-3 mb-4"><div class="fw-bold text-uppercase small mb-3">Your Setup Summary</div><div class="d-flex flex-column gap-2"><div class="d-flex justify-content-between"><span class="text-muted">Plan</span><span class="text-success fw-bold">Affiliator Partner</span></div><div class="d-flex justify-content-between"><span class="text-muted">Commissions</span><span>Unlimited potential</span></div><div class="d-flex justify-content-between"><span class="text-muted">Users</span><span>Unlimited</span></div><div class="d-flex justify-content-between"><span class="text-muted">Provisioning</span><span>~30 minutes</span></div><div class="d-flex justify-content-between"><span class="text-muted">Credit Card</span><span class="text-success">Not Required</span></div></div></div>
                    <div class="check-wrap"><input type="checkbox" id="newsletter2" checked><label for="newsletter2">Send me tips on getting the most out of COOCA</label></div>
                    <div style="display:flex;gap:12px;">
                        <button type="button" class="btn-cooca btn-cooca-outline" style="flex:1;" onclick="goStep(2)"><i class="bi bi-arrow-left"></i> Back</button>
                        <button type="submit" class="btn-cooca btn-cooca-success" id="launchBtn" style="flex:1;">
                            <span id="launchText">Join as Affiliator</span>
                            <i class="bi bi-rocket-takeoff-fill" id="launchIcon"></i>
                        </button>
                    </div>
                    <p class="text-center small mt-3">By signing up, you agree to our <a href="terms.html">Terms</a> and <a href="privacy.html">Privacy Policy</a>.</p>
                </div>
            </form>

            <p class="text-center mt-4">Already have an account? <a href="{{ route('affiliator.login') }}" class="fw-bold">Log in →</a></p>
        </div>
    </div>
</main>

<!-- ========== FOOTER ========== -->

@endsection

@push('styles')
<style>
:root {
            --bg: #020617;
            --card: #0F172A;
            --card-alt: #1E293B;
            --text: #F8FAFC;
            --text-muted: #94A3B8;
            --primary: #2563EB;
            --secondary: #1E40AF;
            --accent: #38BDF8;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --border: rgba(56, 189, 248, 0.12);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.6);
            --glass: rgba(15, 23, 42, 0.65);
            --glass-border: rgba(56, 189, 248, 0.14);
            --radius: 16px;
            --radius-sm: 10px;
            --radius-lg: 24px;
            --transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            --font: 'Inter', -apple-system, sans-serif;
            --navbar-height: 72px;
            --footer-bg: #0B1120;
        }
        [data-theme="light"] {
            --bg: #F8FAFC;
            --card: #FFFFFF;
            --card-alt: #F1F5F9;
            --text: #0F172A;
            --text-muted: #475569;
            --primary: #2563EB;
            --secondary: #7C3AED;
            --accent: #0EA5E9;
            --success: #10B981;
            --border: rgba(37, 99, 235, 0.12);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.1);
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(37, 99, 235, 0.1);
            --footer-bg: #E2E8F0;
        }

        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        html{scroll-behavior:smooth;overflow-x:hidden;}
        body{
            font-family:var(--font);
            background:var(--bg);
            color:var(--text);
            line-height:1.7;
            transition:background var(--transition),color var(--transition);
            overflow-x:hidden;
            -webkit-font-smoothing:antialiased;
            display:flex;
            flex-direction:column;
            min-height:100vh;
        }
        img{max-width:100%;height:auto;}
        a{color:var(--accent);text-decoration:none;transition:color var(--transition);}
        a:hover{color:var(--primary);}
        h1,h2,h3,h4,h5,h6{font-weight:700;line-height:1.2;letter-spacing:-0.02em;}
        p{color:var(--text-muted);}
        ::-webkit-scrollbar{width:6px;}
        ::-webkit-scrollbar-track{background:var(--bg);}
        ::-webkit-scrollbar-thumb{background:var(--primary);border-radius:3px;}
        .section-padding{padding:100px 0;}
        .glass{background:var(--glass);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid var(--glass-border);border-radius:var(--radius);}
        .text-gradient{background:linear-gradient(135deg,var(--accent),var(--primary),var(--secondary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
        .badge-glow{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:50px;font-size:0.75rem;font-weight:600;letter-spacing:0.05em;background:rgba(56,189,248,0.1);border:1px solid rgba(56,189,248,0.2);color:var(--accent);text-transform:uppercase;}
        .section-label{display:inline-flex;align-items:center;gap:8px;padding:8px 20px;border-radius:50px;font-size:0.8rem;font-weight:600;letter-spacing:0.08em;background:rgba(37,99,235,0.1);border:1px solid rgba(37,99,235,0.2);color:var(--primary);text-transform:uppercase;margin-bottom:16px;}
        .btn-cooca{display:inline-flex;align-items:center;gap:8px;padding:14px 32px;border-radius:12px;font-weight:600;font-size:0.95rem;border:none;cursor:pointer;transition:all var(--transition);position:relative;overflow:hidden;text-decoration:none;}
        .btn-cooca-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 4px 20px rgba(37,99,235,0.3);}
        .btn-cooca-primary:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(37,99,235,0.45);color:#fff;}
        .btn-cooca-success{background:linear-gradient(135deg,#10B981,#059669);color:#fff;box-shadow:0 4px 20px rgba(16,185,129,0.3);}
        .btn-cooca-success:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(16,185,129,0.45);color:#fff;}
        .btn-cooca-outline{background:transparent;color:var(--text);border:1px solid var(--border);}
        .btn-cooca-outline:hover{border-color:var(--accent);color:var(--accent);transform:translateY(-2px);}
        .btn-cooca-sm{padding:10px 22px;font-size:0.85rem;border-radius:10px;}
        .btn-cooca .btn-ripple{position:absolute;border-radius:50%;background:rgba(255,255,255,0.3);transform:scale(0);animation:ripple 0.6s linear;pointer-events:none;}
        @keyframes ripple{to{transform:scale(4);opacity:0;}}
        .card-3d{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:32px;transition:all var(--transition);position:relative;overflow:hidden;}
        @keyframes float{0%,100%{transform:translateY(0px);}50%{transform:translateY(-20px);}}
        @keyframes fade-in-scale{0%{opacity:0;transform:scale(0.8);}100%{opacity:1;transform:scale(1);}}
        .reveal{opacity:0;transform:translateY(40px);transition:opacity 0.8s cubic-bezier(0.4,0,0.2,1),transform 0.8s cubic-bezier(0.4,0,0.2,1);}
        .reveal.revealed{opacity:1;transform:translateY(0);}

        /* ========== NAVBAR (unified) ========== */
        .navbar-cooca{position:fixed;top:0;left:0;right:0;z-index:1050;padding:16px 0;transition:all var(--transition);background:transparent;}
        .navbar-cooca.scrolled{padding:10px 0;background:var(--glass);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid var(--glass-border);box-shadow:0 4px 30px rgba(0,0,0,0.1);}
        .navbar-brand-cooca{font-size:1.6rem;font-weight:800;letter-spacing:-0.03em;color:var(--text)!important;display:flex;align-items:center;gap:10px;}
        .navbar-brand-cooca .logo-icon{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;font-weight:800;}
        .nav-link-cooca{color:var(--text-muted)!important;font-weight:500;font-size:0.9rem;padding:8px 16px!important;transition:color var(--transition);position:relative;}
        .nav-link-cooca:hover,.nav-link-cooca.active{color:var(--accent)!important;}
        .nav-link-cooca::after{content:'';position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:0;height:2px;background:var(--accent);transition:width var(--transition);border-radius:1px;}
        .nav-link-cooca:hover::after{width:60%;}
        .theme-toggle{width:42px;height:42px;border-radius:12px;border:1px solid var(--border);background:var(--card);color:var(--text);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all var(--transition);font-size:1.1rem;}
        .theme-toggle:hover{border-color:var(--accent);color:var(--accent);transform:rotate(20deg);}
        .login-dropdown-wrapper{position:relative;}
        .login-dropdown-menu{position:absolute;top:calc(100% + 10px);right:0;min-width:190px;background:var(--card);border:1px solid var(--border);border-radius:var(--radius-sm);box-shadow:var(--shadow-lg);padding:8px 0;opacity:0;visibility:hidden;transform:translateY(-8px);transition:opacity 0.25s ease,transform 0.25s ease,visibility 0.25s ease;z-index:1060;}
        .login-dropdown-menu.show{opacity:1;visibility:visible;transform:translateY(0);}
        .dropdown-item-c{display:flex;align-items:center;gap:10px;padding:12px 20px;font-size:0.9rem;font-weight:500;color:var(--text);text-decoration:none;transition:all 0.2s ease;white-space:nowrap;}
        .dropdown-item-c:hover{background:rgba(56,189,248,0.08);color:var(--accent);}
        .whatsapp-float{position:fixed;bottom:28px;right:28px;z-index:999;width:56px;height:56px;border-radius:50%;background:#25D366;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.6rem;box-shadow:0 6px 24px rgba(37,211,102,0.35);transition:all var(--transition);text-decoration:none;}
        .whatsapp-float:hover{transform:scale(1.1);box-shadow:0 10px 32px rgba(37,211,102,0.5);color:#fff;}
        .whatsapp-float .pulse-ring{position:absolute;inset:-6px;border-radius:50%;border:2px solid #25D366;animation:pulse-ring 2s ease-out infinite;}
        @keyframes pulse-ring{0%{transform:scale(0.8);opacity:1;}100%{transform:scale(1.6);opacity:0;}}
        .offcanvas-cooca{background:var(--glass)!important;backdrop-filter:blur(30px);border-left:1px solid var(--glass-border);}
        .offcanvas-cooca .offcanvas-header{border-bottom:1px solid var(--border);}
        .offcanvas-cooca .btn-close{filter:invert(1);}
        [data-theme="light"] .offcanvas-cooca .btn-close{filter:none;}

        /* ========== AUTH LAYOUT (register) ========== */
        .auth-layout{flex:1;display:grid;grid-template-columns:1fr 1fr;margin-top:var(--navbar-height);min-height:calc(100vh - var(--navbar-height));}
        .auth-panel{display:flex;align-items:center;justify-content:center;padding:48px 40px;}
        .auth-left{background:linear-gradient(160deg,#020617 0%,#0F172A 40%,#1E3A5F 80%,#020617 100%);position:relative;overflow:hidden;}
        .auth-right{background:var(--bg);}
        .grid-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(56,189,248,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(56,189,248,.04) 1px,transparent 1px);background-size:60px 60px;}
        .orb{position:absolute;border-radius:50%;filter:blur(80px);opacity:.15;pointer-events:none;}
        .left-content{position:relative;z-index:2;max-width:420px;text-align:center;}
        .trust-items{display:flex;flex-direction:column;gap:12px;margin-top:36px;text-align:left;}
        .trust-item{display:flex;align-items:center;gap:12px;padding:14px 16px;background:rgba(255,255,255,.04);border:1px solid rgba(56,189,248,.1);border-radius:12px;backdrop-filter:blur(4px);}
        .trust-icon{width:36px;height:36px;border-radius:10px;background:rgba(56,189,248,.1);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:1rem;flex-shrink:0;}
        .form-panel{width:100%;max-width:460px;}
        .form-title{font-size:1.8rem;font-weight:800;margin-bottom:6px;letter-spacing:-0.02em;}
        .form-subtitle{font-size:.92rem;margin-bottom:32px;}
        .input-wrap{margin-bottom:16px;}
        .input-label{font-size:.82rem;font-weight:600;margin-bottom:8px;display:block;}
        .input-field{width:100%;padding:14px 16px 14px 46px;border-radius:12px;border:1px solid var(--border);background:var(--card-alt);color:var(--text);font-family:var(--font);font-size:.95rem;outline:none;transition:all var(--transition);}
        .input-field:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(56,189,248,.1);background:var(--card);}
        .input-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:1rem;pointer-events:none;}
        .input-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--text-muted);transition:color var(--transition);}
        .input-toggle:hover{color:var(--accent);}
        .input-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
        .industry-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;}
        .industry-card{padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);}
        .industry-card:hover{border-color:var(--accent);transform:translateY(-3px);background:rgba(56,189,248,.05);}
        .industry-card.selected{border-color:var(--primary);background:linear-gradient(135deg,rgba(37,99,235,.1),rgba(56,189,248,.05));box-shadow:0 0 0 2px rgba(37,99,235,.3);}
        .ic-icon{font-size:1.8rem;margin-bottom:8px;display:block;}
        .ic-label{font-size:.75rem;font-weight:600;color:var(--text-muted);}
        .pw-strength{margin-top:8px;}
        .pw-bars{display:flex;gap:4px;margin-bottom:4px;}
        .pw-bar{flex:1;height:4px;border-radius:2px;background:var(--border);transition:background 0.2s;}
        .pw-bar.weak{background:var(--danger);}
        .pw-bar.fair{background:var(--warning);}
        .pw-bar.good{background:var(--success);}
        .progress-bar-c{height:4px;background:var(--border);border-radius:4px;margin-bottom:32px;overflow:hidden;}
        .progress-fill{height:100%;background:linear-gradient(90deg,var(--primary),var(--accent));width:33.3%;transition:width 0.4s ease;}
        .step-nav{display:flex;gap:8px;margin-bottom:32px;}
        .step-dot{width:10px;height:10px;border-radius:50%;background:var(--border);transition:all var(--transition);}
        .step-dot.active{background:var(--primary);width:28px;border-radius:5px;}
        .step-dot.done{background:var(--success);}
        .step-page{display:none;}
        .step-page.active{display:block;}
        .success-view{text-align:center;display:none;}
        .success-icon{width:80px;height:80px;border-radius:50%;background:rgba(16,185,129,.1);border:2px solid rgba(16,185,129,.3);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:2.4rem;color:var(--success);}
        .divider{display:flex;align-items:center;gap:12px;margin:20px 0;}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border);}
        .social-btn{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:13px;border-radius:12px;border:1px solid var(--border);background:transparent;color:var(--text);font-weight:600;transition:all var(--transition);margin-bottom:10px;}
        .social-btn:hover{border-color:var(--accent);background:rgba(56,189,248,.04);transform:translateY(-2px);}
        .check-wrap{display:flex;align-items:flex-start;gap:10px;margin-bottom:20px;}
        .check-wrap input{width:18px;height:18px;accent-color:var(--primary);margin-top:2px;}
        .btn-submit{width:100%;padding:15px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;font-weight:700;border:none;transition:all var(--transition);display:flex;align-items:center;justify-content:center;gap:8px;}
        .btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(37,99,235,.4);}

        /* ========== FOOTER (unified) ========== */
        .footer{padding:60px 0 30px;border-top:1px solid var(--border);background:var(--card);}
        .footer-brand{font-size:1.4rem;font-weight:800;margin-bottom:12px;display:flex;align-items:center;gap:8px;}
        .footer-brand .logo-icon{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.9rem;}
        .footer-desc{font-size:0.88rem;color:var(--text-muted);margin-bottom:20px;max-width:300px;}
        .footer-title{font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:16px;color:var(--text);}
        .footer-links{list-style:none;padding:0;}
        .footer-links li{margin-bottom:10px;}
        .footer-links a{color:var(--text-muted);font-size:0.88rem;transition:color var(--transition);}
        .footer-links a:hover{color:var(--accent);}
        .footer-bottom{margin-top:40px;padding-top:20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;}
        .footer-socials{display:flex;gap:12px;}
        .footer-socials a{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:var(--card-alt);color:var(--text-muted);border:1px solid var(--border);transition:all var(--transition);}

        @media(max-width:767px){
            .auth-layout{grid-template-columns:1fr;}
            .auth-left{display:none;}
            .auth-panel{padding:32px 20px;}
            .industry-grid{grid-template-columns:repeat(2,1fr);}
            .input-row{grid-template-columns:1fr;}
            .footer-bottom{justify-content:center;text-align:center;flex-direction:column;}
        }
</style>
@endpush
