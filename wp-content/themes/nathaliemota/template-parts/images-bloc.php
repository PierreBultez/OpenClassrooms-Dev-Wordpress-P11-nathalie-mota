<div class="filter-bar">
    <!-- Bouton pour filtrer par catégorie -->
    <div class="dropdown">
        <button class="btn-filter" id="categoryFilterBtn">Catégorie<i class="fa-solid fa-chevron-down"></i></button>
        <div class="dropdown-content" id="categoryDropdown">
            <?php
            $terms = get_terms(array(
                'taxonomy'   => 'evenement',
                'hide_empty' => true, // Masquer les catégories vides
            ));

            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    echo '<a href="#" class="category-option" data-term-id="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</a>';
                }
            }
            ?>
        </div>
    </div>
    <!-- Bouton pour filtrer par format -->
    <div class="dropdown">
        <button class="btn-filter" id="formatFilterBtn">Format<i class="fa-solid fa-chevron-down"></i></button>
        <div class="dropdown-content" id="formatDropdown">
            <?php
            $formats = get_terms(array(
                'taxonomy'   => 'format',
                'hide_empty' => true, // Masquer les formats vides
            ));

            if (!empty($formats) && !is_wp_error($formats)) {
                foreach ($formats as $format) {
                    echo '<a href="#" class="format-option" data-term-id="' . esc_attr($format->term_id) . '">' . esc_html($format->name) . '</a>';
                }
            }
            ?>
        </div>
    </div>
    <div class="dropdown" id="dropdown-right">
        <button class="btn-filter" id="sortFilterBtn">Trier par<i class="fa-solid fa-chevron-down"></i></button>
        <div class="dropdown-content" id="sortDropdown">
            <a href="#" class="sort-option" data-order="DESC">À partir des plus récentes</a>
            <a href="#" class="sort-option" data-order="ASC">À partir des plus anciennes</a>
        </div>
    </div>
</div>
<div class="reset-button">
    <button id="resetFiltersBtn" class="btn-reset">Réinitialiser les filtres<i class="fa-solid fa-xmark"></i></button>
</div>

<div class="image-bloc">
    <?php
    // Requête initiale pour récupérer les 8 premières photos
    $args = array(
        'post_type'      => 'photographies',
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'paged'          => 1, // Page initiale
    );

    $photo_query = new WP_Query( $args );

    if ( $photo_query->have_posts() ) {
        while ( $photo_query->have_posts() ) {
            $photo_query->the_post();

            // Inclure le template part pour afficher la photo
            get_template_part('template-parts/image-template');
        }
    } else {
        echo '<p>Aucune photo trouvée.</p>';
    }

    wp_reset_postdata();
    ?>
</div>
<div class="image-bloc-btn">
    <button type="button" id="loadMoreBtn" class="btn-submit description-photo">Charger plus</button>
</div>
