@extends('layouts.auth')

@section('title', 'Start Free Trial — COOCA')
@section('meta_description', 'Start your 30-day free trial. No credit card. Full access. Choose your industry and go live in 30 minutes.')

@section('content')
<main class="auth-layout">
    <!-- LEFT PANEL (value proposition) -->
    <div class="auth-left auth-panel">
        <div class="orb" style="width:500px;height:500px;background:#2563EB;top:-150px;right:-100px;"></div>
        <div class="orb" style="width:300px;height:300px;background:#38BDF8;bottom:-80px;left:-60px;"></div>
        <div class="grid-bg"></div>
        <div class="left-content">
            <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                <div class="logo-icon">C</div>
                <span style="font-size:1.8rem;font-weight:800;">{{ config('app.name', 'COOCA') }}</span>
            </div>
            <h2>Your Business System Will Be Live in <span class="text-gradient">30 Minutes.</span></h2>
            <p class="mt-3" style="font-size:.95rem;color:rgba(248,250,252,.7);">Sign up now. Get full access. No credit card, no commitment.</p>
            <div class="trust-items">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div><strong style="color:#F8FAFC;">30-day full access</strong><div class="trust-text">All 10 modules, unlimited users</div></div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-shield-lock-fill"></i></div>
                    <div><strong style="color:#F8FAFC;">Isolated infrastructure</strong><div class="trust-text">Provisioned in 30 min, zero risk</div></div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <div><strong style="color:#F8FAFC;">9 industry configs</strong><div class="trust-text">Retail, restaurant, clinic, salon, etc.</div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: MULTI-STEP FORM -->
    <div class="auth-right auth-panel">
        <div class="form-panel">
            <div class="d-md-none d-flex align-items-center gap-3 mb-4">
                <div class="logo-icon">C</div>
                <span style="font-size:1.6rem;font-weight:800;">{{ config('app.name', 'COOCA') }}</span>
            </div>
            
            <div class="progress-bar-c" style="height:4px;background:var(--border);border-radius:4px;margin-bottom:32px;overflow:hidden;">
                <div class="progress-fill" id="progressFill" style="height:100%;background:linear-gradient(90deg,var(--primary),var(--accent));width:33.3%;transition:width 0.4s ease;"></div>
            </div>
            
            <div class="step-nav" id="stepNav" style="display:flex;gap:8px;margin-bottom:32px;">
                <div class="step-dot active" id="dot1" style="width:10px;height:10px;border-radius:50%;background:var(--primary);transition:all var(--transition);"></div>
                <div class="step-dot" id="dot2" style="width:10px;height:10px;border-radius:50%;background:var(--border);transition:all var(--transition);"></div>
                <div class="step-dot" id="dot3" style="width:10px;height:10px;border-radius:50%;background:var(--border);transition:all var(--transition);"></div>
            </div>

            @if ($errors->any())
                <div class="error-msg" id="errorMsg" style="display:block;margin-bottom:20px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form id="registerForm" action="{{ route('customer.register') }}" method="POST">
                @csrf
                <input type="hidden" name="referral_code" value="{{ request('ref') }}">
                <input type="hidden" name="industry" id="industryInput" value="">

                <!-- STEP 1 -->
                <div class="step-page active" id="step1">
                    <div class="form-title">Create your account</div>
                    <p class="form-subtitle">Step 1 of 3 · Start your 30-day free trial. <a href="{{ route('customer.login') }}">Already have one?</a></p>
                    
                    <button type="button" class="social-btn" onclick="window.location.href='{{ route('customer.auth.google') }}'">
                        <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        Sign up with Google
                    </button>
                    
                    <div class="divider"><span>or with email</span></div>
                    
                    <div class="input-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="input-wrap">
                            <label class="input-label">First Name</label>
                            <div class="position-relative" style="position:relative;">
                                <i class="bi bi-person input-icon"></i>
                                <input type="text" class="input-field" name="first_name" placeholder="Ahmad" value="{{ old('first_name') }}">
                            </div>
                        </div>
                        <div class="input-wrap">
                            <label class="input-label">Last Name</label>
                            <div class="position-relative" style="position:relative;">
                                <i class="bi bi-person input-icon"></i>
                                <input type="text" class="input-field" name="last_name" placeholder="Kurniawan" value="{{ old('last_name') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Work Email</label>
                        <div class="position-relative" style="position:relative;">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" class="input-field" id="regEmail" name="email" placeholder="you@company.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Phone / WhatsApp</label>
                        <div class="position-relative" style="position:relative;">
                            <i class="bi bi-phone input-icon"></i>
                            <input type="tel" class="input-field" name="phone" placeholder="+62 812 3456 7890" value="{{ old('phone') }}">
                        </div>
                    </div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Password</label>
                        <div class="position-relative" style="position:relative;">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" class="input-field" id="regPassword" name="password" placeholder="Min. 8 characters" required minlength="8">
                            <span class="input-toggle" id="regPwToggle" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--text-muted);"><i class="bi bi-eye" id="regPwIcon"></i></span>
                        </div>
                        <div class="pw-strength" style="margin-top:8px;">
                            <div class="pw-bars" style="display:flex;gap:4px;margin-bottom:4px;">
                                <div class="pw-bar" id="b1" style="flex:1;height:4px;border-radius:2px;background:var(--border);transition:background 0.2s;"></div>
                                <div class="pw-bar" id="b2" style="flex:1;height:4px;border-radius:2px;background:var(--border);transition:background 0.2s;"></div>
                                <div class="pw-bar" id="b3" style="flex:1;height:4px;border-radius:2px;background:var(--border);transition:background 0.2s;"></div>
                                <div class="pw-bar" id="b4" style="flex:1;height:4px;border-radius:2px;background:var(--border);transition:background 0.2s;"></div>
                            </div>
                            <div class="pw-label" id="pwLabel" style="font-size:0.75rem;color:var(--text-muted);">Enter a password</div>
                        </div>
                    </div>
                    
                    <div class="check-wrap">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to COOCA's <a href="{{ route('terms') }}">Terms of Service</a> and <a href="{{ route('privacy') }}">Privacy Policy</a></label>
                    </div>
                    
                    <button type="button" class="btn-submit" onclick="goStep(2)" style="width:100%;padding:15px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;font-weight:700;border:none;transition:all var(--transition);display:flex;align-items:center;justify-content:center;gap:8px;">
                        Continue <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

                <!-- STEP 2: BUSINESS INFO -->
                <div class="step-page" id="step2" style="display:none;">
                    <div class="form-title">Tell us about your business</div>
                    <p class="form-subtitle">Step 2 of 3 · Industry & business details for auto‑configuration.</p>
                    
                    <div class="input-wrap">
                        <label class="input-label">Business Name</label>
                        <div class="position-relative" style="position:relative;">
                            <i class="bi bi-building input-icon"></i>
                            <input type="text" class="input-field" name="business_name" placeholder="PT / CV / UD Nama Bisnis" value="{{ old('business_name') }}">
                        </div>
                    </div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Number of Employees</label>
                        <select class="input-field" name="employees" style="padding-left:16px;">
                            <option disabled selected>Select team size</option>
                            <option>Just me</option>
                            <option>2–5 people</option>
                            <option>6–20 people</option>
                            <option>21–50 people</option>
                            <option>51–100 people</option>
                            <option>100+ people</option>
                        </select>
                    </div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Number of Outlets / Locations</label>
                        <select class="input-field" name="locations" style="padding-left:16px;">
                            <option disabled selected>Select</option>
                            <option>1 location</option>
                            <option>2–5 locations</option>
                            <option>6–15 locations</option>
                            <option>16+ locations</option>
                        </select>
                    </div>
                    
                    <div class="input-wrap">
                        <label class="input-label">Choose Your Industry</label>
                        <div class="industry-grid" id="industryGrid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;">
                            <div class="industry-card" onclick="selectIndustry(this,'Retail')" style="padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);">
                                <span class="ic-icon" style="font-size:1.8rem;margin-bottom:8px;display:block;">🛍️</span>
                                <span class="ic-label" style="font-size:.75rem;font-weight:600;color:var(--text-muted);">Retail</span>
                            </div>
                            <div class="industry-card" onclick="selectIndustry(this,'Restaurant')" style="padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);">
                                <span class="ic-icon" style="font-size:1.8rem;margin-bottom:8px;display:block;">🍜</span>
                                <span class="ic-label" style="font-size:.75rem;font-weight:600;color:var(--text-muted);">Restaurant</span>
                            </div>
                            <div class="industry-card" onclick="selectIndustry(this,'Hotel')" style="padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);">
                                <span class="ic-icon" style="font-size:1.8rem;margin-bottom:8px;display:block;">🏨</span>
                                <span class="ic-label" style="font-size:.75rem;font-weight:600;color:var(--text-muted);">Hotel</span>
                            </div>
                            <div class="industry-card" onclick="selectIndustry(this,'Clinic')" style="padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);">
                                <span class="ic-icon" style="font-size:1.8rem;margin-bottom:8px;display:block;">🏥</span>
                                <span class="ic-label" style="font-size:.75rem;font-weight:600;color:var(--text-muted);">Clinic</span>
                            </div>
                            <div class="industry-card" onclick="selectIndustry(this,'Education')" style="padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);">
                                <span class="ic-icon" style="font-size:1.8rem;margin-bottom:8px;display:block;">🎓</span>
                                <span class="ic-label" style="font-size:.75rem;font-weight:600;color:var(--text-muted);">Education</span>
                            </div>
                            <div class="industry-card" onclick="selectIndustry(this,'Salon')" style="padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);">
                                <span class="ic-icon" style="font-size:1.8rem;margin-bottom:8px;display:block;">✂️</span>
                                <span class="ic-label" style="font-size:.75rem;font-weight:600;color:var(--text-muted);">Salon</span>
                            </div>
                            <div class="industry-card" onclick="selectIndustry(this,'Laundry')" style="padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);">
                                <span class="ic-icon" style="font-size:1.8rem;margin-bottom:8px;display:block;">👕</span>
                                <span class="ic-label" style="font-size:.75rem;font-weight:600;color:var(--text-muted);">Laundry</span>
                            </div>
                            <div class="industry-card" onclick="selectIndustry(this,'Workshop')" style="padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);">
                                <span class="ic-icon" style="font-size:1.8rem;margin-bottom:8px;display:block;">🔧</span>
                                <span class="ic-label" style="font-size:.75rem;font-weight:600;color:var(--text-muted);">Workshop</span>
                            </div>
                            <div class="industry-card" onclick="selectIndustry(this,'Rental')" style="padding:14px 8px;border-radius:12px;border:2px solid var(--border);background:var(--card-alt);cursor:pointer;text-align:center;transition:all var(--transition);">
                                <span class="ic-icon" style="font-size:1.8rem;margin-bottom:8px;display:block;">🔑</span>
                                <span class="ic-label" style="font-size:.75rem;font-weight:600;color:var(--text-muted);">Rental</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display:flex;gap:12px;">
                        <button type="button" class="btn-cooca btn-cooca-outline" style="flex:1;" onclick="goStep(1)">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn-cooca btn-cooca-primary" style="flex:1;" onclick="goStep(3)">
                            Continue <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 3: CONFIRM & LAUNCH -->
                <div class="step-page" id="step3" style="display:none;">
                    <div class="form-title">You're almost there!</div>
                    <p class="form-subtitle">Step 3 of 3 · Confirm your setup and launch your free trial.</p>
                    
                    <div class="glass p-4 rounded-3 mb-4" style="background:var(--glass);backdrop-filter:blur(20px);border:1px solid var(--glass-border);border-radius:var(--radius);padding:24px;">
                        <div class="fw-bold text-uppercase small mb-3" style="font-weight:700;text-transform:uppercase;font-size:0.75rem;margin-bottom:12px;">Your Setup Summary</div>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between"><span class="text-muted">Plan</span><span class="text-success fw-bold" style="color:var(--success)!important;font-weight:700;">30-Day Free Trial</span></div>
                            <div class="d-flex justify-content-between"><span class="text-muted">Industry</span><span id="summaryIndustry" class="fw-bold">—</span></div>
                            <div class="d-flex justify-content-between"><span class="text-muted">Modules</span><span>All 10 included</span></div>
                            <div class="d-flex justify-content-between"><span class="text-muted">Users</span><span>Unlimited</span></div>
                            <div class="d-flex justify-content-between"><span class="text-muted">Provisioning</span><span>~30 minutes</span></div>
                            <div class="d-flex justify-content-between"><span class="text-muted">Credit Card</span><span class="text-success" style="color:var(--success)!important;">Not Required</span></div>
                        </div>
                    </div>
                    
                    <div class="check-wrap">
                        <input type="checkbox" id="newsletter2" name="newsletter" checked>
                        <label for="newsletter2">Send me tips on getting the most out of COOCA for my industry</label>
                    </div>
                    
                    <div style="display:flex;gap:12px;">
                        <button type="button" class="btn-cooca btn-cooca-outline" style="flex:1;" onclick="goStep(2)">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="btn-cooca btn-cooca-success" id="launchBtn" style="flex:1;background:linear-gradient(135deg,#10B981,#059669);color:#fff;">
                            <span id="launchText">Launch My Free Trial</span>
                            <i class="bi bi-rocket-takeoff-fill" id="launchIcon"></i>
                        </button>
                    </div>
                    
                    <p class="text-center small mt-3">By signing up, you agree to our <a href="{{ route('terms') }}">Terms</a> and <a href="{{ route('privacy') }}">Privacy Policy</a>.</p>
                </div>
            </form>

            <!-- SUCCESS VIEW -->
            <div class="success-view" id="successView" style="text-align:center;display:none;">
                <div class="success-icon" style="width:80px;height:80px;border-radius:50%;background:rgba(16,185,129,.1);border:2px solid rgba(16,185,129,.3);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:2.4rem;color:var(--success);">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h3>You're in! 🎉</h3>
                <p>Your isolated environment is being provisioned. Check your email for the access link — it will be ready within 30 minutes.</p>
                <div class="glass p-3 rounded-3 mb-4" style="background:var(--glass);backdrop-filter:blur(20px);border:1px solid var(--glass-border);border-radius:var(--radius);padding:16px;">
                    <div>What happens next:</div>
                    <div class="mt-2">
                        <div><i class="bi bi-envelope-fill text-success me-2" style="color:var(--success);"></i> Confirmation email sent</div>
                        <div><i class="bi bi-gear-fill text-success me-2" style="color:var(--success);"></i> System provisioning (~30 min)</div>
                        <div><i class="bi bi-play-fill text-success me-2" style="color:var(--success);"></i> Access link delivered to your email</div>
                    </div>
                </div>
                <a href="{{ route('customer.login') }}" class="btn-cooca btn-cooca-primary w-100" style="display:inline-flex;width:100%;justify-content:center;">
                    Go to Login <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <p class="text-center mt-4">Already have an account? <a href="{{ route('customer.login') }}" class="fw-bold">Log in →</a></p>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
(function(){
    // Password toggle
    const pwToggle = document.getElementById('regPwToggle');
    const pwField = document.getElementById('regPassword');
    const pwIcon = document.getElementById('regPwIcon');
    
    if(pwToggle && pwField) {
        pwToggle.addEventListener('click', function() {
            const isPass = pwField.type === 'password';
            pwField.type = isPass ? 'text' : 'password';
            pwIcon.className = 'bi ' + (isPass ? 'bi-eye-slash' : 'bi-eye');
        });
    }
    
    // Password strength
    const pw = document.getElementById('regPassword');
    if(pw) {
        pw.addEventListener('input', function() {
            const v = this.value;
            const bars = [document.getElementById('b1'), document.getElementById('b2'), document.getElementById('b3'), document.getElementById('b4')];
            const label = document.getElementById('pwLabel');
            
            bars.forEach(b => { if(b) b.className = 'pw-bar'; });
            
            if(!v.length) {
                if(label) label.textContent = 'Enter a password';
                return;
            }
            
            let score = 0;
            if(v.length >= 8) score++;
            if(v.length >= 12) score++;
            if(/[A-Z]/.test(v) && /[0-9]/.test(v)) score++;
            if(/[^A-Za-z0-9]/.test(v)) score++;
            
            const strength = Math.min(4, Math.max(1, score));
            const classes = ['weak', 'fair', 'good', 'good'];
            const labels = ['Weak', 'Fair', 'Good', 'Strong'];
            
            for(let i = 0; i < strength; i++) {
                if(bars[i]) bars[i].className = 'pw-bar ' + classes[strength - 1];
            }
            
            if(label) label.textContent = labels[strength - 1] || 'Weak';
        });
    }
    
    // Industry selection
    window.selectIndustry = function(el, name) {
        document.querySelectorAll('.industry-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('industryInput').value = name;
        const sum = document.getElementById('summaryIndustry');
        if(sum) sum.textContent = name;
    };
    
    // Step navigation
    window.goStep = function(n) {
        // Validate step 1
        if(n === 2) {
            const email = document.getElementById('regEmail');
            const password = document.getElementById('regPassword');
            const terms = document.getElementById('terms');
            
            if(!email.value || !email.validity.valid) {
                email.style.borderColor = 'var(--danger)';
                alert('Please enter a valid email');
                return;
            }
            
            if(!password.value || password.value.length < 8) {
                password.style.borderColor = 'var(--danger)';
                alert('Password must be at least 8 characters');
                return;
            }
            
            if(!terms.checked) {
                alert('Please agree to the Terms of Service');
                return;
            }
        }
        
        document.querySelectorAll('.step-page').forEach(p => p.style.display = 'none');
        document.getElementById('step' + n).style.display = 'block';
        
        const dots = [document.getElementById('dot1'), document.getElementById('dot2'), document.getElementById('dot3')];
        dots.forEach((d, i) => {
            if(d) {
                d.className = 'step-dot' + (i + 1 < n ? ' done' : i + 1 === n ? ' active' : '');
                d.style.background = i + 1 < n ? 'var(--success)' : i + 1 === n ? 'var(--primary)' : 'var(--border)';
                d.style.width = i + 1 === n ? '28px' : '10px';
                d.style.borderRadius = i + 1 === n ? '5px' : '50%';
            }
        });
        
        const pcts = {1: '33.3%', 2: '66.6%', 3: '100%'};
        const fill = document.getElementById('progressFill');
        if(fill) fill.style.width = pcts[n];
        
        window.scrollTo({top: 0, behavior: 'smooth'});
    };
})();
</script>
@endpush
