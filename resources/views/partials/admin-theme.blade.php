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
{{-- Phones and tablets: grid and flex columns may shrink, long values (links, emails) wrap
     instead of pushing past the screen edge, and wide tables scroll inside their box. --}}
<style>
    img { max-width: 100%; }
    /* flex and grid items normally refuse to shrink below their content; these may */
    .grid > *, ol, ul, img { min-width: 0; }
    li, p, td, dd, h4, h6 { overflow-wrap: break-word; }
    @media (max-width: 639px) {
        table { display: block; overflow-x: auto; }
    }
</style>
<script>
    // Trash icons are forms with data-confirm: ask before anything is deleted.
    document.addEventListener('submit', e => {
        const form = e.target.closest('form[data-confirm]');
        if (form && ! confirm(form.dataset.confirm)) e.preventDefault();
    });
</script>
@endonce
