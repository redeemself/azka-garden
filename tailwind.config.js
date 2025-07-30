import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: ['./resources/views/**/*.blade.php', './resources/js/**/*.js', './resources/**/*.vue'],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#166534', // Keep original primary color
                    dark: '#15803d', // green-700
                    light: '#22c55e', // green-500
                },
                secondary: {
                    DEFAULT: '#4ade80', // Keep original secondary color
                    dark: '#059669', // emerald-600
                    light: '#86efac', // green-300
                },
                'gray-bg': '#f9fafb', // custom light gray
            },
            fontFamily: {
                sans: [
                    'Inter',
                    '-apple-system',
                    'BlinkMacSystemFont',
                    'Segoe UI',
                    'Roboto',
                    'Oxygen',
                    'Ubuntu',
                    'Cantarell',
                    'Open Sans',
                    'Helvetica Neue',
                    'sans-serif',
                    'Apple Color Emoji',
                    'Segoe UI Emoji',
                    'Segoe UI Symbol',
                    'Noto Color Emoji',
                ],
            },
            keyframes: {
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in-down': {
                    '0%': { opacity: '0', transform: 'translateY(-20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'bounce-slow': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5%)' },
                },
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.8s ease-out forwards',
                'fade-in-down': 'fade-in-down 0.8s ease-out forwards',
                'bounce-slow': 'bounce-slow 2s infinite',
            },
        },
    },
    plugins: [typography],
    /**
     * Azka Garden Tailwind Configuration
     * @updated 2025-07-30 04:09:39 by mulyadafa
     *
     * Changes:
     * - Added @tailwindcss/typography plugin for rich text styling
     * - Expanded color palette with dark and light variants
     * - Added custom font stack with Inter as primary font
     * - Added custom animations and keyframes
     * - Optimized content patterns for better performance
     */
};
