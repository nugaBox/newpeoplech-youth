/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./*.php", "./*.html", "./js/*.js", "./css/*.css"],
    theme: {
        extend: {
            fontFamily: {
                pretendard: ["Pretendard Variable", "sans-serif"],
            },
            colors: {
                "custom-gray": {
                    50: "#f6f8fb",
                    100: "#f3f4f6",
                    200: "#e5e7eb",
                    300: "#d1d5db",
                    400: "#9ca3af",
                    500: "#6b7280",
                    600: "#4b5563",
                    700: "#374151",
                    800: "#1f2937",
                    900: "#111827",
                },
            },
        },
    },
    plugins: [],
};
