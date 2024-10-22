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

        // Définir l'URL de l'image par défaut
        const defaultImageUrl = previewImg.attr('src'); // Récupérer l'URL de l'image par défaut

        const currentPostID = ajax_object.post_id; // Récupérer l'ID du post actuel depuis les données localisées

        // Fonction pour afficher l'image et mettre à jour le lien de la flèche
        function showImage(url, postUrl, arrowElement) {
            console.log('URL reçue:', url);
            if (url !== defaultImageUrl) {
                previewImg.attr('src', url).css('opacity', 1);
                arrowElement.attr('href', postUrl); // Mettre à jour le lien de la flèche
            } else {
                console.warn('L\'URL reçue est identique à l\'image par défaut.');
            }
        }

        // Fonction pour masquer l'image
        function hideImage() {
            previewImg.css('opacity', 0); // Masque l'image en ajustant son opacité
        }

        // Survol de la flèche gauche (photo précédente)
        prevArrow.on('mouseenter', function() {
            $.ajax({
                url: ajax_object.ajax_url, // URL pour l'appel AJAX
                type: 'POST',
                data: {
                    action: 'get_previous_photo', // Action définie dans functions.php
                    security: ajax_object.security, // Nonce pour la sécurité
                    post_id: currentPostID // Passer l'ID du post actuel
                },
                success: function(response) {
                    if (response.success) {
                        showImage(response.data.image_url, response.data.post_url, prevArrow); // Afficher l'image précédente et mettre à jour le lien
                    } else {
                        console.error('Erreur :', response.data.message); // Afficher une erreur si nécessaire
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Erreur AJAX:', textStatus, errorThrown); // Afficher une erreur AJAX si nécessaire
                }
            });
        });

        // Survol de la flèche droite (photo suivante)
        nextArrow.on('mouseenter', function() {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_next_photo',
                    security: ajax_object.security,
                    post_id: currentPostID // Passer l'ID du post actuel
                },
                success: function(response) {
                    if (response.success) {
                        showImage(response.data.image_url, response.data.post_url, nextArrow); // Afficher l'image suivante et mettre à jour le lien
                    } else {
                        console.error('Erreur :', response.data.message); // Afficher une erreur si nécessaire
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Erreur AJAX:', textStatus, errorThrown); // Afficher une erreur AJAX si nécessaire
                }
            });
        });
    // Masquer l'image lorsque la souris quitte les flèches
    prevArrow.on('mouseleave', hideImage);
    nextArrow.on('mouseleave', hideImage);
    });
});
