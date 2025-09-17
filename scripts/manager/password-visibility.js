(function () {
    function setupPasswordToggle(inputId, toggleId) {
        const pwd    = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        const icon   = toggle ? toggle.querySelector('i') : null;

        if (!pwd || !toggle || !icon) return;

        function updateButtonVisibility() {
            const hasValue = pwd.value.length > 0;
            toggle.hidden = !hasValue;

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

        // inicjalizacja
        updateButtonVisibility();

        function checkCaps(e) {
            const caps = e.getModifierState && e.getModifierState('CapsLock');
            if (caps) {
                pwd.setCustomValidity('Caps Lock is on');
                pwd.reportValidity();
            } else {
                pwd.setCustomValidity('');
            }
        }

        pwd.addEventListener('keydown', checkCaps);
        pwd.addEventListener('keyup', checkCaps);
    }

    // inicjalizujemy wszystkie trzy pola
    setupPasswordToggle('currentPassword', 'toggleCurrent');
    setupPasswordToggle('newPassword', 'toggleNew');
    setupPasswordToggle('confirmPassword', 'toggleConfirm');
})();
