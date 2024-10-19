<?php
/**
 * Template Name: Single Photographie
 * Description: Template par défaut pour afficher les photographies.
 */

get_header(); // Inclut le fichier header.php du thème
?>
<div class="detail-photo">
    <div class="detail-photo-col-1">
        <h2>
            <!-- titre de la photo dynamique ACF -->champ ACF titre
        </h2>
        <div class="reference-photo" id="ref-photo">
            <!-- référence de la photo dynamique ACF --><p class="description-photo">champ ACF référence</p>
        </div>
        <div class="categorie-photo" id="cat-photo">
            <!-- catégorie de la photo dynamique CPT --><p class="description-photo">donnée CPT catégorie</p>
        </div>
        <div class="format-photo" id="format-photo">
            <!-- format de la photo dynamique CPT --><p class="description-photo">donnée CPT format</p>
        </div>
        <div class="type-photo" id="type-photo">
            <!-- type de la photo dynamique ACF --><p class="description-photo">champ ACF type</p>
        </div>
        <div class="annee-photo" id="annee-photo">
            <!-- année de la photo dynamique NATIF WP --><p class="description-photo">donnée native WP année</p>
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

<?php
get_footer(); // Inclut le fichier footer.php du thème
?>