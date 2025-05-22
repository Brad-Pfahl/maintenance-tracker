module.exports = {
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.{js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#64748b', // slate-500
          light: '#cbd5e1',   // slate-200
          dark: '#475569',    // slate-600
        },
        background: {
          DEFAULT: '#f8fafc', // slate-50
        },
        text: {
          DEFAULT: '#334155', // slate-700
          muted: '#64748b',   // slate-500
        },
        accent: {
          DEFAULT: '#60a5fa', // blue-400
          dark: '#3b82f6',    // blue-500
        },
        error: {
          DEFAULT: '#b91c1c',
          light: '#fee2e2',
        },
      },
    }

  },
  plugins: [],
};
