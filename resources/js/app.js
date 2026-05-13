import "./bootstrap";
import "../css/app.css";

// Handle Laravel Flash Messages
document.addEventListener("DOMContentLoaded", () => {
    const flash = document.body.dataset;

    setTimeout(() => {
        if (flash.success) showToast(flash.success, "success");
        if (flash.error) showToast(flash.error, "error");
        if (flash.warning) showToast(flash.warning, "warning");
        if (flash.info) showToast(flash.info, "info");
    }, 300);
});

document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("notificationBtn");
    const dropdown = document.getElementById("notificationDropdown");
    const dot = document.getElementById("notificationDot");

    if (!btn || !dropdown) return;

    // Toggle dropdown
    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdown.classList.toggle("hidden");

        // Hide red dot when opened
        if (dot) dot.style.display = "none";
    });

    // Close when clicking outside
    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
            dropdown.classList.add("hidden");
        }
    });
});
