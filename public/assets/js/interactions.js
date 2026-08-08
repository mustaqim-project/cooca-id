document.addEventListener('DOMContentLoaded', () => {
    // Accessibility check: Prefers Reduced Motion (Pri 7)
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Scroll Reveal with IntersectionObserver & Spatial Continuity
    const revealElements = document.querySelectorAll('.reveal-on-scroll');
    
    if (prefersReducedMotion) {
        // Fallback for accessibility: no animations
        revealElements.forEach(el => el.classList.add('revealed'));
    } else {
        const revealOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };

        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Slight delay for spatial continuity staggered effect could be added here
                    requestAnimationFrame(() => {
                        entry.target.classList.add('revealed');
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, revealOptions);

        revealElements.forEach(el => {
            el.classList.add('reveal-pending');
            revealObserver.observe(el);
        });
    }

    // Ripple Effect for Buttons (Debounced to prevent Main Thread Thrashing)
    const rippleButtons = document.querySelectorAll('.btn-cooca');
    rippleButtons.forEach(button => {
        button.addEventListener('mousedown', function(e) {
            // Prevent multiple ripples from thrashing the DOM
            if (this.querySelector('.ripple-effect')) return;

            const rect = e.target.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            this.appendChild(ripple);
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});
