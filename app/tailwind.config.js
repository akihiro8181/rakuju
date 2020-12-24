const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            
            colors: {
                'dawn-orange':  '#eb6138ff',
                'sunrise-glow': '#f18e1dff',
                'blue-shadow':  '#404b73ff',
                'warm-white':   '#f9ead2ff',
                'ridge-gray':   '#7d93baff',
                
                'heavenly-blue':'#3362A8',
                'early-morning':'#8489b6',
                'cloud-sea':    '#ccdfef',
                'clear-blue':   '#a1c3e7',
            },
        },
    },

    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
        textColor: ['visited'],
    },

    plugins: [require('@tailwindcss/ui')],
};
