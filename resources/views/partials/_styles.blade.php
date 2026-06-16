<style>
/* ==================================================================
     UNIFIED DESIGN SYSTEM — COOCA
     ================================================================== */
:root {
    --bg: #020617;
    --card: #0f172a;
    --card-alt: #1e293b;
    --text: #f8fafc;
    --text-muted: #94a3b8;
    --primary: #2563eb;
    --secondary: #1e40af;
    --accent: #38bdf8;
    --success: #10b981;
    --border: rgba(56, 189, 248, 0.12);
    --shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.6);
    --glass: rgba(15, 23, 42, 0.65);
    --glass-border: rgba(56, 189, 248, 0.14);
    --radius: 16px;
    --radius-sm: 10px;
    --radius-lg: 24px;
    --transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    --font: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    --hero-gradient: linear-gradient(160deg, #020617 0%, #0f172a 35%, #1e3a5f 65%, #020617 100%);
}

[data-theme="light"] {
    --bg: #f8fafc;
    --card: #ffffff;
    --card-alt: #f1f5f9;
    --text: #0f172a;
    --text-muted: #475569;
    --primary: #2563eb;
    --secondary: #7c3aed;
    --accent: #0ea5e9;
    --border: rgba(37, 99, 235, 0.12);
    --shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.1);
    --glass: rgba(255, 255, 255, 0.7);
    --glass-border: rgba(37, 99, 235, 0.1);
    --hero-gradient: linear-gradient(160deg, #f8fafc 0%, #eff6ff 35%, #dbeafe 65%, #f8fafc 100%);
}

*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    scroll-behavior: smooth;
    overflow-x: hidden;
}

body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    line-height: 1.7;
    transition: background var(--transition), color var(--transition);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
}

img {
    max-width: 100%;
    height: auto;
}

a {
    color: var(--accent);
    text-decoration: none;
    transition: color var(--transition);
}

a:hover {
    color: var(--primary);
}

h1, h2, h3, h4, h5, h6 {
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -0.02em;
}

h1 { font-size: clamp(2.4rem, 5vw, 4.2rem); }
h2 { font-size: clamp(1.8rem, 3.5vw, 3rem); }
h3 { font-size: clamp(1.2rem, 2vw, 1.5rem); }

p { color: var(--text-muted); }

/* Scrollbar */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

/* Utility Classes */
.section-padding { padding: 100px 0; }

.glass {
    background: var(--glass);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
}

.text-gradient {
    background: linear-gradient(135deg, var(--accent), var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.badge-glow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    background: rgba(56, 189, 248, 0.1);
    border: 1px solid rgba(56, 189, 248, 0.2);
    color: var(--accent);
    text-transform: uppercase;
}

.section-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    background: rgba(37, 99, 235, 0.1);
    border: 1px solid rgba(37, 99, 235, 0.2);
    color: var(--primary);
    text-transform: uppercase;
    margin-bottom: 16px;
}

.section-title { margin-bottom: 16px; }
.section-subtitle {
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto 48px;
}
.text-center { text-align: center; }

/* Buttons */
.btn-cooca {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 32px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    cursor: pointer;
    transition: all var(--transition);
    position: relative;
    overflow: hidden;
    text-decoration: none;
    white-space: nowrap;
}

.btn-cooca-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    box-shadow: 0 4px 20px rgba(37, 99, 235, 0.3);
}

.btn-cooca-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(37, 99, 235, 0.45);
    color: #fff;
}

.btn-cooca-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
}

.btn-cooca-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(16, 185, 129, 0.45);
    color: #fff;
}

.btn-cooca-outline {
    background: transparent;
    color: var(--text);
    border: 1px solid var(--border);
}

.btn-cooca-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
    transform: translateY(-2px);
}

.btn-cooca-sm {
    padding: 10px 22px;
    font-size: 0.85rem;
    border-radius: 10px;
}

/* Ripple Effect */
.btn-cooca .btn-ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to { transform: scale(4); opacity: 0; }
}

/* Cards */
.card-3d {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 32px;
    transition: all var(--transition);
    position: relative;
    overflow: hidden;
    transform-style: preserve-3d;
    perspective: 1000px;
}

.card-3d::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--accent), transparent);
    opacity: 0;
    transition: opacity var(--transition);
}

.card-3d:hover::before { opacity: 1; }

.card-3d:hover {
    transform: translateY(-8px);
    border-color: rgba(56, 189, 248, 0.3);
    box-shadow: 0 20px 60px rgba(56, 189, 248, 0.08), var(--shadow);
}

.card-3d .card-glow {
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: radial-gradient(circle, rgba(56, 189, 248, 0.05) 0%, transparent 60%);
    pointer-events: none;
    opacity: 0;
    transition: opacity var(--transition);
}

.card-3d:hover .card-glow { opacity: 1; }

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes float-delay {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

@keyframes float-slow {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse-scale {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.05); }
    100% { opacity: 1; transform: scale(1); }
}

@keyframes fade-in-scale {
    0% { opacity: 0; transform: scale(0.8); }
    100% { opacity: 1; transform: scale(1); }
}

.float-anim { animation: float 6s ease-in-out infinite; }
.float-anim-delay { animation: float-delay 5s ease-in-out 1s infinite; }

.reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.reveal.revealed {
    opacity: 1;
    transform: translateY(0);
}

.reveal-delay-1 { transition-delay: 0.1s; }
.reveal-delay-2 { transition-delay: 0.2s; }
.reveal-delay-3 { transition-delay: 0.3s; }
.reveal-delay-4 { transition-delay: 0.4s; }
.reveal-delay-5 { transition-delay: 0.5s; }

/* Grid Background */
.grid-bg {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(56, 189, 248, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(56, 189, 248, 0.03) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

/* Responsive */
@media (max-width: 767.98px) {
    .section-padding { padding: 60px 0; }
}
</style>
