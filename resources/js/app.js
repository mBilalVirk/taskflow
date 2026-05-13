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
