module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
        './resources/css/**/*.css',
    ],
    theme: {
        screens: {
            sm: '480px',
            md: '768px',
            lg: '976px',
            xl: '1440px',
        },
        extend: {
            spacing: {
                '128': '32rem',
                '144': '36rem',
            },
            borderRadius: {
                '4xl': '2rem',
            }
        }
    },
    plugins: []
}
