import "./bootstrap";
import "../css/app.css";
import "../css/public.css";

import Alpine from "alpinejs";
import Swal from "sweetalert2";

window.Alpine = Alpine;
Alpine.start();

// Configure SweetAlert2 with Pristine Light Enterprise Theme
window.Swal = Swal.mixin({
    customClass: {
        popup: "rounded-3xl border border-slate-200 bg-white shadow-2xl p-6 text-slate-900",
        title: "text-xl font-bold text-slate-900 tracking-tight",
        htmlContainer: "text-sm text-slate-600 font-medium mt-2",
        confirmButton:
            "inline-flex justify-center items-center px-5 py-2.5 text-sm font-extrabold rounded-xl shadow-lg text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all",
        cancelButton:
            "inline-flex justify-center items-center px-5 py-2.5 text-sm font-bold rounded-xl border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition-all",
        actions: "gap-3 mt-4",
    },
    buttonsStyling: false,
});

// Toast Notification Helper
window.showToast = function (message, type = "info", duration = 4000) {
    const container = document.getElementById("toastContainer");
    if (!container) return;

    const toast = document.createElement("div");
    const bgClass =
        type === "success"
            ? "bg-emerald-600 text-white"
            : type === "error"
            ? "bg-rose-600 text-white"
            : type === "warning"
            ? "bg-amber-500 text-white"
            : "bg-indigo-600 text-white";

    toast.className = `flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl ${bgClass} font-semibold text-sm transition-all transform translate-y-2 opacity-0 duration-300 z-50`;
    toast.innerHTML = `
        <i class="fa-solid fa-${
            type === "success"
                ? "circle-check"
                : type === "error"
                ? "circle-exclamation"
                : type === "warning"
                ? "triangle-exclamation"
                : "circle-info"
        } text-lg"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove("translate-y-2", "opacity-0");
    });

    setTimeout(() => {
        toast.classList.add("opacity-0", "-translate-y-2");
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

// Initialize App Features & Smooth Interactivity
document.addEventListener("DOMContentLoaded", () => {
    // 1. FontAwesome / Icon helper auto check
    console.log("Cooca.id Pristine Light Theme Assets Loaded.");

    // 2. Smooth reveal animation for Bento cards
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("opacity-100", "translate-y-0");
                    entry.target.classList.remove("opacity-0", "translate-y-4");
                }
            });
        },
        { threshold: 0.1 }
    );

    document.querySelectorAll(".bento-card").forEach((card) => {
        card.classList.add("transition-all", "duration-500");
        observer.observe(card);
    });
});
