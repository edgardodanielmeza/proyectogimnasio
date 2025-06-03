/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')

export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Http/Livewire/**/*.php",
    "./app/Livewire/**/*.php", // Path where components were actually created
    "./app/View/Components/**/*.php",
    "./storage/framework/views/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          light: '#67e8f9', // cyan-300
          DEFAULT: '#06b6d4', // cyan-500
          dark: '#0e7490',  // cyan-700
        },
        secondary: {
          light: '#fde047', // yellow-300
          DEFAULT: '#facc15', // yellow-400
          dark: '#eab308',  // yellow-500
        },
        accent: {
          light: '#fb923c', // orange-400
          DEFAULT: '#f97316', // orange-500
          dark: '#ea580c',  // orange-600
        },
        neutral: {
          100: '#f8fafc', // slate-50
          200: '#f1f5f9', // slate-100
          300: '#e2e8f0', // slate-200
          400: '#cbd5e1', // slate-300
          500: '#94a3b8', // slate-400
          600: '#64748b', // slate-500
          700: '#475569', // slate-600
          800: '#334155', // slate-700
          900: '#1e293b', // slate-800
        },
        success: {
          light: '#86efac', // green-300
          DEFAULT: '#22c55e', // green-500
          dark: '#15803d',  // green-700
        },
        warning: {
          light: '#fcd34d', // amber-300
          DEFAULT: '#f59e0b', // amber-500
          dark: '#b45309',  // amber-700
        },
        danger: {
          light: '#fca5a5', // red-300
          DEFAULT: '#ef4444', // red-500
          dark: '#b91c1c',  // red-700
        },
        info: {
          light: '#93c5fd', // blue-300
          DEFAULT: '#3b82f6', // blue-500
          dark: '#1d4ed8',  // blue-700
        }
      },
      fontFamily: {
        sans: ['Figtree', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', '"Noto Sans"', 'sans-serif', '"Apple Color Emoji"', '"Segoe UI Emoji"', '"Segoe UI Symbol"', '"Noto Color Emoji"'],
        // display: ['Georgia', 'serif'], // Example for a display font
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
