/** @type {import('tailwindcss').Config} */
import flowbitePlugin from "flowbite/plugin";
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
      //it's for Flowbite
      "./node_modules/flowbite/**/*.js",

      // it's optional for laravel pagination
      "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [
      flowbitePlugin,
  ],
};
