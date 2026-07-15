<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @if (setting('site.favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset(setting('site.favicon')) }}">
    @endif
    @include('partials.seo')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="{{ asset('css/premium.css') }}" rel="stylesheet" />
    @stack('styles')
</head>

<body>
    @include('partials.header')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            "use strict";

            /** THEME ENGINE **/
            var html = document.documentElement;
            var savedTheme = localStorage.getItem("cooca-theme") || (window.matchMedia("(prefers-color-scheme: light)")
                .matches ? "light" : "dark");
            html.setAttribute("data-theme", savedTheme);

            function applyTheme(theme) {
                html.setAttribute("data-theme", theme);
                localStorage.setItem("cooca-theme", theme);
                var isDark = theme === "dark";
                document.querySelectorAll(".theme-icon-light").forEach(function(el) {
                    el.style.display = isDark ? "none" : "";
                });
                document.querySelectorAll(".theme-icon-dark").forEach(function(el) {
                    el.style.display = isDark ? "" : "none";
                });
                document.querySelectorAll(".nav-logo-light, .loader-img-light").forEach(function(el) {
                    el.style.display = isDark ? "none" : "";
                });
                document.querySelectorAll(".nav-logo-dark, .loader-img-dark").forEach(function(el) {
                    el.style.display = isDark ? "" : "none";
                });
            }
            applyTheme(savedTheme);

            document.querySelectorAll("[data-toggle-theme]").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    var current = html.getAttribute("data-theme");
                    applyTheme(current === "dark" ? "light" : "dark");
                });
            });

            /** PAGE LOADER **/
            window.addEventListener("load", function() {
                setTimeout(function() {
                    var loader = document.getElementById("pageLoader");
                    if (loader) {
                        loader.classList.add("hidden");
                    }
                }, 1000);
            });

            /** STICKY NAVBAR **/
            var navbar = document.querySelector(".navbar");
            if (navbar) {
                window.addEventListener("scroll", function() {
                    navbar.classList.toggle("scrolled", window.pageYOffset > 40);
                }, {
                    passive: true
                });
                if (window.pageYOffset > 40) {
                    navbar.classList.add("scrolled");
                }
            }

            /** SCROLL REVEAL **/
            var revealEls = document.querySelectorAll(".reveal");
            if (revealEls.length > 0 && "IntersectionObserver" in window) {
                var revealObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("revealed");
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: "0px 0px -40px 0px"
                });
                revealEls.forEach(function(el) {
                    revealObserver.observe(el);
                });
            }

            /** BUTTON RIPPLE EFFECT **/
            document.addEventListener("click", function(e) {
                var btn = e.target.closest(".btn");
                if (!btn) {
                    return;
                }
                var ripple = document.createElement("span");
                ripple.classList.add("ripple");
                var rect = btn.getBoundingClientRect();
                var size = Math.max(rect.width, rect.height);
                ripple.style.width = ripple.style.height = size + "px";
                ripple.style.left = (e.clientX - rect.left - size / 2) + "px";
                ripple.style.top = (e.clientY - rect.top - size / 2) + "px";
                btn.appendChild(ripple);
                setTimeout(function() {
                    ripple.remove();
                }, 600);
            });

            /** COUNTER ANIMATION **/
            var counterSection = document.getElementById("counters");
            if (counterSection && "IntersectionObserver" in window) {
                var animated = false;
                var counterObserver = new IntersectionObserver(function(entries) {
                    if (entries[0].isIntersecting && !animated) {
                        animated = true;
                        document.querySelectorAll(".counter").forEach(function(counter) {
                            var target = parseFloat(counter.getAttribute("data-target"));
                            var isDecimal = counter.getAttribute("data-decimal") === "true";
                            var duration = 2000;
                            var startTime = performance.now();

                            function updateCounter(now) {
                                var elapsed = now - startTime;
                                var progress = Math.min(elapsed / duration, 1);
                                var eased = 1 - Math.pow(1 - progress, 3);
                                var current = eased * target;
                                if (isDecimal) {
                                    counter.textContent = current.toFixed(1);
                                } else {
                                    counter.textContent = Math.floor(current).toLocaleString();
                                }
                                if (progress < 1) {
                                    requestAnimationFrame(updateCounter);
                                } else {
                                    counter.textContent = isDecimal ? target.toFixed(1) : target
                                        .toLocaleString();
                                }
                            }
                            requestAnimationFrame(updateCounter);
                        });
                        counterObserver.unobserve(counterSection);
                    }
                }, {
                    threshold: 0.3
                });
                counterObserver.observe(counterSection);
            }

            /** GLOW CARD MOUSE TRACKING **/
            document.querySelectorAll(".card-hover-glow").forEach(function(card) {
                card.addEventListener("mousemove", function(e) {
                    var r = card.getBoundingClientRect();
                    card.style.setProperty("--mouse-x", (e.clientX - r.left) + "px");
                    card.style.setProperty("--mouse-y", (e.clientY - r.top) + "px");
                });
            });

            /** 3D CARD TILT **/
            document.querySelectorAll(".card-3d").forEach(function(card) {
                card.addEventListener("mousemove", function(e) {
                    var r = card.getBoundingClientRect();
                    var cx = r.width / 2;
                    var cy = r.height / 2;
                    var rx = ((e.clientY - r.top - cy) / cy) * -4;
                    var ry = ((e.clientX - r.left - cx) / cx) * 4;
                    card.style.transform = "perspective(1000px) rotateX(" + rx + "deg) rotateY(" + ry +
                        "deg) translateY(-6px)";
                });
                card.addEventListener("mouseleave", function() {
                    card.style.transform = "";
                });
            });

            /** PASSWORD TOGGLE **/
            document.querySelectorAll(".input-toggle").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    var targetSelector = this.getAttribute("data-target");
                    var input = document.querySelector(targetSelector);
                    if (!input) {
                        return;
                    }
                    var isPassword = input.type === "password";
                    input.type = isPassword ? "text" : "password";
                    var icon = this.querySelector("i");
                    if (icon) {
                        icon.className = "bi " + (isPassword ? "bi-eye-slash" : "bi-eye");
                    }
                });
            });

            /** PARALLAX ORBS **/
            var orbs = document.querySelectorAll(".hero-orb, .page-hero-orb");
            if (orbs.length > 0) {
                window.addEventListener("scroll", function() {
                    var scrollY = window.pageYOffset;
                    orbs.forEach(function(orb, i) {
                        orb.style.transform = "translateY(" + (scrollY * (0.03 + i * 0.02)) + "px)";
                    });
                }, {
                    passive: true
                });
            }
        })();
    </script>
    @stack('scripts')
</body>

</html>
