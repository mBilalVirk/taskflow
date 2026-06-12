import "./bootstrap";
// import "./echo";
import "./utils/kanban";
// import "../css/app.css";

const themeIconMap = {
    dark: "fa-sun",
    light: "fa-moon",
};

function updateThemeIcon(theme) {
    const icon = document.getElementById("theme-toggle-icon");
    if (!icon) return;
    icon.classList.remove("fa-sun", "fa-moon");
    icon.classList.add(themeIconMap[theme] ?? "fa-sun");
}

function setAppTheme(theme) {
    const body = document.body;
    if (!body) return;

    if (theme === "dark") {
        body.classList.add("dark", "bg-dark-bg", "text-white");
        body.classList.remove("light", "bg-white", "text-gray-900");
    } else {
        body.classList.add("light", "bg-white", "text-gray-900");
        body.classList.remove("dark", "bg-dark-bg", "text-white");
    }

    updateThemeIcon(theme);
    localStorage.setItem("theme", theme);
}

function initializeTheme() {
    const savedTheme = localStorage.getItem("theme") || "dark";
    setAppTheme(savedTheme);

    const button = document.getElementById("theme-toggle-btn");
    if (!button) return;

    button.addEventListener("click", () => {
        const currentTheme = document.body.classList.contains("dark")
            ? "dark"
            : "light";
        const nextTheme = currentTheme === "dark" ? "light" : "dark";
        setAppTheme(nextTheme);
    });
}

// Handle Laravel Flash Messages
document.addEventListener("DOMContentLoaded", () => {
    initializeTheme();

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
