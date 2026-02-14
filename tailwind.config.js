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

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, flowbitePlugin],
};
