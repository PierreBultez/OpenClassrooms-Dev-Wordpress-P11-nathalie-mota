document.addEventListener('DOMContentLoaded', function() {
    const burger = document.querySelector('.burger-menu');
    const menu = document.querySelector('.menu');
    const icon = burger.querySelector('i');
    const modal = document.getElementById('contactModal');
    const contactLink = document.querySelector('a[href="#contact"]');

    burger.addEventListener('click', function() {
        menu.classList.toggle('active');
        if (menu.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-xmark');
        } else {
            icon.classList.remove('fa-xmark');
            icon.classList.add('fa-bars');
        }
        const isExpanded = burger.getAttribute('aria-expanded') === 'true' || false;
        burger.setAttribute('aria-expanded', !isExpanded);
    });

    contactLink.addEventListener('click', function(e) {
        e.preventDefault(); // Empêche le comportement par défaut du lien
        modal.classList.add('active'); // Ajoute la classe active pour afficher la modale
    });
    modal.addEventListener('click', function(e) {
        if (e.target === modal) { // Si le clic est sur l'overlay (en dehors de la boîte)
            modal.classList.remove('active'); // Fermer la modale
        }
    });
});
