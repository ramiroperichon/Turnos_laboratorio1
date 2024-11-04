import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    darkMode: 'class',
    important: true,
    presets: [preset],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './app/Filament/**/*.php',
        './app/Livewire/**/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './app/Livewire/**/*Table.php',
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php', // 👈
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'custom-bg-table': '#191c24',
                'fi-ta-table': {
                    DEFAULT: '#191c24', // Main table background
                    'row': { // Row backgrounds
                        'stripe': '#222222', // Striped row background
                        'hover': '#2a2a2a', // Hover state
                        'selected': '#333333', // Selected state
                    },
                },
            },
        },
    },
    plugins: [forms, require('@tailwindcss/typography'), require('@tailwindcss/forms')],
}
