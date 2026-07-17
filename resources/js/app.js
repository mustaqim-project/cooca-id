import "./bootstrap";
import "../css/app.css";

import Alpine from "alpinejs";
import Swal from "sweetalert2";
import { initTheme, toggleTheme } from "./ui/theme.js";
import { initAlerts } from "./ui/alerts.js";
import { initFormConfirmations } from "./ui/forms.js";

window.Alpine = Alpine;
Alpine.start();

// Configure SweetAlert2 with modern styles
window.Swal = Swal.mixin({
    customClass: {
        popup: "rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 shadow-xl",
        title: "text-lg font-heading font-semibold text-surface-900 dark:text-white",
        htmlContainer: "text-sm text-surface-600 dark:text-surface-300",
        confirmButton:
            "inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors",
        cancelButton:
            "inline-flex justify-center items-center px-4 py-2 border border-surface-300 dark:border-surface-600 text-sm font-medium rounded-lg text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors",
        actions: "gap-3",
    },
    buttonsStyling: false,
});

// Toast Notification Helper
window.showToast = function (message, type = "info", duration = 5000) {
    const container = document.getElementById("toastContainer");
    if (!container) return;

    const toast = document.createElement("div");
    toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
    toast.setAttribute("role", "alert");
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-${type === "success" ? "check-circle" : type === "error" ? "exclamation-triangle" : type === "warning" ? "exclamation-circle" : "info-circle"} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

// Initialize App
document.addEventListener("DOMContentLoaded", () => {
    // 1. Initialize UI modules
    initTheme();
    initAlerts();
    initFormConfirmations();

    // Auto-initialize Lucide if loaded via CDN
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }

    // 2. Page Loader
    const loader = document.getElementById("pageLoader");
    if (loader) {
        setTimeout(() => {
            loader.classList.add("loaded");
            setTimeout(() => loader.remove(), 300);
        }, 500);
    }

    // 3. Fullscreen Toggle
    document
        .getElementById("fullscreenToggle")
        ?.addEventListener("click", function () {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch((err) => {
                    console.log("Fullscreen request failed:", err);
                });
                this.querySelector("i")?.classList.replace(
                    "bi-fullscreen",
                    "bi-fullscreen-exit",
                );
            } else {
                document.exitFullscreen();
                this.querySelector("i")?.classList.replace(
                    "bi-fullscreen-exit",
                    "bi-fullscreen",
                );
            }
        });

    // 4. Sidebar Toggle & Collapse
    const sidebar = document.getElementById("appSidebar");
    const main = document.getElementById("appMain");
    const backdrop = document.getElementById("sidebarBackdrop");
    const toggle = document.getElementById("sidebarToggle");
    const collapseBtn = document.getElementById("sidebarCollapseBtn");

    function openSidebar() {
        sidebar?.classList.add("open");
        backdrop?.classList.add("show");
        toggle?.setAttribute("aria-expanded", "true");
    }

    function closeSidebar() {
        sidebar?.classList.remove("open");
        backdrop?.classList.remove("show");
        toggle?.setAttribute("aria-expanded", "false");
    }

    function toggleSidebar() {
        if (window.innerWidth >= 992) {
            sidebar?.classList.toggle("collapsed");
            main?.classList.toggle("sidebar-collapsed");
            localStorage.setItem(
                "sidebarCollapsed",
                sidebar?.classList.contains("collapsed"),
            );

            // Update collapse button icon
            const icon = collapseBtn?.querySelector("i");
            if (sidebar?.classList.contains("collapsed")) {
                icon?.classList.replace("bi-chevron-left", "bi-chevron-right");
            } else {
                icon?.classList.replace("bi-chevron-right", "bi-chevron-left");
            }
        } else {
            sidebar?.classList.contains("open")
                ? closeSidebar()
                : openSidebar();
        }
    }

    toggle?.addEventListener("click", toggleSidebar);
    collapseBtn?.addEventListener("click", toggleSidebar);
    backdrop?.addEventListener("click", closeSidebar);

    // Restore collapsed state on desktop
    if (
        window.innerWidth >= 992 &&
        localStorage.getItem("sidebarCollapsed") === "true"
    ) {
        sidebar?.classList.add("collapsed");
        main?.classList.add("sidebar-collapsed");
        collapseBtn
            ?.querySelector("i")
            ?.classList.replace("bi-chevron-left", "bi-chevron-right");
    }

    // 5. Menu Search
    document
        .getElementById("menuSearchInput")
        ?.addEventListener("input", function (e) {
            const query = e.target.value.toLowerCase();
            const navItems = document.querySelectorAll(".sidebar-nav-item");

            navItems.forEach((item) => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(query) ? "" : "none";
            });
        });

    // 6. Animate Elements on Scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("fade-in-up");
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll(".card-saas, .stat-card").forEach((el) => {
        el.style.opacity = "0"; // Initial state before animation
        observer.observe(el);
    });
});

// Global Keyboard Shortcuts
document.addEventListener("keydown", function (e) {
    // Ctrl+K for global search
    if ((e.ctrlKey || e.metaKey) && e.key === "k") {
        e.preventDefault();
        document.getElementById("globalSearchInput")?.focus();
    }
    // Escape to close sidebar on mobile
    if (e.key === "Escape") {
        const sidebar = document.getElementById("appSidebar");
        const backdrop = document.getElementById("sidebarBackdrop");
        const toggle = document.getElementById("sidebarToggle");

        sidebar?.classList.remove("open");
        backdrop?.classList.remove("show");
        toggle?.setAttribute("aria-expanded", "false");
    }
});
