document.addEventListener('DOMContentLoaded', function () {
    jQuery(document).ready(function ($) {
        // Ouvre la lightbox avec les données de la photo
        function openLightbox(photoData) {
            $('.lightbox-image').attr('src', photoData.url); // Met à jour l'image de la lightbox
            $('.lightbox-reference').text(photoData.reference); // Met à jour la référence de la photo
            $('.lightbox-category').text(photoData.category); // Met à jour la catégorie de la photo
            $('.lightbox-overlay').data('current-photo-id', photoData.id).fadeIn(); // Affiche la lightbox
        }

        // Ferme la lightbox
        function closeLightbox() {
            $('.lightbox-overlay').fadeOut(); // Cache la lightbox
        }

        // Charge une nouvelle photo dans la lightbox
        function loadPhoto(url, postId) {
            $('.lightbox-image').attr('src', url); // Met à jour l'image de la lightbox
            $('.lightbox-overlay').data('current-photo-id', postId); // Met à jour l'ID de la photo actuelle
        }

        // Gère le clic sur les flèches de navigation de la lightbox
        function handleArrowClick(action) {
            const currentPostId = $('.lightbox-overlay').data('current-photo-id'); // Récupère l'ID de la photo actuelle
            $.ajax({
                url: lightbox_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: action, // Action à exécuter (précédente ou suivante)
                    security: lightbox_ajax_object.security,
                    post_id: currentPostId // ID de la photo actuelle
                },
                success: function (response) {
                    if (response.success) {
                        loadPhoto(response.data.url, response.data.id); // Charge la nouvelle photo
                        $('.lightbox-reference').text(response.data.reference); // Met à jour la référence
                        $('.lightbox-category').text(response.data.category); // Met à jour la catégorie
                    } else {
                        console.error('Erreur :', response.data); // Affiche une erreur en cas d'échec
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Erreur AJAX:', textStatus, errorThrown); // Affiche une erreur AJAX
                }
            });
        }

        // Ouvre la lightbox lorsque l'utilisateur clique sur l'icone expand
        $('.photo-expand').on('click', function (e) {
            e.preventDefault();
            const photoId = $(this).data('photo-id'); // Récupère l'ID de la photo cliquée
            $.ajax({
                url: lightbox_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_photo_data', // Action pour récupérer les données de la photo
                    security: lightbox_ajax_object.security,
                    photo_id: photoId // ID de la photo cliquée
                },
                success: function (response) {
                    if (response.success) {
                        openLightbox(response.data); // Ouvre la lightbox avec les données de la photo
                    } else {
                        console.error('Erreur de récupération des données de la photo.'); // Affiche une erreur en cas d'échec
                    }
                },
                error: function () {
                    console.error('Erreur AJAX.'); // Affiche une erreur AJAX
                }
            });
        });

        // Gère le clic sur la flèche précédente de la lightbox
        $('#prevArrowLightbox').on('click', function () {
            handleArrowClick('get_previous_lightbox_photo_ajax'); // Charge la photo précédente
        });

        // Gère le clic sur la flèche suivante de la lightbox
        $('#nextArrowLightbox').on('click', function () {
            handleArrowClick('get_next_lightbox_photo_ajax'); // Charge la photo suivante
        });

        // Ferme la lightbox lorsque l'utilisateur clique sur l'overlay ou le bouton de fermeture
        $('.lightbox-close, .lightbox-overlay').on('click', closeLightbox);

        // Empêche la propagation du clic à l'intérieur du contenu de la lightbox
        $('.lightbox-content').on('click', function (e) {
            e.stopPropagation();
        });
    });
});