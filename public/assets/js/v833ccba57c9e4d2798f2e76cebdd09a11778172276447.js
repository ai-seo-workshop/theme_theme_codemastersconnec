(() => {
    const toggleButton = document.querySelector('[data-menu-toggle]');
    const menuPanel = document.querySelector('[data-menu-panel]');

    if (toggleButton && menuPanel) {
        toggleButton.addEventListener('click', () => {
            const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
            toggleButton.setAttribute('aria-expanded', String(!isExpanded));
            toggleButton.classList.toggle('is-active', !isExpanded);
            menuPanel.classList.toggle('is-open', !isExpanded);
        });
    }

    const faqButtons = document.querySelectorAll('.faq-toggle');
    faqButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            const answer = button.nextElementSibling;
            button.setAttribute('aria-expanded', String(!expanded));
            if (answer) {
                answer.hidden = expanded;
            }
        });
    });
})();
