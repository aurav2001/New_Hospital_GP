/** @type {import('tailwindcss').Config} */

// Colours resolve through CSS variables so the Colors tab in Hospital Settings
// can recolour the whole site without rebuilding this file.
// Each variable holds a space separated RGB triplet, e.g. "8 145 178".
const scale = (name) => ({
  50: `rgb(var(--bs-${name}-50) / <alpha-value>)`,
  100: `rgb(var(--bs-${name}-100) / <alpha-value>)`,
  200: `rgb(var(--bs-${name}-200) / <alpha-value>)`,
  300: `rgb(var(--bs-${name}-300) / <alpha-value>)`,
  400: `rgb(var(--bs-${name}-400) / <alpha-value>)`,
  500: `rgb(var(--bs-${name}-500) / <alpha-value>)`,
  600: `rgb(var(--bs-${name}-600) / <alpha-value>)`,
  700: `rgb(var(--bs-${name}-700) / <alpha-value>)`,
  800: `rgb(var(--bs-${name}-800) / <alpha-value>)`,
  900: `rgb(var(--bs-${name}-900) / <alpha-value>)`,
  950: `rgb(var(--bs-${name}-950) / <alpha-value>)`,
});

export default {
  content: [
    './**/*.php',
    './assets/js/**/*.js',
    '!./node_modules/**',
  ],
  theme: {
    extend: {
      colors: {
        primary: scale('primary'),
        navy: scale('navy'),
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        soft: '0 1px 2px rgba(15, 36, 56, 0.04), 0 8px 24px -12px rgba(15, 36, 56, 0.12)',
        card: '0 1px 3px rgba(15, 36, 56, 0.05), 0 12px 32px -16px rgba(15, 36, 56, 0.18)',
        lift: '0 20px 40px -20px rgb(var(--bs-primary-600) / 0.35)',
      },
      keyframes: {
        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-12px)' } },
        'pulse-ring': { '0%': { transform: 'scale(0.9)', opacity: '0.7' }, '100%': { transform: 'scale(1.8)', opacity: '0' } },
      },
      animation: {
        float: 'float 6s ease-in-out infinite',
        'pulse-ring': 'pulse-ring 1.8s cubic-bezier(0.2, 0.8, 0.2, 1) infinite',
      },
    },
  },
  plugins: [],
}
