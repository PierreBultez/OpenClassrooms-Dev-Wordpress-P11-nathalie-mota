<?php
/**
 * Template Name: Single Photographie
 * Description: Template par défaut pour afficher les photographies.
 */

get_header(); // Inclut le fichier header.php du thème
?>
<div class="detail-photo">
    <div class="detail-photo-col-1">
        <?php
        // Afficher le titre de la photographie
        echo '<h2>' . get_the_title() . '</h2>';
        ?>
        <div class="reference-photo" id="ref-photo">
            <?php
            // Utiliser get_field() pour récupérer la valeur du champ personnalisé
            $reference = get_field('reference_photo');

            // Si une référence est définie, l'afficher
            if ($reference) {
                echo '<p class="description-photo">Référence : ' . esc_html($reference) . '</p>';
            } else {
                echo '<p class="description-photo">Aucune référence trouvée.</p>';
            }
            ?>
        </div>
        <div class="categorie-photo" id="cat-photo">
            <?php
            // Récupérer les termes de la taxonomie "événement"
            $evenements = get_the_terms( get_the_ID(), 'evenement' );

            if ( $evenements && ! is_wp_error( $evenements ) ) {
                // Récupérer le premier terme seulement
                $evenement = $evenements[0];
                echo '<p class="description-photo">Catégorie : ' . esc_html( $evenement->name ) . '</p>';
            }
            ?>
        </div>
        <div class="format-photo" id="format-photo">
            <?php
            // Récupérer les termes de la taxonomie "format"
            $formats = get_the_terms( get_the_ID(), 'format' );

            if ( $formats && ! is_wp_error( $formats ) ) {
                // Récupérer le premier terme seulement
                $format = $formats[0];
                echo '<p class="description-photo">Format : ' . esc_html( $format->name ) . '</p>';
            }
            ?>
        </div>
        <div class="type-photo" id="type-photo">
            <?php
            // Utiliser get_field() pour récupérer la valeur du champ personnalisé
            $type = get_field('type_photo');

            // Si une référence est définie, l'afficher
            if ($type) {
                echo '<p class="description-photo">Type : ' . esc_html($type) . '</p>';
            } else {
                echo '<p class="description-photo">Aucun type de photo trouvé.</p>';
            }
            ?>
        </div>
        <div class="annee-photo" id="annee-photo">
            <?php
            // Récupérer et afficher l'année de publication
            $year = get_the_date('Y');
            echo '<p class="description-photo">Année : ' . esc_html($year) . '</p>';
            ?>
        </div>
    </div>
    <div class="detail-photo-col-2">
        <?php
        // Récupérer les pièces jointes (images) associées à cet article
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_parent' => get_the_ID(),
            'posts_per_page' => 1, // Récupérer seulement la première image
        );
        $attachments = get_children( $args );

        // Si des images sont trouvées, afficher la première
        if ( $attachments ) {
            foreach ( $attachments as $attachment_id => $attachment ) {
                echo wp_get_attachment_image( $attachment_id, 'photo-detail' );
            }
        }
        ?>
    </div>
</div>
<div class="detail-photo-bottom">
    <div class="divider">
        <div class="detail-photo-bottom-contact">
            <p>Cette photo vous intèresse ?</p>
            <button type="button" class="btn-submit" id=contactBtn>Contact</button>
        </div>
        <div class="detail-photo-bottom-next-prev">
            <div class="next-prev-nav">
                <div class="photo-preview">
                    <img id="previewImg" src="<?php echo get_stylesheet_directory_uri() . '/assets/images/nathalie-0-80x80.jpeg'; ?>" alt="Preview image" style="opacity: 0;">
                </div>
                <div class="next-prev-arrows">
                    <div class="previous-photo">
                        <a id="prevArrow" href="#">
                            <i class="fa-solid fa-arrow-left-long"></i>
                        </a>
                    </div>
                    <div class="next-photo">
                        <a id="nextArrow" href="#">
                            <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="cross-sell">
    <h3>Vous aimerez aussi</h3>
</div>
<div class="image-bloc">
    <?php
    // Récupérer les termes de la taxonomie "evenement" associés à la photo actuelle
    $terms = get_the_terms( get_the_ID(), 'evenement' );

    if ( $terms && ! is_wp_error( $terms ) ) {
        $term_ids = wp_list_pluck( $terms, 'term_id' ); // Récupère les IDs des termes

        // Requête pour récupérer deux autres photos de la même catégorie (taxonomie "evenement")
        $args = array(
            'post_type'      => 'photographies',
            'posts_per_page' =>2,
            'post__not_in'   => array( get_the_ID() ), // Exclure la photo actuelle
            'tax_query'      => array(
                array(
                    'taxonomy' => 'evenement',
                    'field'    => 'term_id',
                    'terms'    => $term_ids, // Utilise les IDs des termes
                ),
            ),
        );

        // Exécuter la requête personnalisée
        $cross_sell_query = new WP_Query( $args );

        if ( $cross_sell_query->have_posts() ) {
            $count = 1;
            while ( $cross_sell_query->have_posts() ) {
                $cross_sell_query->the_post();

                // Inclure le template part pour chaque photo cross-sell
                get_template_part('template-parts/image-template');
            }
        } else {
            echo '<p>Aucune autre photo trouvée dans cette catégorie.</p>';
        }

        // Restaurer la requête principale
        wp_reset_postdata();
    }
    ?>
</div>

<?php
get_footer(); // Inclut le fichier footer.php du thème
?>