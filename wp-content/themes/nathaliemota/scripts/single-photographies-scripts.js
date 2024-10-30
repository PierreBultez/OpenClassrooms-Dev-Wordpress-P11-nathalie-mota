document.addEventListener('DOMContentLoaded', function () {
    const modalPhoto = document.getElementById('contactModalPhoto');
    const contactBtn = document.getElementById('contactBtn');

    // Vérifie si les éléments contactBtn et modalPhoto existent
    if (contactBtn && modalPhoto) {
        // Ajoute un événement de clic pour ouvrir la modal
        contactBtn.addEventListener('click', function (e) {
            e.preventDefault();
            modalPhoto.classList.add('active'); // Affiche la modal
        });

        // Ajoute un événement de clic pour fermer la modal lorsqu'on clique en dehors
        modalPhoto.addEventListener('click', function (e) {
            if (e.target === modalPhoto) {
                modalPhoto.classList.remove('active'); // Cache la modal
            }
        });
    }

    jQuery(document).ready(function ($) {
        // Vérifie si l'objet photo_navigation_ajax_object est défini
        if (typeof photo_navigation_ajax_object !== 'undefined') {
            const previewImg = $('#previewImg');
            const prevArrow = $('#prevArrow');
            const nextArrow = $('#nextArrow');
            const defaultImageUrl = previewImg.attr('src'); // URL de l'image par défaut

            // Affiche l'image dans le preview et met à jour le lien de la flèche
            function showImage(url, postUrl, arrowElement) {
                if (url !== defaultImageUrl) {
                    previewImg.attr('src', url).css('opacity', 1); // Met à jour l'image et la rend visible
                    arrowElement.attr('href', postUrl); // Met à jour le lien de la flèche
                } else {
                    console.warn('L\'URL reçue est identique à l\'image par défaut.');
                }
            }

            // Cache l'image du preview
            function hideImage() {
                previewImg.css('opacity', 0); // Rend l'image invisible
            }

            // Gère le survol des flèches pour charger les images précédentes/suivantes
            function handleArrowHover(action, arrowElement) {
                $.ajax({
                    url: photo_navigation_ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: action, // Action à exécuter (précédente ou suivante)
                        security: photo_navigation_ajax_object.security,
                        post_id: photo_navigation_ajax_object.post_id // ID du post actuel
                    },
                    success: function (response) {
                        if (response.success) {
                            showImage(response.data.image_url, response.data.post_url, arrowElement); // Affiche la nouvelle image
                        } else {
                            console.error('Erreur :', response.data.message); // Affiche une erreur en cas d'échec
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Erreur AJAX:', textStatus, errorThrown); // Affiche une erreur AJAX
                    }
                });
            }

            // Ajoute les événements de survol pour les flèches
            prevArrow.on('mouseenter', function () {
                handleArrowHover('get_previous_photo', prevArrow); // Charge l'image précédente
            });

            nextArrow.on('mouseenter', function () {
                handleArrowHover('get_next_photo', nextArrow); // Charge l'image suivante
            });

            // Ajoute les événements de sortie de survol pour cacher l'image
            prevArrow.on('mouseleave', hideImage);
            nextArrow.on('mouseleave', hideImage);
        }
    });
});