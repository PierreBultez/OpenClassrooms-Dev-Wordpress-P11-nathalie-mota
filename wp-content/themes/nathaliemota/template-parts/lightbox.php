<div id="lightbox-overlay" class="lightbox-overlay" style="display: none;">
    <div class="lightbox-content">
        <!-- Bouton de fermeture -->
        <button class="lightbox-close">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Navigation Précédente -->
        <button class="lightbox-prev">
            <i class="fa-solid fa-arrow-left-long"></i><span class="description-photo">Précédente</span>
        </button>

        <!-- Affichage de l'image -->
        <div class="lightbox-image-container">
            <img src="" alt="" class="lightbox-image">
        </div>

        <!-- Navigation Suivante -->
        <button class="lightbox-next">
            <span class="description-photo">Suivante</span><i class="fa-solid fa-arrow-right-long"></i>
        </button>

        <!-- Informations sur la photo -->
        <div class="lightbox-info">
            <span class="lightbox-reference description-photo">
                <?php echo esc_html(get_field('reference_photo')); ?>
            </span>
            <span class="lightbox-category description-photo">
                <?php
                // Récupérer la première catégorie associée à la taxonomie 'evenement'
                $terms = get_the_terms(get_the_ID(), 'evenement');
                if ($terms && !is_wp_error($terms)) {
                    echo esc_html($terms[0]->name);
                }
                ?>
            </span>
        </div>
    </div>
</div>

