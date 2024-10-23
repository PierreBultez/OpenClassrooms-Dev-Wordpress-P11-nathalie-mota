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

            // Récupérer les images jointes à la publication actuelle
            $attachments = get_attached_media( 'image', get_the_ID() );

            if ( !empty( $attachments ) ) {
                foreach ( $attachments as $attachment ) {
                    $attachment_url = wp_get_attachment_image_src( $attachment->ID, 'photo-detail' );

                    // Afficher chaque image attachée
                    echo '<div class="cross-sell-pic">';
                    echo '<img src="' . esc_url( $attachment_url[0] ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                    echo '</div>';
                }
            }
        }
    } else {
        echo '<p>Aucune photo trouvée.</p>';
    }

    wp_reset_postdata();
    ?>
</div>
<div class="image-bloc-btn">
    <button type="button" id="loadMoreBtn" class="btn-submit">Charger plus</button>
</div>

<?php
// Localisation du script AJAX
wp_localize_script('nathaliemota-scripts', 'ajax_object', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'security' => wp_create_nonce('load_more_nonce'), // Nonce pour la sécurité
));
?>