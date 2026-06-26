<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @if(setting('site.favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset(setting('site.favicon')) }}">
    @endif
    @include('partials.seo')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/premium.css') }}" rel="stylesheet" />
    @stack('styles')
    @stack('head')
</head>
<body>
    @include('partials.header')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
      // ======= SHARED SYSTEM (sama persis di setiap halaman) =======
      (function () {
        "use strict";
        window.addEventListener("load", function () {
          setTimeout(function () {
            const loader = document.getElementById("pageLoader");
            if (loader) loader.classList.add("hidden");
          }, 1200);
        });

        const html = document.documentElement;
        const themeToggle = document.getElementById("themeToggle");
        const themeToggleMobile = document.getElementById("themeToggleMobile");
        const themeIcon = document.getElementById("themeIcon");
        const themeIconMobile = document.getElementById("themeIconMobile");

        function getSystemTheme() {
          return window.matchMedia("(prefers-color-scheme: light)").matches
            ? "light"
            : "dark";
        }
        function setTheme(theme) {
          html.setAttribute("data-theme", theme);
          localStorage.setItem("cooca-theme", theme);
          const icon = theme === "dark" ? "bi-moon-fill" : "bi-sun-fill";
          if (themeIcon) themeIcon.className = "bi " + icon;
          if (themeIconMobile) themeIconMobile.className = "bi " + icon;

          // Toggle light/dark logos and preloaders
          document.querySelectorAll('.nav-logo-light, .loader-img-light').forEach(function(el) {
            el.style.display = theme === 'light' ? 'block' : 'none';
          });
          document.querySelectorAll('.nav-logo-dark, .loader-img-dark').forEach(function(el) {
            el.style.display = theme === 'dark' ? 'block' : 'none';
          });
        }
        const savedTheme = localStorage.getItem("cooca-theme");
        setTheme(savedTheme || getSystemTheme());
        window
          .matchMedia("(prefers-color-scheme: light)")
          .addEventListener("change", function (e) {
            if (!localStorage.getItem("cooca-theme"))
              setTheme(e.matches ? "light" : "dark");
          });
        function toggleTheme() {
          setTheme(
            html.getAttribute("data-theme") === "dark" ? "light" : "dark",
          );
        }
        if (themeToggle) themeToggle.addEventListener("click", toggleTheme);
        if (themeToggleMobile)
          themeToggleMobile.addEventListener("click", toggleTheme);

        const nav = document.getElementById("mainNav");
        if (nav) {
          window.addEventListener(
            "scroll",
            function () {
              nav.classList.toggle("scrolled", window.pageYOffset > 50);
            },
            { passive: true },
          );
        }

        const revealElements = document.querySelectorAll(".reveal");
        const revealObserver = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) {
                entry.target.classList.add("revealed");
                revealObserver.unobserve(entry.target);
              }
            });
          },
          { threshold: 0.1, rootMargin: "0px 0px -50px 0px" },
        );
        revealElements.forEach(function (el) {
          revealObserver.observe(el);
        });

        document.querySelectorAll(".btn-cooca").forEach(function (btn) {
          btn.addEventListener("click", function (e) {
            const rect = btn.getBoundingClientRect();
            const ripple = document.createElement("span");
            ripple.classList.add("btn-ripple");
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + "px";
            ripple.style.left = e.clientX - rect.left - size / 2 + "px";
            ripple.style.top = e.clientY - rect.top - size / 2 + "px";
            btn.appendChild(ripple);
            setTimeout(function () {
              ripple.remove();
            }, 600);
          });
        });
      })();

      // ======= PAGE-SPECIFIC SCRIPTS (index.html) =======
      (function () {
        // Counter animation
        const counters = document.querySelectorAll(".counter");
        let countersAnimated = false;
        function animateCounters() {
          if (countersAnimated) return;
          countersAnimated = true;
          counters.forEach(function (counter) {
            const target = parseFloat(counter.getAttribute("data-target"));
            const isDecimal = counter.getAttribute("data-decimal") === "true";
            const duration = 2000;
            const start = performance.now();
            function update(currentTime) {
              const elapsed = currentTime - start;
              const progress = Math.min(elapsed / duration, 1);
              const eased = 1 - Math.pow(1 - progress, 3);
              const current = eased * target;
              counter.textContent = isDecimal
                ? current.toFixed(1)
                : Math.floor(current).toLocaleString();
              if (progress < 1) requestAnimationFrame(update);
              else
                counter.textContent = isDecimal
                  ? target.toFixed(1)
                  : target.toLocaleString();
            }
            requestAnimationFrame(update);
          });
        }
        const counterSection = document.getElementById("counters");
        if (counterSection) {
          const counterObserver = new IntersectionObserver(
            function (entries) {
              if (entries[0].isIntersecting) {
                animateCounters();
                counterObserver.unobserve(counterSection);
              }
            },
            { threshold: 0.3 },
          );
          counterObserver.observe(counterSection);
        }

        // Card 3D tilt
        document.querySelectorAll(".card-3d").forEach(function (card) {
          card.addEventListener("mousemove", function (e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = ((y - centerY) / centerY) * -5;
            const rotateY = ((x - centerX) / centerX) * 5;
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
          });
          card.addEventListener("mouseleave", function () {
            card.style.transform = "";
          });
        });

        // Glow card mouse tracking
        document.querySelectorAll(".glow-card").forEach(function (card) {
          card.addEventListener("mousemove", function (e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty("--mouse-x", x + "px");
            card.style.setProperty("--mouse-y", y + "px");
          });
        });

        // Dashboard chart animation
        const chartBars = document.querySelectorAll(".dash-chart-bar");
        const chartObserver = new IntersectionObserver(
          function (entries) {
            if (entries[0].isIntersecting) {
              chartBars.forEach(function (bar, i) {
                const height = bar.style.height;
                bar.style.height = "0%";
                setTimeout(function () {
                  bar.style.height = height;
                }, i * 80);
              });
              chartObserver.unobserve(entries[0].target);
            }
          },
          { threshold: 0.5 },
        );
        const chartEl = document.querySelector(".dash-chart");
        if (chartEl) chartObserver.observe(chartEl);

        // Parallax orbs
        const orbs = document.querySelectorAll(".hero-bg-orb, .mesh-orb");
        window.addEventListener(
          "scroll",
          function () {
            const scrollY = window.pageYOffset;
            orbs.forEach(function (orb, i) {
              const speed = 0.05 + i * 0.03;
              orb.style.transform = "translateY(" + scrollY * speed + "px)";
            });
          },
          { passive: true },
        );

        // Contact form
        const contactForm = document.getElementById("contactForm");
        if (contactForm) {
          contactForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML =
              '<i class="bi bi-check-circle"></i> Message Received!';
            btn.style.background = "linear-gradient(135deg, #10B981, #059669)";
            setTimeout(function () {
              btn.innerHTML = originalText;
              btn.style.background = "";
              contactForm.reset();
            }, 3000);
          });
        }
      })();
    </script>
    @stack('scripts')
</body>
</html>


