document.addEventListener('DOMContentLoaded', function () {
    jQuery(document).ready(function ($) {
        function openLightbox(photoData) {
            // Remplir les informations de la lightbox avec les données reçues
            $('.lightbox-image').attr('src', photoData.url);
            $('.lightbox-reference').text(photoData.reference);
            $('.lightbox-category').text(photoData.category);

            $('.lightbox-overlay').fadeIn(); // Afficher la lightbox
            $('.lightbox-overlay').data('current-photo-id', photoData.id); // Mémoriser l'ID de la photo actuelle
        }

        function closeLightbox() {
            $('.lightbox-overlay').fadeOut(); // Fermer la lightbox
        }

        function loadAdjacentPhoto(direction) {
            const currentPhotoId = $('.lightbox-overlay').data('current-photo-id');
            $.ajax({
                url: lightbox_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_adjacent_photo_data',
                    security: lightbox_ajax_object.security,
                    photo_id: currentPhotoId
                },
                success: function (response) {
                    if (response.success && response.data[direction]) {
                        openLightbox(response.data[direction]);
                    } else {
                        console.log(`Aucune photo ${direction} disponible.`);
                    }
                },
                error: function () {
                    console.error('Erreur AJAX lors de la navigation des photos.');
                }
            });
        }

        // Ouvrir la lightbox au clic sur le lien .photo-expand
        $('.photo-expand').on('click', function (e) {
            e.preventDefault();
            const photoId = $(this).data('photo-id'); // Obtenir l'ID de la photo

            // Requête AJAX pour obtenir les informations de la photo
            $.ajax({
                url: lightbox_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_photo_data',
                    security: lightbox_ajax_object.security,
                    photo_id: photoId
                },
                success: function(response) {
                    if (response.success) {
                        openLightbox(response.data);
                    } else {
                        console.error('Erreur de récupération des données de la photo.');
                    }
                },
                error: function() {
                    console.error('Erreur AJAX.');
                }
            });
        });

        $('.lightbox-prev').on('click', function () {
            loadAdjacentPhoto('previous');
        });

        $('.lightbox-next').on('click', function () {
            loadAdjacentPhoto('next');
        });

        // Fermer la lightbox au clic sur l'overlay ou sur le bouton de fermeture
        $('.lightbox-close, .lightbox-overlay').on('click', function () {
            closeLightbox();
        });

        // Empêche la propagation du clic sur l'image pour éviter de fermer la lightbox
        $('.lightbox-content').on('click', function (e) {
            e.stopPropagation();
        });
    });
});

