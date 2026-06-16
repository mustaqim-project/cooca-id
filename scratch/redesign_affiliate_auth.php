<?php

// 1. Update login.blade.php
$loginFile = 'c:/laragon/www/cooca-id/resources/views/auth/affiliator/login.blade.php';
$loginContent = file_get_contents($loginFile);

$newLoginLeft = <<<HTML
    <div class="auth-left auth-panel">
        <div class="orb" style="width:500px;height:500px;background:#10B981;top:-150px;right:-100px;opacity:0.4;"></div>
        <div class="orb" style="width:300px;height:300px;background:#059669;bottom:-80px;left:-60px;opacity:0.4;"></div>
        <div class="grid-bg"></div>
        <div class="left-content">
            <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                <div class="logo-icon" style="background:linear-gradient(135deg, #10B981, #059669);">C</div>
                <span class="brand-name" style="font-size:1.8rem;font-weight:800;">COOCA Partners</span>
            </div>
            <h2>Build a <span class="text-gradient" style="background:linear-gradient(135deg, #34D399, #10B981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Passive Income</span> Empire.</h2>
            <p style="font-size:.95rem;color:rgba(248,250,252,.6);margin-top:12px;">Log in to access your dashboard, track referrals, and withdraw your commissions.</p>
            <div class="trust-items">
                <div class="trust-item">
                    <div class="trust-icon" style="background:rgba(16,185,129,0.1);color:#10B981;border:1px solid rgba(16,185,129,0.2);"><i class="bi bi-cash-stack"></i></div>
                    <div class="trust-text"><strong style="color:#F8FAFC;">Up to 30% Commission</strong> — 25% direct commission + 5% team override on every payment.</div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon" style="background:rgba(16,185,129,0.1);color:#10B981;border:1px solid rgba(16,185,129,0.2);"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="trust-text"><strong style="color:#F8FAFC;">Real-Time Tracking</strong> — Instant visibility into clicks, signups, and your earnings.</div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon" style="background:rgba(16,185,129,0.1);color:#10B981;border:1px solid rgba(16,185,129,0.2);"><i class="bi bi-calendar-check-fill"></i></div>
                    <div class="trust-text"><strong style="color:#F8FAFC;">Reliable Payouts</strong> — Commissions are calculated and paid out securely every month.</div>
                </div>
            </div>
        </div>
    </div>
HTML;

// Replace the auth-left div block
$loginContent = preg_replace('/<div class="auth-left auth-panel">.*?<\/div>\s*<!-- RIGHT FORM PANEL -->/s', $newLoginLeft . "\n\n    <!-- RIGHT FORM PANEL -->", $loginContent);
file_put_contents($loginFile, $loginContent);
echo "Updated affiliator login\n";


// 2. Update register.blade.php
$regFile = 'c:/laragon/www/cooca-id/resources/views/auth/affiliator/register.blade.php';
$regContent = file_get_contents($regFile);

$newRegLeft = <<<HTML
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
HTML;

$regContent = preg_replace('/<div class="auth-left auth-panel">.*?<\/div>\s*<!-- RIGHT PANEL: MULTI-STEP FORM -->/s', $newRegLeft . "\n\n    <!-- RIGHT PANEL: MULTI-STEP FORM -->", $regContent);
file_put_contents($regFile, $regContent);
echo "Updated affiliator register\n";
