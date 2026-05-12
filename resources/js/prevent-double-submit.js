export function initPreventDoubleSubmit() {
    document.addEventListener(
        'submit',
        (e) => {
            const form = e.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (!form.matches('form[data-prevent-double-submit]')) return;

            if (form.dataset.submitting === 'true') {
                e.preventDefault();
                return;
            }

            form.dataset.submitting = 'true';
            form.setAttribute('aria-busy', 'true');

            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitButtons.forEach((btn) => {
                btn.disabled = true;
                btn.setAttribute('aria-disabled', 'true');
            });
        },
        true,
    );
}

