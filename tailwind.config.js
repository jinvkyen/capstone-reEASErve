import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                mont: ["Montserrat", "sans-serif"],
            },
            colors: {
                100: "#E9F3FD", //for bg
                200: "#7B7A7A", //gray texts
                300: "#2669D5", //for buttons
                400: "#969696", //darker gray texts
                gray: {
                    600: "#4A4A4A", // example gray color
                },
                red: {
                    600: "#C53030", // example red color
                },
                yellow: {
                    600: "#D69E2E", // example yellow color
                },
                green: {
                    500: "#22c55e",
                },

                dynamic: "var(--dynamic-background-color)", //For Dynamic Color
            },
            screens: {
                sm: "300px",
            },
            transitionProperty: {
                "max-height": "max-height",
            },
        },
    },
    plugins: [require("@tailwindcss/line-clamp")],
};
