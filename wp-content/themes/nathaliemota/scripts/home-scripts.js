document.addEventListener('DOMContentLoaded', function () {
    // Récupération des éléments du DOM
    const categoryBtn = document.getElementById('categoryFilterBtn');
    const formatBtn = document.getElementById('formatFilterBtn');
    const sortBtn = document.getElementById('sortFilterBtn');
    const categoryDropdown = document.getElementById('categoryDropdown');
    const formatDropdown = document.getElementById('formatDropdown');
    const sortDropdown = document.getElementById('sortDropdown');

    // Fonction pour fermer tous les dropdowns
    function closeAllDropdowns() {
        [categoryDropdown, formatDropdown, sortDropdown].forEach(dropdown => dropdown.classList.remove('active'));
        [categoryBtn, formatBtn, sortBtn].forEach(btn => btn.classList.remove('active'));
        [categoryBtn, formatBtn, sortBtn].forEach(btn => {
            const icon = btn.querySelector('i');
            if (icon) {
                icon.classList.add('fa-chevron-down');
                icon.classList.remove('fa-chevron-up');
            }
        });
    }

    // Fonction pour gérer le clic sur les boutons de dropdown
    function toggleDropdown(btn, dropdown) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation(); // Empêche le clic de se propager
            const isActive = dropdown.classList.contains('active');
            closeAllDropdowns();
            if (!isActive) {
                dropdown.classList.add('active');
                btn.classList.add('active');
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }
        });
    }

    // Ajout des gestionnaires de clic pour les boutons de dropdown
    toggleDropdown(categoryBtn, categoryDropdown);
    toggleDropdown(formatBtn, formatDropdown);
    toggleDropdown(sortBtn, sortDropdown);

    // Ferme tous les dropdowns lorsque l'utilisateur clique en dehors
    document.addEventListener('click', closeAllDropdowns);

    jQuery(document).ready(function ($) {
        let paged = 2; // Initialiser la page à 2 pour la première requête "Charger plus"
        let selectedCategory = null;
        let selectedFormat = null;
        let selectedSort = 'desc';

        // Affiche ou cache le bouton de réinitialisation des filtres
        function toggleResetButton() {
            $('#resetFiltersBtn').css("display", selectedCategory || selectedFormat || selectedSort !== 'desc' ? "flex" : "none");
        }

        // Met à jour le texte du bouton avec l'icône
        function setButtonText($button, text) {
            $button.html(`${text} <i class="fa-solid fa-chevron-down"></i>`);
        }

        // Fonction pour charger les photos avec filtres et pagination
        function loadPhotos(isFilterChange = false) {
            if (isFilterChange) {
                paged = 1; // Réinitialiser uniquement si le filtre change
                $('.image-bloc').html(''); // Effacer les photos affichées pour recharger
            }

            // Requête AJAX pour charger les photos
            $.ajax({
                url: filter_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'load_more_photos',
                    security: load_more_ajax_object.security,
                    category_id: selectedCategory,
                    format_id: selectedFormat,
                    paged: paged,
                    order: selectedSort
                },
                success: function (response) {
                    if (response.success) {
                        $('.image-bloc').append(response.data.content);
                        paged++; // Incrémenter la page pour les chargements suivants
                        if (response.data.has_more) {
                            $('#loadMoreBtn').text('Charger plus').prop('disabled', false);
                        } else {
                            $('#loadMoreBtn').text('Aucune photo supplémentaire').prop('disabled', true);
                        }
                    } else {
                        $('#loadMoreBtn').text('Aucune photo supplémentaire').prop('disabled', true);
                        console.log('Erreur de réponse: ', response.data);
                    }
                    toggleResetButton();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Erreur AJAX : ' + textStatus);
                    $('#loadMoreBtn').text('Erreur, réessayez');
                }
            });
        }

        // Gestion des filtres : catégories
        $('.category-option').on('click', function (e) {
            e.preventDefault();
            selectedCategory = $(this).data('term-id');
            loadPhotos(true); // Recharger avec le nouveau filtre
            setButtonText($('#categoryFilterBtn'), $(this).text());
        });

        // Gestion des filtres : formats
        $('.format-option').on('click', function (e) {
            e.preventDefault();
            selectedFormat = $(this).data('term-id');
            loadPhotos(true); // Recharger avec le nouveau filtre
            setButtonText($('#formatFilterBtn'), $(this).text());
        });

        // Gestion des filtres : tri
        $('.sort-option').on('click', function (e) {
            e.preventDefault();
            selectedSort = $(this).data('order'); // Assurez-vous que le tri est mis à jour
            loadPhotos(true); // Recharger avec le tri sélectionné
            setButtonText($('#sortFilterBtn'), $(this).text());
        });

        // Bouton "Charger plus" pour pagination
        $('#loadMoreBtn').on('click', function (e) {
            e.preventDefault();
            loadPhotos(); // Charger la page suivante en conservant les filtres
        });

        // Bouton de réinitialisation des filtres
        $('#resetFiltersBtn').on('click', function (e) {
            e.preventDefault();
            selectedCategory = null;
            selectedFormat = null;
            selectedSort = 'desc';
            setButtonText($('#categoryFilterBtn'), 'Catégorie');
            setButtonText($('#formatFilterBtn'), 'Format');
            setButtonText($('#sortFilterBtn'), 'Trier par');
            loadPhotos(true); // Recharger sans filtre
        });

        toggleResetButton();
    });
});