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
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'ujian-blue': '#2563EB', // Biru untuk tombol dan nomor aktif
                'ujian-green-bg': '#2C7A7B', // Background jawaban terpilih
                'main-bg': '#2C7A7B', // Main background
                'card-diff-bg': '#bbd4d5',
                'main-blue-button': '#1976d2', // Main Button
                'ujian-green-border': '#2C7A7B', // Border jawaban terpilih
                'ujian-gray': {
                    100: '#F3F4F6', // Latar belakang body
                    200: '#E5E7EB', // Border
                    500: '#6B7280', // Teks sekunder
                    700: '#374151', // Teks utama
                }
              }
        },
    },

    plugins: [forms],
};
