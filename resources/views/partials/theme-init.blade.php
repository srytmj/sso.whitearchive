<script>
    (function () {
        var stored = localStorage.getItem('theme') || @json($theme ?? 'system');
        var isDark = stored === 'dark' || (stored === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', isDark);
        document.documentElement.dataset.theme = stored;
    })();
</script>
