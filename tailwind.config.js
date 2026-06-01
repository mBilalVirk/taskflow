/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class", // ← CRITICAL: Enable class-based dark mode
    content: ["./resources/views/**/*.blade.php", "./resources/js/**/*.js"],
    theme: {
        extend: {
            colors: {
                // Dark mode colors
                "dark-bg": "#0a0e27",
                "dark-card": "#1a1f3a",
                "dark-hover": "#242d4a",
                "neon-cyan": "#00D9FF",
                "neon-purple": "#9D00FF",
                "neon-pink": "#FF006E",
            },
            backgroundColor: {
                "light-bg": "#ffffff",
                "light-card": "#f9fafb",
                "light-hover": "#f3f4f6",
            },
            textColor: {
                "light-text": "#111827",
                "light-secondary": "#6b7280",
            },
            borderColor: {
                "light-border": "#e5e7eb",
                "light-border-dark": "#d1d5db",
            },
            animation: {
                float: "float 6s ease-in-out infinite",
                glow: "glow 3s ease-in-out infinite",
                "pulse-glow": "pulseGlow 2s ease-in-out infinite",
                "slide-up": "slideUp 0.6s ease-out",
                "fade-in": "fadeIn 0.8s ease-out",
            },
            keyframes: {
                float: {
                    "0%, 100%": { transform: "translateY(0px)" },
                    "50%": { transform: "translateY(-20px)" },
                },
                glow: {
                    "0%, 100%": {
                        boxShadow: "0 0 20px rgba(0, 217, 255, 0.3)",
                    },
                    "50%": { boxShadow: "0 0 40px rgba(0, 217, 255, 0.6)" },
                },
                pulseGlow: {
                    "0%, 100%": { opacity: "0.6" },
                    "50%": { opacity: "1" },
                },
                slideUp: {
                    from: { transform: "translateY(40px)", opacity: "0" },
                    to: { transform: "translateY(0)", opacity: "1" },
                },
                fadeIn: {
                    from: { opacity: "0" },
                    to: { opacity: "1" },
                },
            },
        },
    },
    plugins: [],
};
