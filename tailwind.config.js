export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#FFD400',
                'primary-dark': '#FFC107',
                dark: {
                    bg: '#050505',
                    card: '#121212',
                    elevated: '#181818',
                },
            },
            fontFamily: {
                heading: ['Space Mono', 'JetBrains Mono', 'monospace'],
                body: ['IBM Plex Sans', 'sans-serif'],
                code: ['JetBrains Mono', 'Fira Code', 'monospace'],
            },
        },
    },
    plugins: [],
};
