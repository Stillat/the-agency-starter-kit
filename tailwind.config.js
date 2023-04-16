module.exports = {
    content: [
        './resources/**/*.antlers.html',
        './resources/**/*.blade.php',
        './resources/**/*.vue',
        './content/**/*.md'
    ],
    theme: {
        extend: {
            backgroundColor: {
                'accent-color': 'pink',
                'accent-color-blue': '#c4cce7',
            },

            borderRadius: {
                'xl': '1rem',
            },
            boxShadow: {
                'subtle': '0 4px 6px rgba(0, 0, 0, 0.1)',
            },
            spacing: {
                '18': '4.5rem',
            },

        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        function ({ addBase, theme }) {
            function extractColorVars(colorObj, colorGroup = '') {
                return Object.keys(colorObj).reduce((vars, colorKey) => {
                    const value = colorObj[colorKey];

                    const newVars =
                        typeof value === 'string'
                            ? { [`--color${colorGroup}-${colorKey}`]: value }
                            : extractColorVars(value, `-${colorKey}`);

                    return { ...vars, ...newVars };
                }, {});
            }

            addBase({
                ':root': extractColorVars(theme('colors')),
            });
        },
    ],
}
