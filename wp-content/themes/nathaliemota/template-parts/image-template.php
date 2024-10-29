<?php
// Assurez-vous que ce fichier est inclus dans une boucle WP Query
// Récupérer la référence de la photo (champ ACF)
$photo_reference = get_field('reference_photo', get_the_ID());

// Récupérer la catégorie (taxonomie evenement)
$photo_category = wp_get_post_terms(get_the_ID(), 'evenement');
$category_name = (!empty($photo_category) && !is_wp_error($photo_category)) ? $photo_category[0]->name : 'Non classé';

// Récupérer l'image attachée
$attachments = get_attached_media('image', get_the_ID());

if (!empty($attachments)) {
    foreach ($attachments as $attachment) {
        $attachment_url = wp_get_attachment_image_src($attachment->ID, 'photo-detail');
        ?>

        <div class="photo-item" style="background-image: url('<?php echo esc_url($attachment_url[0]); ?>');">
            <div class="photo-overlay">
                <span class="photo-reference description-photo"><?php echo esc_html($photo_reference); ?></span>
                <span class="photo-category description-photo"><?php echo esc_html($category_name); ?></span>
                <a href="<?php the_permalink(); ?>" class="photo-link">
                    <i class="fa-regular fa-eye"></i>
                </a>

                <!-- Icône expand en haut à droite -->
                <a href="#"
                   class="photo-expand"
                   data-photo-id="<?php echo get_the_ID(); ?>">
                    <i class="fa-solid fa-expand mota-expand"></i>
                </a>
            </div>
        </div>

        <?php
    }
}
?>
