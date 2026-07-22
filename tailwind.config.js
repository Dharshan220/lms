export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#FFC107',
                'primary-dark': '#FFA000',
                'primary-light': '#FFD54F',
                dark: {
                    bg: '#121212',
                    card: '#1C1C1C',
                    elevated: '#2A2A2A',
                    surface: '#0D0D0D',
                    glass: 'rgba(28, 28, 28, 0.7)',
                },
                neon: {
                    yellow: '#FFC107',
                    amber: '#FFA000',
                    orange: '#FF9800',
                    glow: 'rgba(255, 193, 7, 0.25)',
                },
            },
            fontFamily: {
                heading: ['Inter', 'Poppins', 'system-ui', 'sans-serif'],
                body: ['Inter', 'Poppins', 'system-ui', 'sans-serif'],
                display: ['Poppins', 'Inter', 'system-ui', 'sans-serif'],
                code: ['JetBrains Mono', 'Fira Code', 'monospace'],
                mono: ['JetBrains Mono', 'Fira Code', 'monospace'],
            },
            borderRadius: {
                '2xl': '16px',
                '3xl': '20px',
                '4xl': '24px',
            },
            boxShadow: {
                'glow': '0 0 20px rgba(255, 193, 7, 0.15)',
                'glow-lg': '0 0 40px rgba(255, 193, 7, 0.25)',
                'glass': '0 8px 32px rgba(0, 0, 0, 0.4)',
                'premium': '0 4px 24px rgba(0, 0, 0, 0.5)',
            },
            backdropBlur: {
                glass: '20px',
            },
        },
    },
    plugins: [],
};
