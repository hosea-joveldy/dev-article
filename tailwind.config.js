import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                ink: '#242424',
                'ink-strong': '#191919',
                muted: '#6b6b6b',
                line: '#e5e5e5',
                cream: '#f7f4ed',
                paper: '#ffffff',
                soft: '#f2f2f2',
                accent: {
                    DEFAULT: '#1a8917',
                    dark: '#146b12',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', ...defaultTheme.fontFamily.sans],
                serif: ['Georgia', 'Times New Roman', ...defaultTheme.fontFamily.serif],
            },
            maxWidth: {
                container: '1180px',
            },
        },
    },

    plugins: [forms],
};
