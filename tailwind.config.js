import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                border: "hsl(var(--border))",
                input: "hsl(var(--input))",
                ring: "hsl(var(--ring))",
                background: "hsl(var(--background))",
                foreground: "hsl(var(--foreground))",
                primary: {
                    DEFAULT: "hsl(var(--primary))",
                    foreground: "hsl(var(--primary-foreground))",
                },
                secondary: {
                    DEFAULT: "hsl(var(--secondary))",
                    foreground: "hsl(var(--secondary-foreground))",
                },
                destructive: {
                    DEFAULT: "hsl(var(--destructive))",
                    foreground: "hsl(var(--destructive-foreground))",
                },
                muted: {
                    DEFAULT: "hsl(var(--muted))",
                    foreground: "hsl(var(--muted-foreground))",
                },
                accent: {
                    DEFAULT: "hsl(var(--accent))",
                    foreground: "hsl(var(--accent-foreground))",
                },
                popover: {
                    DEFAULT: "hsl(var(--popover))",
                    foreground: "hsl(var(--popover-foreground))",
                },
                card: {
                    DEFAULT: "hsl(var(--card))",
                    foreground: "hsl(var(--card-foreground))",
                },
                btnTambah: {
                    DEFAULT: "hsl(var(--btn-tambah))",
                    foreground: "hsl(var(--btn-tambah-fg))",
                },
                btnHistory: {
                    DEFAULT: "hsl(var(--btn-history))",
                    foreground: "hsl(var(--btn-history-fg))",
                },
                btnSubmit: {
                    DEFAULT: "hsl(var(--btn-submit))",
                    foreground: "hsl(var(--btn-submit-fg))",
                },
                btnSearch: {
                    DEFAULT: "hsl(var(--btn-search))",
                    foreground: "hsl(var(--btn-search-fg))",
                },
                btnMasuk: {
                    DEFAULT: "hsl(var(--btn-masuk))",
                    foreground: "hsl(var(--btn-masuk-fg))",
                },
                btnPaginasi: {
                    DEFAULT: "hsl(var(--btn-paginasi))",
                    foreground: "hsl(var(--btn-paginasi-fg))",
                },
            },
            borderRadius: {
                lg: "var(--radius)",
                md: "calc(var(--radius) - 2px)",
                sm: "calc(var(--radius) - 4px)",
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
