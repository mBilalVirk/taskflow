const sidebar = document.getElementById("sidebar");
const menuButton = document.getElementById("mobile-menu-button");
const overlay = document.getElementById("mobile-overlay");

function toggleSidebar() {
    sidebar.classList.toggle("open");
    overlay.classList.toggle("hidden");
}

// Hamburger Button Click
menuButton.addEventListener("click", toggleSidebar);

// Close sidebar when clicking overlay
overlay.addEventListener("click", toggleSidebar);

// Close sidebar when clicking a link (optional)
document.querySelectorAll("#sidebar a").forEach((link) => {
    link.addEventListener("click", () => {
        if (window.innerWidth < 768) {
            sidebar.classList.remove("open");
            overlay.classList.add("hidden");
        }
    });
});

// Close on escape key
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && window.innerWidth < 768) {
        sidebar.classList.remove("open");
        overlay.classList.add("hidden");
    }
});
// Close dropdowns when clicking outside
document.addEventListener("click", function (event) {
    if (!event.target.closest("[onclick]")) {
        document.getElementById("team-dropdown")?.classList.add("hidden");
        document.getElementById("user-menu")?.classList.add("hidden");
    }
});
