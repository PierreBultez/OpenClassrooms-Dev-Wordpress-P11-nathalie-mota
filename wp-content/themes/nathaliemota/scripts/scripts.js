document.addEventListener('DOMContentLoaded', () => {
    const burger = document.querySelector('.burger-menu');
    const menu = document.querySelector('.menu');
    const icon = burger.querySelector('i');
    const modal = document.getElementById('contactModal');
    const contactLink = document.querySelector('a[href="#contact"]');

    // Gère le clic sur le bouton burger pour afficher/masquer le menu
    burger.addEventListener('click', () => {
        menu.classList.toggle('active'); // Affiche ou cache le menu
        icon.classList.toggle('fa-bars'); // Change l'icône en barre
        icon.classList.toggle('fa-xmark'); // Change l'icône en croix
        burger.setAttribute('aria-expanded', menu.classList.contains('active')); // Met à jour l'attribut aria-expanded
    });

    // Vérifie si le lien de contact et la modal existent
    if (contactLink && modal) {
        // Gère le clic sur le lien de contact pour ouvrir la modal
        contactLink.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.add('active'); // Affiche la modal
        });

        // Gère le clic en dehors de la modal pour la fermer
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active'); // Cache la modal
            }
        });
    }
});