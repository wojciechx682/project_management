
(function () {
    const pwd    = document.getElementById('password');
    const toggle = document.getElementById('togglePass');
    const icon   = toggle ? toggle.querySelector('i') : null;

    if (!pwd || !toggle || !icon) return;

    function updateButtonVisibility() {
        const hasValue = pwd.value.length > 0;
        toggle.hidden = !hasValue;

        // jeśli pole wyczyszczono — wracamy do trybu "password"
        if (!hasValue && pwd.type !== 'password') {
            pwd.type = 'password';
            toggle.setAttribute('aria-pressed', 'false');
            toggle.setAttribute('aria-label', 'Pokaż hasło');
            icon.classList.remove('icon-eye-off');
            icon.classList.add('icon-eye');
        }
    }

    toggle.addEventListener('click', () => {
        const show = pwd.type === 'password';
        pwd.type = show ? 'text' : 'password';

        toggle.setAttribute('aria-pressed', String(show));
        toggle.setAttribute('aria-label', show ? 'Ukryj hasło' : 'Pokaż hasło');

        icon.classList.toggle('icon-eye');
        icon.classList.toggle('icon-eye-off');
    });

    pwd.addEventListener('input', updateButtonVisibility);

    // inicjalizacja po załadowaniu (np. gdy przeglądarka przywróci wartość)
    updateButtonVisibility();
})();

