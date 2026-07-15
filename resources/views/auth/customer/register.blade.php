@extends('layouts.guest')

@push('scripts')
    <style>
        /* Pastikan hanya satu step yang terlihat */
        .step-page {
            display: none;
        }

        .step-page.active {
            display: block;
        }

        /* Tab disabled tidak bisa diklik */
        #regTabs .nav-link.disabled {
            pointer-events: none;
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
    <script>
        let currentStep = 1;

        function selectIndustry(el, name) {
            document.querySelectorAll('.industry-card').forEach(function(c) {
                c.classList.remove('selected');
            });
            el.classList.add('selected');
            window.selectedIndustry = name;
            checkStep2Valid(); // perbarui status tab step 3
        }

        // Validasi step 1: semua field wajib + terms
        function isStep1Valid() {
            const name = document.querySelector('input[name="name"]').value.trim();
            const email = document.querySelector('input[name="email"]').value.trim();
            const password = document.getElementById('regPassword').value;
            const confirm = document.querySelector('input[name="password_confirmation"]').value;
            const terms = document.getElementById('terms').checked;
            return name.length > 0 &&
                email.includes('@') &&
                password.length >= 8 &&
                password === confirm &&
                terms;
        }

        // Validasi step 2: minimal industri dipilih
        function isStep2Valid() {
            return typeof window.selectedIndustry !== 'undefined' && window.selectedIndustry.trim() !== '';
        }

        // Aktifkan/nonaktifkan tab step 2 berdasarkan validasi step 1
        function checkStep1Valid() {
            const tab2 = document.querySelector('#regTabs .nav-link[data-step="2"]');
            if (tab2) {
                if (isStep1Valid()) {
                    tab2.classList.remove('disabled');
                } else {
                    tab2.classList.add('disabled');
                }
            }
        }

        // Aktifkan/nonaktifkan tab step 3 berdasarkan validasi step 2
        function checkStep2Valid() {
            const tab3 = document.querySelector('#regTabs .nav-link[data-step="3"]');
            if (tab3) {
                if (isStep2Valid()) {
                    tab3.classList.remove('disabled');
                } else {
                    tab3.classList.add('disabled');
                }
            }
        }

        // Fungsi utama pindah step
        function goStep(n) {
            // Jika target sama dengan posisi sekarang, abaikan
            if (n === currentStep) return;

            // Validasi jika maju
            if (n > currentStep) {
                for (let s = currentStep; s < n; s++) {
                    if (s === 1 && !isStep1Valid()) {
                        alert('Mohon lengkapi data akun terlebih dahulu.');
                        return;
                    }
                    if (s === 2 && !isStep2Valid()) {
                        alert('Silakan pilih industri Anda terlebih dahulu.');
                        return;
                    }
                }
            }

            // Sembunyikan semua step-page
            document.querySelectorAll('.step-page').forEach(p => p.classList.remove('active'));
            // Tampilkan step tujuan
            document.getElementById('step' + n).classList.add('active');

            // Update tab aktif
            document.querySelectorAll('#regTabs .nav-link').forEach(tab => {
                tab.classList.remove('active');
                if (tab.getAttribute('data-step') == n) {
                    tab.classList.add('active');
                }
            });

            // Update progress bar
            const pcts = {
                1: '33.3%',
                2: '66.6%',
                3: '100%'
            };
            const fill = document.getElementById('progressFill');
            if (fill) fill.style.width = pcts[n];

            // Jika ke step 3, isi ringkasan data
            if (n === 3) {
                const summary = document.getElementById('summaryDetails');
                if (summary) {
                    const name = document.querySelector('input[name="name"]').value || '-';
                    const email = document.querySelector('input[name="email"]').value || '-';
                    const business = document.querySelector('input[name="business_name"]').value || '-';
                    const industry = window.selectedIndustry || '-';
                    summary.innerHTML = `
                        <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Full Name') }}</span><span>${name}</span></div>
                        <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Email') }}</span><span>${email}</span></div>
                        <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Business') }}</span><span>${business}</span></div>
                        <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Industry') }}</span><span>${industry}</span></div>
                    `;
                }
            }

            // Perbarui currentStep
            currentStep = n;

            // Scroll ke atas
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Awal: disable tab 2 dan 3
            const tab2 = document.querySelector('#regTabs .nav-link[data-step="2"]');
            const tab3 = document.querySelector('#regTabs .nav-link[data-step="3"]');
            if (tab2) tab2.classList.add('disabled');
            if (tab3) tab3.classList.add('disabled');

            // Password toggle
            const pwTgl = document.getElementById('regPwToggle'),
                pwFld = document.getElementById('regPassword'),
                pwIco = document.getElementById('regPwIcon');
            if (pwTgl && pwFld) {
                pwTgl.addEventListener('click', function() {
                    const isPass = pwFld.type === 'password';
                    pwFld.type = isPass ? 'text' : 'password';
                    pwIco.className = 'bi ' + (isPass ? 'bi-eye-slash' : 'bi-eye');
                });
            }

            // Password strength
            const pw = document.getElementById('regPassword');
            if (pw) {
                pw.addEventListener('input', function() {
                    const v = this.value;
                    const bars = ['b1', 'b2', 'b3', 'b4'].map(id => document.getElementById(id));
                    const lbl = document.getElementById('pwLabel');
                    bars.forEach(b => {
                        if (b) b.className = 'pw-bar';
                    });
                    if (!v.length) {
                        if (lbl) lbl.textContent = 'Enter a password';
                        return;
                    }
                    let sc = 0;
                    if (v.length >= 8) sc++;
                    if (v.length >= 12) sc++;
                    if (/[A-Z]/.test(v) && /[0-9]/.test(v)) sc++;
                    if (/[^A-Za-z0-9]/.test(v)) sc++;
                    const st = Math.min(4, Math.max(1, sc));
                    const cls = ['weak', 'fair', 'good', 'strong'];
                    const lls = ['Weak', 'Fair', 'Good', 'Strong'];
                    for (let i = 0; i < st; i++) {
                        if (bars[i]) bars[i].className = 'pw-bar ' + cls[st - 1];
                    }
                    if (lbl) lbl.textContent = lls[st - 1] || 'Weak';
                });
            }

            // Pantau perubahan input step 1 untuk validasi realtime
            const step1Inputs = document.querySelectorAll('#step1 input');
            step1Inputs.forEach(input => input.addEventListener('input', checkStep1Valid));
            const termsCheck = document.getElementById('terms');
            if (termsCheck) termsCheck.addEventListener('change', checkStep1Valid);
            // Cek awal jika ada old value
            checkStep1Valid();
            checkStep2Valid();

            // Klik pada tab
            document.querySelectorAll('#regTabs .nav-link').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const step = parseInt(this.getAttribute('data-step'));
                    goStep(step);
                });
            });
        });
    </script>
@endpush

@section('content')
    <div class="auth-layout">
        <!-- LEFT PANEL -->
        <div class="auth-left auth-panel">
            <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
            <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
            <div class="grid-bg"></div>
            <div class="auth-left-content">
                <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                    <div class="brand-icon">C</div>
                    <span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name', 'COOCA') }}</span>
                </div>
                <h2>{{ __('Your Business System Will Be Live in') }} <span
                        class="text-gradient">{{ __('30 Minutes.') }}</span></h2>
                <p class="mt-3" style="font-size:.95rem;color:var(--text-muted);">
                    {{ __('Sign up now. Get full access. No credit card, no commitment.') }}</p>
                <div class="d-flex flex-column gap-3 mt-5 text-start">
                    <div class="auth-trust-item">
                        <div class="auth-trust-icon"><i class="bi bi-check-circle-fill"></i></div>
                        <div style="font-size:.82rem;"><strong
                                style="color:var(--text);">{{ __('30-day full access') }}</strong><br><span
                                style="color:var(--text-muted);">{{ __('All 10 modules, unlimited users') }}</span></div>
                    </div>
                    <div class="auth-trust-item">
                        <div class="auth-trust-icon"><i class="bi bi-shield-lock-fill"></i></div>
                        <div style="font-size:.82rem;"><strong
                                style="color:var(--text);">{{ __('Isolated infrastructure') }}</strong><br><span
                                style="color:var(--text-muted);">{{ __('Provisioned in 30 min, zero risk') }}</span></div>
                    </div>
                    <div class="auth-trust-item">
                        <div class="auth-trust-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <div style="font-size:.82rem;"><strong
                                style="color:var(--text);">{{ __('9 industry configs') }}</strong><br><span
                                style="color:var(--text-muted);">{{ __('Retail, restaurant, clinic, salon, etc.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="auth-right auth-panel">
            <div class="auth-form-panel">
                <!-- Mobile brand -->
                <div class="d-md-none d-flex align-items-center gap-3 mb-4">
                    <div class="brand-icon">C</div><span
                        style="font-size:1.6rem;font-weight:800;">{{ setting('site.name', 'COOCA') }}</span>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0" style="padding-left:20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Progress bar -->
                <div class="progress-bar-c">
                    <div class="progress-fill" id="progressFill" style="width:33.3%"></div>
                </div>

                <!-- Tab navigasi -->
                <ul class="nav nav-tabs mb-4" id="regTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-step="1" href="#">{{ __('Account') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" data-step="2" href="#">{{ __('Business') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" data-step="3" href="#">{{ __('Launch') }}</a>
                    </li>
                </ul>

                <form action="{{ route('customer.register.submit') }}" method="POST" id="regForm">
                    @csrf

                    <!-- STEP 1 -->
                    <div class="step-page active" id="step1">
                        <div class="form-title" style="font-size:1.7rem;font-weight:800;">{{ __('Create your account') }}
                        </div>
                        <p style="font-size:.9rem;margin-bottom:24px;">
                            {{ __('Step 1 of 3 · Start your 30-day free trial.') }} <a
                                href="{{ route('customer.login') }}">{{ __('Already have one?') }}</a></p>
                        <div class="form-group"><label class="form-label">{{ __('Full Name') }}</label>
                            <div class="input-icon-wrap"><i class="bi bi-person input-icon"></i><input type="text"
                                    name="name" class="form-control" placeholder="Ahmad Kurniawan"
                                    value="{{ old('name') }}" required></div>
                        </div>
                        <div class="form-group"><label class="form-label">{{ __('Work Email') }}</label>
                            <div class="input-icon-wrap"><i class="bi bi-envelope input-icon"></i><input type="email"
                                    name="email" class="form-control" id="regEmail" placeholder="you@company.com"
                                    value="{{ old('email') }}" required></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('Password') }}</label>
                            <div class="input-icon-wrap">
                                <i class="bi bi-lock input-icon"></i>
                                <input type="password" name="password" class="form-control" id="regPassword"
                                    placeholder="Min. 8 characters" required>
                                <button type="button" class="input-toggle" id="regPwToggle" data-target="#regPassword"><i
                                        class="bi bi-eye" id="regPwIcon"></i></button>
                            </div>
                            <div class="pw-strength">
                                <div class="pw-bars">
                                    <div class="pw-bar" id="b1"></div>
                                    <div class="pw-bar" id="b2"></div>
                                    <div class="pw-bar" id="b3"></div>
                                    <div class="pw-bar" id="b4"></div>
                                </div>
                                <div class="pw-label" id="pwLabel" style="font-size:.75rem;color:var(--text-muted);">
                                    Enter a password</div>
                            </div>
                        </div>
                        <div class="form-group"><label class="form-label">{{ __('Confirm Password') }}</label>
                            <div class="input-icon-wrap"><i class="bi bi-lock input-icon"></i><input type="password"
                                    name="password_confirmation" class="form-control" placeholder="Repeat password"
                                    required></div>
                        </div>
                        <div class="form-check mb-3"><input type="checkbox" id="terms" class="form-check-input"
                                required><label for="terms" class="form-check-label">{{ __('I agree to COOCAs') }} <a
                                    href="{{ route('terms') }}">{{ __('Terms') }}</a> {{ __('and') }} <a
                                    href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a></label></div>
                        <button type="button" class="btn btn-primary btn-block btn-lg"
                            onclick="goStep(2)">{{ __('Continue') }} <i class="bi bi-arrow-right"></i></button>
                    </div>

                    <!-- STEP 2 -->
                    <div class="step-page" id="step2">
                        <div class="form-title" style="font-size:1.7rem;font-weight:800;">
                            {{ __('Tell us about your business') }}</div>
                        <p style="font-size:.9rem;margin-bottom:24px;">
                            {{ __('Step 2 of 3 · Industry & business details.') }}</p>
                        <div class="form-group"><label class="form-label">{{ __('Business Name (Optional)') }}</label>
                            <div class="input-icon-wrap"><i class="bi bi-building input-icon"></i><input type="text"
                                    name="business_name" class="form-control" placeholder="RetailMax Indonesia"
                                    value="{{ old('business_name') }}"></div>
                        </div>
                        <div class="form-group"><label class="form-label">{{ __('Referral Code (Optional)') }}</label>
                            <div class="input-icon-wrap"><i class="bi bi-person-bounding-box input-icon"></i><input
                                    type="text" name="referral_code" class="form-control"
                                    placeholder="Affiliator Code" value="{{ old('referral_code') }}"></div>
                        </div>
                        <div class="form-group"><label class="form-label">{{ __('Choose Your Industry') }}</label>
                            <div class="industry-grid">
                                <div class="industry-card" onclick="selectIndustry(this,'Retail')"><span
                                        class="ic-icon">🛍️</span><span class="ic-label">Retail</span></div>
                                <div class="industry-card" onclick="selectIndustry(this,'Restaurant')"><span
                                        class="ic-icon">🍴</span><span class="ic-label">Restaurant</span></div>
                                <div class="industry-card" onclick="selectIndustry(this,'Hotel')"><span
                                        class="ic-icon">🏨</span><span class="ic-label">Hotel</span></div>
                                <div class="industry-card" onclick="selectIndustry(this,'Clinic')"><span
                                        class="ic-icon">🏥</span><span class="ic-label">Clinic</span></div>
                                <div class="industry-card" onclick="selectIndustry(this,'Education')"><span
                                        class="ic-icon">🎓</span><span class="ic-label">Education</span></div>
                                <div class="industry-card" onclick="selectIndustry(this,'Salon')"><span
                                        class="ic-icon">✂️</span><span class="ic-label">Salon</span></div>
                            </div>
                        </div>
                        <div style="display:flex;gap:12px;">
                            <button type="button" class="btn btn-outline" style="flex:1;" onclick="goStep(1)"><i
                                    class="bi bi-arrow-left"></i> {{ __('Back') }}</button>
                            <button type="button" class="btn btn-primary" style="flex:1;"
                                onclick="goStep(3)">{{ __('Continue') }} <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="step-page" id="step3">
                        <div class="form-title" style="font-size:1.7rem;font-weight:800;">
                            {{ __('You are almost there!') }}</div>
                        <p style="font-size:.9rem;margin-bottom:24px;">{{ __('Step 3 of 3 · Confirm and launch.') }}</p>

                        <div class="card mb-3"
                            style="border:1px solid var(--border);padding:20px;border-radius:var(--radius-sm);">
                            <div
                                style="font-weight:700;text-transform:uppercase;font-size:.75rem;letter-spacing:.06em;margin-bottom:14px;color:var(--text-muted);">
                                {{ __('Your Setup Summary') }}</div>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-content-between"><span
                                        class="text-muted">{{ __('Plan') }}</span><span style="color:var(--success);"
                                        class="fw-bold">{{ __('30-Day Free Trial') }}</span></div>
                                <div class="d-flex justify-content-between"><span
                                        class="text-muted">{{ __('Modules') }}</span><span>{{ __('All 10 included') }}</span>
                                </div>
                                <div class="d-flex justify-content-between"><span
                                        class="text-muted">{{ __('Users') }}</span><span>{{ __('Unlimited') }}</span>
                                </div>
                                <div class="d-flex justify-content-between"><span
                                        class="text-muted">{{ __('Credit Card') }}</span><span
                                        style="color:var(--success);">{{ __('Not Required') }}</span></div>
                            </div>
                        </div>
                        <div class="card mb-4"
                            style="border:1px solid var(--border);padding:20px;border-radius:var(--radius-sm);">
                            <div
                                style="font-weight:700;text-transform:uppercase;font-size:.75rem;letter-spacing:.06em;margin-bottom:14px;color:var(--text-muted);">
                                {{ __('Your Details') }}</div>
                            <div class="d-flex flex-column gap-2" id="summaryDetails">
                                <!-- akan diisi oleh JavaScript -->
                            </div>
                        </div>
                        <div style="display:flex;gap:12px;">
                            <button type="button" class="btn btn-outline" style="flex:1;" onclick="goStep(2)"><i
                                    class="bi bi-arrow-left"></i> {{ __('Back') }}</button>
                            <button type="submit" class="btn btn-success"
                                style="flex:1;">{{ __('Launch My Free Trial') }} <i
                                    class="bi bi-rocket-takeoff-fill"></i></button>
                        </div>
                        <p class="text-center mt-3" style="font-size:.78rem;">{{ __('By signing up, you agree to our') }}
                            <a href="{{ route('terms') }}">{{ __('Terms') }}</a> {{ __('and') }} <a
                                href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a>.</p>
                    </div>
                </form>
                <p class="text-center mt-4">{{ __('Already have an account?') }} <a href="{{ route('customer.login') }}"
                        class="fw-bold">{{ __('Log in →') }}</a></p>
            </div>
        </div>
    </div>
@endsection
