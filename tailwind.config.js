import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
import flowbitePlugin from "flowbite/plugin";

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        //it's for Flowbite
        "./node_modules/flowbite/**/*.js",
        // it's optional for laravel pagination
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php"
    ],

    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: {
                    "50": "#f8f2f1",
                    "100": "#f0e0de",
                    "200": "#e0c1bc",
                    "300": "#cc9b93",
                    "400": "#b3766b",
                    "500": "#965449",
                    "600": "#7d4037",
                    "700": "#65322c",
                    "800": "#4e2723",
                    "900": "#3a1d1a",
                    "950": "#220f0d"
                }
            }
        },
        fontFamily: {
            'body': [
                'Inter',
                'ui-sans-serif',
                'system-ui',
                '-apple-system',
                'system-ui',
                'Segoe UI',
                'Roboto',
                'Helvetica Neue',
                'Arial',
                'Noto Sans',
                'sans-serif',
                'Apple Color Emoji',
                'Segoe UI Emoji',
                'Segoe UI Symbol',
                'Noto Color Emoji'
            ],
            'sans': [
                'Inter',
                'ui-sans-serif',
                'system-ui',
                '-apple-system',
                'system-ui',
                'Segoe UI',
                'Roboto',
                'Helvetica Neue',
                'Arial',
                'Noto Sans',
                'sans-serif',
                'Apple Color Emoji',
                'Segoe UI Emoji',
                'Segoe UI Symbol',
                'Noto Color Emoji'
            ]
        }
    },

    plugins: [forms, flowbitePlugin],
};
