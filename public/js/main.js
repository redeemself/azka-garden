document.addEventListener('DOMContentLoaded', () => {
    console.log('Azka Garden JS loaded.');

    // Toggle mobile menu
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu    = document.querySelector('nav ul, #menu, #user-menu, #admin-menu, #dev-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('hidden');
        });
    }
});
