document.addEventListener('DOMContentLoaded', function () {

    const modalPhoto = document.getElementById('contactModalPhoto');
    const contactBtn = document.getElementById('contactBtn');

    if (contactBtn && modalPhoto) {
        // Ajouter l'écouteur d'événement seulement si le bouton et la modale existent
        contactBtn.addEventListener('click', function (e) {
            e.preventDefault(); // Empêche le comportement par défaut du lien
            modalPhoto.classList.add('active'); // Ajoute la classe active pour afficher la modale
        });

        modalPhoto.addEventListener('click', function (e) {
            if (e.target === modalPhoto) { // Si le clic est sur l'overlay (en dehors de la boîte)
                modalPhoto.classList.remove('active'); // Fermer la modale
            }
        });
    }

    jQuery(document).ready(function ($) {
        // Vérifier si l'objet photo_navigation_ajax_object est défini
        if (typeof photo_navigation_ajax_object !== 'undefined') {
            const previewImg = $('#previewImg');
            const prevArrow = $('#prevArrow');
            const nextArrow = $('#nextArrow');

            // Définir l'URL de l'image par défaut
            const defaultImageUrl = previewImg.attr('src'); // Récupérer l'URL de l'image par défaut

            // Fonction pour afficher l'image et mettre à jour le lien de la flèche
            function showImage(url, postUrl, arrowElement) {
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
            prevArrow.on('mouseenter', function () {
                $.ajax({
                    url: photo_navigation_ajax_object.ajax_url, // URL pour l'appel AJAX
                    type: 'POST',
                    data: {
                        action: 'get_previous_photo', // Action définie dans functions.php
                        security: photo_navigation_ajax_object.security, // Nonce pour la sécurité
                        post_id: photo_navigation_ajax_object.post_id // Passer l'ID du post actuel
                    },
                    success: function (response) {
                        if (response.success) {
                            showImage(response.data.image_url, response.data.post_url, prevArrow); // Afficher l'image précédente et mettre à jour le lien
                        } else {
                            console.error('Erreur :', response.data.message); // Afficher une erreur si nécessaire
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Erreur AJAX:', textStatus, errorThrown); // Afficher une erreur AJAX si nécessaire
                    }
                });
            });

            // Survol de la flèche droite (photo suivante)
            nextArrow.on('mouseenter', function () {
                $.ajax({
                    url: photo_navigation_ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'get_next_photo',
                        security: photo_navigation_ajax_object.security,
                        post_id: photo_navigation_ajax_object.post_id
                    },
                    success: function (response) {
                        if (response.success) {
                            showImage(response.data.image_url, response.data.post_url, nextArrow); // Afficher l'image suivante et mettre à jour le lien
                        } else {
                            console.error('Erreur :', response.data.message); // Afficher une erreur si nécessaire
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Erreur AJAX:', textStatus, errorThrown); // Afficher une erreur AJAX si nécessaire
                    }
                });
            });
            // Masquer l'image lorsque la souris quitte les flèches
            prevArrow.on('mouseleave', hideImage);
            nextArrow.on('mouseleave', hideImage);
        }
    });
});