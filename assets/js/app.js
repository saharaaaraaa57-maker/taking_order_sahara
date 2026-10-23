document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();

    const input = document.getElementById('gambarInput');
    const preview = document.getElementById('gambarPreview');
    const placeholder = document.getElementById('gambarPlaceholder');

    if (input && preview) {
        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB.');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = (ev) => {
                preview.src = ev.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    }
});