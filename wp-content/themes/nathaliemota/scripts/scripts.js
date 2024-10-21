document.addEventListener('DOMContentLoaded', function() {
    const burger = document.querySelector('.burger-menu');
    const menu = document.querySelector('.menu');
    const icon = burger.querySelector('i');
    const modal = document.getElementById('contactModal');
    const contactLink = document.querySelector('a[href="#contact"]');
    const modalPhoto = document.getElementById('contactModalPhoto');
    const contactBtn = document.getElementById('contactBtn');

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
        console.log('clicked');
        e.preventDefault(); // Empêche le comportement par défaut du lien
        modal.classList.add('active'); // Ajoute la classe active pour afficher la modale
    });
    modal.addEventListener('click', function(e) {
        if (e.target === modal) { // Si le clic est sur l'overlay (en dehors de la boîte)
            modal.classList.remove('active'); // Fermer la modale
        }
    });

    contactBtn.addEventListener('click', function(e) {
        console.log('clicked');
        e.preventDefault(); // Empêche le comportement par défaut du lien
        modalPhoto.classList.add('active'); // Ajoute la classe active pour afficher la modale
    });
    modalPhoto.addEventListener('click', function(e) {
        if (e.target === modalPhoto) { // Si le clic est sur l'overlay (en dehors de la boîte)
            modalPhoto.classList.remove('active'); // Fermer la modale
        }
    });

    jQuery(document).ready(function($) {
        const previewImg = $('#previewImg');
        const prevArrow = $('#prevArrow');
        const nextArrow = $('#nextArrow');

        // Survol de la flèche gauche (photo précédente)
        prevArrow.on('mouseenter', function() {
            $.ajax({
                url: ajax_object.ajax_url, // URL pour l'appel AJAX
                type: 'POST',
                data: {
                    action: 'get_previous_photo', // Action définie dans functions.php
                    security: ajax_object.security // Nonce pour la sécurité
                },
                success: function(response) {
                    if (response.success && response.data) {
                        console.log('URL de l\'image:', response.data); // Vérifier l'URL de l'image
                        previewImg.attr('src', response.data).show(); // Afficher l'image
                    }
                }
            });
        });

        // Quitter la flèche gauche
        prevArrow.on('mouseleave', function() {
            previewImg.hide(); // Masquer l'image au survol
        });

        // Survol de la flèche droite (photo suivante)
        nextArrow.on('mouseenter', function() {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_next_photo',
                    security: ajax_object.security
                },
                success: function(response) {
                    if (response.success && response.data) {
                        previewImg.attr('src', response.data).show(); // Afficher l'image
                    }
                }
            });
        });

        // Quitter la flèche droite
        nextArrow.on('mouseleave', function() {
            previewImg.hide(); // Masquer l'image au survol
        });
    });
});
