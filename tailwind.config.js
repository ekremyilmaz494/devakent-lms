import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Oxanium', ...defaultTheme.fontFamily.sans],
                serif: ['Merriweather', ...defaultTheme.fontFamily.serif],
                mono: ['Fira Code', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                /* Remap gray → stone for warm tones */
                gray: colors.stone,

                /* Primary (CSS variable-based, amber-brown) */
                primary: {
                    50: 'rgb(var(--primary-50) / <alpha-value>)',
                    100: 'rgb(var(--primary-100) / <alpha-value>)',
                    200: 'rgb(var(--primary-200) / <alpha-value>)',
                    300: 'rgb(var(--primary-300) / <alpha-value>)',
                    400: 'rgb(var(--primary-400) / <alpha-value>)',
                    500: 'rgb(var(--primary-500) / <alpha-value>)',
                    600: 'rgb(var(--primary-600) / <alpha-value>)',
                    700: 'rgb(var(--primary-700) / <alpha-value>)',
                    800: 'rgb(var(--primary-800) / <alpha-value>)',
                    900: 'rgb(var(--primary-900) / <alpha-value>)',
                    950: 'rgb(var(--primary-950) / <alpha-value>)',
                },
            },
            borderRadius: {
                DEFAULT: '0.3rem',
            },
            keyframes: {
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(30px) scale(0.95)' },
                    '100%': { opacity: '1', transform: 'translateY(0) scale(1)' },
                },
                'float': {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-6px)' },
                },
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.6s ease-out both',
                'float': 'float 3s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
