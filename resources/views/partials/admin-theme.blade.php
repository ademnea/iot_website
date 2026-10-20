{{-- Admin pages: Tailwind's runtime CDN (as before, now pinned) plus the IoT-ra brand palette,
     so `bg-brand-700` etc. render the logo's navy. Loaded once per page even if included twice. --}}
@once
<script src="https://cdn.tailwindcss.com/3.4.17"></script>
<script>
    tailwind.config = { theme: { extend: { colors: { brand: {
        50: '#EEF4FB', 100: '#DCE8F6', 200: '#B9D1EE', 300: '#8CB3E1', 400: '#5A8FCF',
        500: '#2F6DB8', 600: '#0F559F', 700: '#034486', 800: '#04366B', 900: '#04294F', 950: '#021A33',
    } } } } };
</script>
@endonce
