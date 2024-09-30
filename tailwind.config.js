import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php", // Bladeテンプレート
        "./resources/**/*.js", // JavaScriptファイル
    ],

    theme: {
        extend: {},
    },

    plugins: [forms],
};
