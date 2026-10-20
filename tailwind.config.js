import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    // The admin pages are light-only; dark: styles apply only if a page opts in with class="dark".
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // IoT-ra brand palette (same as resources/views/partials/admin-theme.blade.php); 700 = logo navy.
            colors: {
                brand: {
                    50: '#EEF4FB', 100: '#DCE8F6', 200: '#B9D1EE', 300: '#8CB3E1', 400: '#5A8FCF',
                    500: '#2F6DB8', 600: '#0F559F', 700: '#034486', 800: '#04366B', 900: '#04294F', 950: '#021A33',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
