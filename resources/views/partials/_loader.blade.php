{{-- Page Loader --}}
<div class="page-loader" id="pageLoader">
    <div class="loader-logo">
        <div class="logo-icon-large">C</div>
        <div class="logo-text">{{ config('app.name', 'COOCA') }}</div>
    </div>
</div>

<style>
.page-loader {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.5s ease, visibility 0.5s ease;
}

.page-loader.hidden {
    opacity: 0;
    visibility: hidden;
}

.loader-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.logo-icon-large {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2.5rem;
    font-weight: 800;
    animation: fade-in-scale 0.8s ease-out, pulse-scale 2s ease-in-out 0.8s infinite;
}

.logo-text {
    font-size: 1.8rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    background: linear-gradient(135deg, var(--accent), var(--primary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: fade-in-scale 1s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pageLoader = document.getElementById('pageLoader');
    if (pageLoader) {
        setTimeout(() => {
            pageLoader.classList.add('hidden');
            setTimeout(() => {
                pageLoader.style.display = 'none';
            }, 500);
        }, 800);
    }
});
</script>
