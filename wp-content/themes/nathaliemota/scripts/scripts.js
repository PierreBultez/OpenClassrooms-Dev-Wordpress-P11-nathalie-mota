document.addEventListener('DOMContentLoaded', function() {
    const burger = document.querySelector('.burger-menu');
    const menu = document.querySelector('.menu');
    const icon = burger.querySelector('i');
    const modal = document.getElementById('contactModal');
    const contactLink = document.querySelector('a[href="#contact"]');
    const modalPhoto = document.getElementById('contactModalPhoto');
    const contactBtn = document.getElementById('contactBtn');
    const categoryBtn = document.getElementById('categoryFilterBtn');
    const formatBtn = document.getElementById('formatFilterBtn');
    const sortBtn = document.getElementById('sortFilterBtn');

    const categoryDropdown = document.getElementById('categoryDropdown');
    const formatDropdown = document.getElementById('formatDropdown');
    const sortDropdown = document.getElementById('sortDropdown');

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

    if (contactLink && modal) {
        // Ajouter l'écouteur d'événement seulement si le lien et la modale existent
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
    }

    if (contactBtn && modalPhoto) {
        // Ajouter l'écouteur d'événement seulement si le bouton et la modale existent
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
    }

    function closeAllDropdowns() {
        categoryDropdown.classList.remove('active');
        categoryBtn.classList.remove('active');
        formatDropdown.classList.remove('active');
        formatBtn.classList.remove('active');
        sortDropdown.classList.remove('active');
        sortBtn.classList.remove('active');

        const categoryIcon = categoryBtn.querySelector('i');
        if (categoryIcon) {
            categoryIcon.classList.add('fa-chevron-down');
            categoryIcon.classList.remove('fa-chevron-up');
        }

        const formatIcon = formatBtn.querySelector('i');
        if (formatIcon) {
            formatIcon.classList.add('fa-chevron-down');
            formatIcon.classList.remove('fa-chevron-up');
        }

        const sortIcon = sortBtn.querySelector('i');
        if (sortIcon) {
            sortIcon.classList.add('fa-chevron-down');
            sortIcon.classList.remove('fa-chevron-up');
        }
    }

    categoryBtn.addEventListener('click', function(e) {
        e.stopPropagation(); // Empêche le clic de se propager
        const isActive = categoryDropdown.classList.contains('active');
        closeAllDropdowns();

        if (!isActive) {
            categoryDropdown.classList.add('active');
            categoryBtn.classList.add('active');
            const categoryIcon = categoryBtn.querySelector('i');
            if (categoryIcon) {
                categoryIcon.classList.remove('fa-chevron-down');
                categoryIcon.classList.add('fa-chevron-up');
            }
        } else {
            // Rétablit l'icône sur chevron-down si le bouton était déjà actif
            const categoryIcon = categoryBtn.querySelector('i');
            if (categoryIcon) {
                categoryIcon.classList.remove('fa-chevron-up');
                categoryIcon.classList.add('fa-chevron-down');
            }
        }
    });

    formatBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isActive = formatDropdown.classList.contains('active');
        closeAllDropdowns();

        if (!isActive) {
            formatDropdown.classList.add('active');
            formatBtn.classList.add('active');
            const formatIcon = formatBtn.querySelector('i');
            if (formatIcon) {
                formatIcon.classList.remove('fa-chevron-down');
                formatIcon.classList.add('fa-chevron-up');
            }
        } else {
            // Rétablit l'icône sur chevron-down si le bouton était déjà actif
            const formatIcon = formatBtn.querySelector('i');
            if (formatIcon) {
                formatIcon.classList.remove('fa-chevron-up');
                formatIcon.classList.add('fa-chevron-down');
            }
        }
    });

    sortBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isActive = sortDropdown.classList.contains('active');
        closeAllDropdowns();

        if (!isActive) {
            sortDropdown.classList.add('active');
            sortBtn.classList.add('active');
            const sortIcon = sortBtn.querySelector('i');
            if (sortIcon) {
                sortIcon.classList.remove('fa-chevron-down');
                sortIcon.classList.add('fa-chevron-up');
            }
        } else {
            // Rétablit l'icône sur chevron-down si le bouton était déjà actif
            const sortIcon = sortBtn.querySelector('i');
            if (sortIcon) {
                sortIcon.classList.remove('fa-chevron-up');
                sortIcon.classList.add('fa-chevron-down');
            }
        }
    });

    document.addEventListener('click', function() {
        closeAllDropdowns();
    });

    jQuery(document).ready(function($) {
        // Lorsqu'une option de catégorie est sélectionnée
        $('.category-option').on('click', function(e) {
            e.preventDefault();

            let termId = $(this).data('term-id');
            let termName = $(this).text();
            let $button = $('#categoryFilterBtn');
            console.log('ID de la catégorie sélectionnée:', termId);
            console.log('Nom de la catégorie sélectionnée:', termName);

            $.ajax({
                url: filter_ajax_object.ajax_url, // URL de l'admin-ajax.php
                type: 'POST',
                data: {
                    action: 'filter_photos_by_category', // Action définie dans functions.php
                    security: filter_ajax_object.security, // Nonce pour la sécurité
                    term_id: termId, // ID de la catégorie sélectionnée
                },
                success: function(response) {
                    if (response.success) {
                        $('.image-bloc').html(response.data); // Mettre à jour les photos filtrées
                        console.log('Réponse AJAX réussie, contenu mis à jour');
                        $button.html(`${termName} <i class="fa-solid fa-chevron-up"></i>`); // Mettre à jour le texte du bouton avec le nom de la catégorie
                        console.log('Texte du bouton mis à jour avec la catégorie:', termName);
                    } else {
                        $('.image-bloc').html('<p>' + response.data + '</p>'); // Afficher un message d'erreur si aucune photo n'est trouvée
                        $button.html('Catégorie <i class="fa-solid fa-chevron-down"></i>');
                        console.log('Réponse AJAX échouée, texte du bouton réinitialisé');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Erreur AJAX : ' + textStatus);
                    $button.html('Catégorie <i class="fa-solid fa-chevron-down"></i>');
                }
            });
        });
    });

    jQuery(document).ready(function($) {
        // Vérifier si l'objet photo_navigation_ajax_object est défini
        if (typeof photo_navigation_ajax_object !== 'undefined') {
        const previewImg = $('#previewImg');
        const prevArrow = $('#prevArrow');
        const nextArrow = $('#nextArrow');

        // Définir l'URL de l'image par défaut
        const defaultImageUrl = previewImg.attr('src'); // Récupérer l'URL de l'image par défaut

        const currentPostID = photo_navigation_ajax_object.post_id; // Récupérer l'ID du post actuel depuis les données localisées

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
                url: photo_navigation_ajax_object.ajax_url, // URL pour l'appel AJAX
                type: 'POST',
                data: {
                    action: 'get_previous_photo', // Action définie dans functions.php
                    security: photo_navigation_ajax_object.security, // Nonce pour la sécurité
                    post_id: photo_navigation_ajax_object.post_id // Passer l'ID du post actuel
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
                url: photo_navigation_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_next_photo',
                    security: photo_navigation_ajax_object.security,
                    post_id: photo_navigation_ajax_object.post_id
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
    } else {
            console.log('photo_navigation_ajax_object n\'est pas défini sur cette page.');
        }
        });

    jQuery(document).ready(function($) {
        let paged = 2; // Commencer à la page 2 car la page 1 est déjà affichée
        let selectedCategory = null; // Variable pour stocker l'ID de la catégorie sélectionnée
        let selectedFormat = null; // Variable pour stocker l'ID du format sélectionné

        // Lorsqu'une option de catégorie est sélectionnée
        $('.category-option').off('click').on('click', function(e) {
            e.preventDefault();
            selectedCategory = $(this).data('term-id'); // Récupérer l'ID de la catégorie sélectionnée
            paged = 1; // Réinitialiser la pagination
            let $button = $('#categoryFilterBtn');
            let $loadMoreBtn = $('#loadMoreBtn');

            $button.text('Chargement...');
            $loadMoreBtn.text('Afficher plus').prop('disabled', false); // Réinitialiser le texte et réactiver le bouton

            $.ajax({
                url: filter_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_photos_by_category',
                    security: filter_ajax_object.security,
                    category_id: selectedCategory,
                    format_id: selectedFormat,
                    paged: paged
                },
                success: function(response) {
                    if (response.success) {
                        $('.image-bloc').html(response.data.content); // Mettre à jour les photos filtrées
                        $button.text('Catégorie');
                        paged++;

                        if (!response.data.has_more) {
                            $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true);
                        }
                    } else {
                        $('.image-bloc').html('<p>' + response.data + '</p>');
                        $button.text('Catégorie');
                        $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true); // Désactiver le bouton
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Erreur AJAX : ' + textStatus);
                    $button.text('Erreur, réessayez');
                }
            });
        });

        // Même traitement pour les formats
        $('.format-option').off('click').on('click', function(e) {
            e.preventDefault();
            selectedFormat = $(this).data('term-id');
            paged = 1; // Réinitialiser la pagination
            let $button = $('#formatFilterBtn');
            let $loadMoreBtn = $('#loadMoreBtn');

            $button.text('Chargement...');
            $loadMoreBtn.text('Afficher plus').prop('disabled', false);

            $.ajax({
                url: filter_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_photos_by_format',
                    security: filter_ajax_object.security,
                    category_id: selectedCategory,
                    format_id: selectedFormat,
                    paged: paged
                },
                success: function(response) {
                    if (response.success) {
                        $('.image-bloc').html(response.data.content); // Mettre à jour les photos filtrées
                        $button.text('Format');
                        paged++;

                        if (!response.data.has_more) {
                            $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true);
                        }
                    } else {
                        $('.image-bloc').html('<p>' + response.data + '</p>');
                        $button.text('Format');
                        $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Erreur AJAX : ' + textStatus);
                    $button.text('Erreur, réessayez');
                }
            });
        });

        // Charger plus de photos
        $('#loadMoreBtn').off('click').on('click', function(e) {
            e.preventDefault();
            let $button = $(this);
            $button.text('Chargement...');

            $.ajax({
                url: load_more_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'load_more_photos',
                    security: load_more_ajax_object.security,
                    paged: paged,
                    category_id: selectedCategory,
                    format_id: selectedFormat
                },
                success: function(response) {
                    if (response.success) {
                        $('.image-bloc').append(response.data.content); // Ajouter les nouvelles photos
                        $button.text('Afficher plus');
                        paged++;

                        if (!response.data.has_more) {
                            $button.text('Aucune photo supplémentaire').prop('disabled', true);
                        }
                    } else {
                        $button.text('Aucune photo supplémentaire').prop('disabled', true);
                        alert('Aucune photo supplémentaire');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Erreur AJAX : ' + textStatus);
                    $('#loadMoreBtn').text('Erreur, réessayez');
                }
            });
        });
    });

    jQuery(document).ready(function($) {
        let paged = 2; // Commencer à la page 2 car la page 1 est déjà affichée
        let selectedCategory = null; // Variable pour stocker l'ID de la catégorie sélectionnée
        let selectedFormat = null; // Variable pour stocker l'ID du format sélectionné
        let selectedSort = 'desc'; // Par défaut trier par les plus récentes

        function toggleResetButton() {
            if (selectedCategory || selectedFormat || selectedSort !== 'desc') {
                $('#resetFiltersBtn').css("display", "flex");
            } else {
                $('#resetFiltersBtn').css("display", "none");
            }
        }

        // Gestion du filtre de catégorie
        $('.category-option').off('click').on('click', function(e) {
            e.preventDefault();
            selectedCategory = $(this).data('term-id');
            paged = 1; // Réinitialiser la pagination
            let $button = $('#categoryFilterBtn');
            let $loadMoreBtn = $('#loadMoreBtn');

            $button.text($(this).text()); // Met à jour le texte du bouton avec la catégorie sélectionnée
            $loadMoreBtn.text('Afficher plus').prop('disabled', false);

            $.ajax({
                url: filter_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_photos_by_category',
                    security: filter_ajax_object.security,
                    category_id: selectedCategory,
                    format_id: selectedFormat,
                    paged: paged,
                    order: selectedSort // Ajouter l'option de tri
                },
                success: function(response) {
                    if (response.success) {
                        $('.image-bloc').html(response.data.content);
                        paged++;

                        if (!response.data.has_more) {
                            $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true);
                        }
                    } else {
                        $('.image-bloc').html('<p>' + response.data + '</p>');
                        $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true);
                    }
                    toggleResetButton();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Erreur AJAX : ' + textStatus);
                    $button.html('Catégorie <i class="fa-solid fa-chevron-down"></i>');
                }
            });
        });

        // Gestion du filtre de format
        $('.format-option').off('click').on('click', function(e) {
            e.preventDefault();
            selectedFormat = $(this).data('term-id');
            const termName = $(this).text().trim();
            paged = 1; // Réinitialiser la pagination
            let $button = $('#formatFilterBtn');
            let $loadMoreBtn = $('#loadMoreBtn');

            $button.text($(this).text()); // Met à jour le texte du bouton avec le format sélectionné
            $loadMoreBtn.text('Afficher plus').prop('disabled', false);

            $.ajax({
                url: filter_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_photos_by_format',
                    security: filter_ajax_object.security,
                    category_id: selectedCategory,
                    format_id: selectedFormat,
                    paged: paged,
                    order: selectedSort // Ajouter l'option de tri
                },
                success: function(response) {
                    if (response.success) {
                        $('.image-bloc').html(response.data.content);
                        paged++;

                        if (!response.data.has_more) {
                            $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true);
                        }
                    } else {
                        $('.image-bloc').html('<p>' + response.data + '</p>');
                        $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true);
                    }
                    toggleResetButton();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Erreur AJAX : ' + textStatus);
                    $button.html('Format <i class="fa-solid fa-chevron-down"></i>');
                }
            });
        });

        // Gestion du tri
        $('.sort-option').off('click').on('click', function(e) {
            e.preventDefault();
            selectedSort = $(this).data('order'); // Récupérer l'ordre de tri
            paged = 1; // Réinitialiser la pagination
            let $button = $('#sortFilterBtn');
            let $loadMoreBtn = $('#loadMoreBtn');

            $button.text($(this).text()); // Met à jour le texte du bouton avec le tri sélectionné
            $loadMoreBtn.text('Afficher plus').prop('disabled', false);

            $.ajax({
                url: filter_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_photos_by_order',
                    security: filter_ajax_object.security,
                    category_id: selectedCategory,
                    format_id: selectedFormat,
                    paged: paged,
                    order: selectedSort // Passer l'option de tri
                },
                success: function(response) {
                    if (response.success) {
                        $('.image-bloc').html(response.data.content);
                        paged++;

                        if (!response.data.has_more) {
                            $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true);
                        }
                    } else {
                        $('.image-bloc').html('<p>' + response.data + '</p>');
                        $loadMoreBtn.text('Aucune photo supplémentaire').prop('disabled', true);
                    }
                    toggleResetButton();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Erreur AJAX : ' + textStatus);
                    $button.html('Trier par <i class="fa-solid fa-chevron-down"></i>');
                }
            });
            console.log({
                action: 'filter_photos_by_order',
                security: filter_ajax_object.security,
                category_id: selectedCategory || 'Aucune catégorie sélectionnée',
                format_id: selectedFormat || 'Aucun format sélectionné',
                paged: paged,
                order: selectedSort
            });
        });

        // Gestion du bouton de réinitialisation
        $('#resetFiltersBtn').on('click', function(e) {
            e.preventDefault();

            // Réinitialiser les valeurs des filtres
            selectedCategory = null;
            selectedFormat = null;
            selectedSort = 'desc';
            paged = 1;

            // Remettre les textes des boutons de filtre à leur valeur par défaut
            $('#categoryFilterBtn').text('Catégorie');
            $('#formatFilterBtn').text('Format');
            $('#sortFilterBtn').text('Trier par');

            // Recharger les photos sans filtres
            $.ajax({
                url: filter_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_photos_by_category', // Assurez-vous que l'action correspond à votre logique de chargement initial
                    security: filter_ajax_object.security,
                    category_id: selectedCategory,
                    format_id: selectedFormat,
                    paged: paged,
                    order: selectedSort
                },
                success: function(response) {
                    if (response.success) {
                        $('.image-bloc').html(response.data.content);
                        $('#loadMoreBtn').text('Afficher plus').prop('disabled', false);
                    } else {
                        $('.image-bloc').html('<p>' + response.data + '</p>');
                        $('#loadMoreBtn').text('Aucune photo supplémentaire').prop('disabled', true);
                    }
                    toggleResetButton();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Erreur AJAX : ' + textStatus);
                }
            });
        });
        toggleResetButton();
    });
});
