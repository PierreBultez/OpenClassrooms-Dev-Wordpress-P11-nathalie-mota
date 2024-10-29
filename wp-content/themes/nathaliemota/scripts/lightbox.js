document.addEventListener('DOMContentLoaded', function () {
    jQuery(document).ready(function ($) {
        function openLightbox(photoData) {
            // Remplir les informations de la lightbox avec les données reçues
            $('.lightbox-image').attr('src', photoData.url);
            $('.lightbox-reference').text(photoData.reference);
            $('.lightbox-category').text(photoData.category);
            $('.lightbox-overlay').data('current-photo-id', photoData.id); // Mémoriser l'ID de la photo actuelle
            console.log('ID de la photo initiale pour lightbox:', photoData.id);
            $('.lightbox-overlay').fadeIn(); // Afficher la lightbox
        }

        function closeLightbox() {
            $('.lightbox-overlay').fadeOut(); // Fermer la lightbox
        }

        function loadPhoto(url, postId) {
            $('.lightbox-image').attr('src', url); // Charger la nouvelle image dans la lightbox
            $('.lightbox-overlay').data('current-photo-id', postId); // Mettre à jour l'ID de la photo actuelle
        }

        // Clic sur l'icône expand pour ouvrir la lightbox
        $('.photo-expand').on('click', function (e) {
            e.preventDefault();
            const photoId = $(this).data('photo-id'); // Récupérer l'ID de la photo
            console.log('ID de la photo au clic sur expand:', photoId);

            $('.lightbox-overlay').data('current-photo-id', photoId); // Stocker l'ID dans la lightbox

            // Requête AJAX pour obtenir les informations de la photo et ouvrir la lightbox
            $.ajax({
                url: lightbox_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_photo_data',
                    security: lightbox_ajax_object.security,
                    photo_id: photoId
                },
                success: function (response) {
                    if (response.success) {
                        console.log('Données de la photo:', response.data);
                        openLightbox(response.data); // Ouvrir la lightbox avec les données de la photo
                    } else {
                        console.error('Erreur de récupération des données de la photo.');
                    }
                },
                error: function () {
                    console.error('Erreur AJAX.');
                }
            });
        });

        $('#prevArrowLightbox').on('click', function () {
            const currentPostId = $('.lightbox-overlay').data('current-photo-id');
            console.log('ID de la photo actuelle pour navigation précédente:', currentPostId);

            $.ajax({
                url: lightbox_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_previous_lightbox_photo_ajax',
                    security: lightbox_ajax_object.security,
                    post_id: currentPostId
                },
                success: function (response) {
                    if (response.success) {
                        loadPhoto(response.data.url, response.data.id); // Utilisez l'URL de la photo pour afficher l'image
                        $('.lightbox-reference').text(response.data.reference);
                        $('.lightbox-category').text(response.data.category);
                    } else {
                        console.error('Erreur :', response.data);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Erreur AJAX:', textStatus, errorThrown);
                }
            });
        });

        $('#nextArrowLightbox').on('click', function () {
            const currentPostId = $('.lightbox-overlay').data('current-photo-id');
            console.log('ID de la photo actuelle pour navigation suivante:', currentPostId);

            $.ajax({
                url: lightbox_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_next_lightbox_photo_ajax',
                    security: lightbox_ajax_object.security,
                    post_id: currentPostId
                },
                success: function (response) {
                    if (response.success) {
                        loadPhoto(response.data.url, response.data.id); // Utilisez l'URL de la photo pour afficher l'image
                        $('.lightbox-reference').text(response.data.reference);
                        $('.lightbox-category').text(response.data.category);
                    } else {
                        console.error('Erreur :', response.data);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Erreur AJAX:', textStatus, errorThrown);
                }
            });
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

