document.addEventListener('DOMContentLoaded', function () {

    const categoryBtn = document.getElementById('categoryFilterBtn');
    const formatBtn = document.getElementById('formatFilterBtn');
    const sortBtn = document.getElementById('sortFilterBtn');
    const categoryDropdown = document.getElementById('categoryDropdown');
    const formatDropdown = document.getElementById('formatDropdown');
    const sortDropdown = document.getElementById('sortDropdown');

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

    categoryBtn.addEventListener('click', function (e) {
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

    formatBtn.addEventListener('click', function (e) {
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

    sortBtn.addEventListener('click', function (e) {
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

    document.addEventListener('click', function () {
        closeAllDropdowns();
    });

    jQuery(document).ready(function ($) {
        let paged = 2; // Initialiser la page à 2 pour la première requête "Charger plus"
        let selectedCategory = null;
        let selectedFormat = null;
        let selectedSort = 'desc';

        function toggleResetButton() {
            if (selectedCategory || selectedFormat || selectedSort !== 'desc') {
                $('#resetFiltersBtn').css("display", "flex");
            } else {
                $('#resetFiltersBtn').css("display", "none");
            }
        }

        function setButtonText($button, text) {
            $button.html(`${text} <i class="fa-solid fa-chevron-down"></i>`);
        }

        // Fonction pour charger les photos avec filtres et pagination
        function loadPhotos(isFilterChange = false) {
            if (isFilterChange) {
                paged = 1; // Réinitialiser uniquement si le filtre change
                $('.image-bloc').html(''); // Effacer les photos affichées pour recharger
            }

            /* console.log("Chargement des photos avec les paramètres :", {
                paged: paged,
                category_id: selectedCategory,
                format_id: selectedFormat,
                order: selectedSort // Vérification que selectedSort est bien transmis
            }); */

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
                        $('#loadMoreBtn').text('Charger plus').prop('disabled', !response.data.has_more);
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

        // Gestion des filtres : catégories, formats et tri
        $('.category-option').on('click', function (e) {
            e.preventDefault();
            selectedCategory = $(this).data('term-id');
            loadPhotos(true); // Recharger avec le nouveau filtre
            setButtonText($('#categoryFilterBtn'), $(this).text());
        });

        $('.format-option').on('click', function (e) {
            e.preventDefault();
            selectedFormat = $(this).data('term-id');
            loadPhotos(true); // Recharger avec le nouveau filtre
            setButtonText($('#formatFilterBtn'), $(this).text());
        });

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