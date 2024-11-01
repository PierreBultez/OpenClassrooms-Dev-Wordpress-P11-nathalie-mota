<?php

/* Fonction pour initialiser les taxonomies et le type de post personnalisé */
function nathaliemota_init() {
    // Définir les taxonomies personnalisées
    $taxonomies = [
        'evenement' => 'Événement',
        'format' => 'Format de photo'
    ];

    // Enregistrer chaque taxonomie personnalisée
    foreach ($taxonomies as $taxonomy => $label) {
        register_taxonomy($taxonomy, 'photographies', [
            'hierarchical' => true,
            'labels' => [
                'name' => __($label . 's'),
                'singular_name' => __($label),
                'search_items' => __('Rechercher un ' . strtolower($label)),
                'all_items' => __('Tous les ' . strtolower($label) . 's'),
                'edit_item' => __('Editer le ' . strtolower($label)),
                'update_item' => __('Mettre à jour le ' . strtolower($label)),
                'add_new_item' => __('Ajouter un nouveau ' . strtolower($label)),
                'new_item_name' => __('Nouveau ' . strtolower($label)),
                'menu_name' => __($label . 's'),
            ],
            'show_in_rest' => true,
            'show_admin_column' => true,
        ]);
    }

    // Enregistrer le type de post personnalisé "photographies"
    register_post_type('photographies', [
        'labels' => [
            'name' => __('Photographies'),
            'singular_name' => __('Photographie'),
            'search_items' => __('Rechercher une photographie'),
            'all_items' => __('Toutes les photographies'),
            'edit_item' => __('Editer une photographie'),
            'update_item' => __('Mettre à jour la photographie'),
            'add_new' => __('Ajouter une nouvelle photographie'),
            'new_item_name' => __('Nouvelle photographie'),
            'menu_name' => __('Photographies'),
            'not_found' => __('Aucune photographie trouvée'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-camera',
        'menu_position' => 3,
    ]);
}

/* Fonction pour configurer le thème après son activation */
function nathaliemota_setup () {
    // Ajoute la prise en charge du titre de la page
    add_theme_support('title-tag');

    // Enregistre les menus de navigation
    register_nav_menus(array(
        'main_menu' => __('Menu principal', ''),
        'footer_menu' => __('Menu de footer', ''),
    ));

    // Activer le support du logo personnalisé
    add_theme_support('custom-logo', array(
        'height'      => 22,
        'width'       => 345,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Ajoute des tailles d'image personnalisées
    add_image_size('photo-detail', 844, 844);
    add_image_size('photo-detail-thumb', 80, 80, true);
    add_image_size('photo-lightbox-landscape', 844, 563, true);
    add_image_size('photo-lightbox-portrait', 376, 563, true);
    add_image_size('hero', 1440, 960, true);
}

/* Fonction pour enregistrer et inclure les styles et scripts */
function nathaliemota_register_assets() {
    // Définir les styles à enregistrer et à inclure
    $styles = [
        'bootstrap' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        'nathaliemota' => get_stylesheet_directory_uri() . '/style.css',
        'nathaliemota-lightbox' => get_stylesheet_directory_uri() . '/css/lightbox.css',
        'nathaliemota-mediaQueries' => get_stylesheet_directory_uri() . '/css/mediaQueries.css',
        'fontawesome' => get_stylesheet_directory_uri() . '/assets/fontawesome/css/fontawesome.css',
        'fontawesome-all' => get_stylesheet_directory_uri() . '/assets/fontawesome/css/all.css',
    ];

    // Enregistrer et inclure chaque style
    foreach ($styles as $handle => $src) {
        wp_register_style($handle, $src);
        wp_enqueue_style($handle);
    }

    // Définir les scripts à enregistrer et à inclure
    $scripts = [
        'bootstrap' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        'nathaliemota-scripts' => get_stylesheet_directory_uri() . '/scripts/scripts.js',
        'lightbox-script' => get_stylesheet_directory_uri() . '/scripts/lightbox.js',
    ];

    // Enregistrer et inclure chaque script
    foreach ($scripts as $handle => $src) {
        wp_register_script($handle, $src, ['jquery'], false, true);
        wp_enqueue_script($handle);
    }

    // Localiser le script lightbox avec des données AJAX
    wp_localize_script('lightbox-script', 'lightbox_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('lightbox_nonce'),
    ]);

    // Enregistrer et inclure des scripts spécifiques pour la page d'accueil
    if (is_front_page() || is_home()) {
        wp_register_script('home-scripts', get_stylesheet_directory_uri() . '/scripts/home-scripts.js', ['jquery'], false, true);
        wp_enqueue_script('home-scripts');
        wp_localize_script('home-scripts', 'filter_ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('filter_nonce'),
        ]);
        wp_localize_script('home-scripts', 'load_more_ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('load_more_nonce'),
        ]);
    }

    // Enregistrer et inclure des scripts spécifiques pour les pages de type "photographies"
    if (is_singular('photographies')) {
        wp_register_script('single-photographies-scripts', get_stylesheet_directory_uri() . '/scripts/single-photographies-scripts.js', ['jquery'], false, true);
        wp_enqueue_script('single-photographies-scripts');
        wp_localize_script('single-photographies-scripts', 'photo_navigation_ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id' => get_the_ID(),
            'security' => wp_create_nonce('photo_navigation_nonce'),
        ]);
    }
}

/* Fonction pour ajouter un champ meta description dans l'éditeur WP */
function custom_meta_description_box()
{
    add_meta_box(
        'meta_description_id',
        'Meta Description',
        'custom_meta_description_callback',
        'post',
        'side'
    );
    add_meta_box(
        'meta_description_id',
        'Meta Description',
        'custom_meta_description_callback',
        'page',
        'side'
    );
    add_meta_box(
        'meta_description_id',
        'Meta Description',
        'custom_meta_description_callback',
        'photographies',
        'side'
    );
}

function custom_meta_description_callback($post)
{
    $value = get_post_meta($post->ID, '_custom_meta_description', true);
    echo '<textarea style="width:100%;" rows="4" name="custom_meta_description">' . esc_attr($value) . '</textarea>';
}

function save_custom_meta_description($post_id)
{
    if (array_key_exists('custom_meta_description', $_POST)) {
        update_post_meta(
            $post_id,
            '_custom_meta_description',
            sanitize_textarea_field($_POST['custom_meta_description'])
        );
    }
}

/* Fonction pour afficher une meta description dynamique */
function get_dynamic_meta_description()
{
    if (is_single() || is_page()) {
        global $post;
        $custom_meta_description = get_post_meta($post->ID, '_custom_meta_description', true);
        if ($custom_meta_description) {
            return esc_attr($custom_meta_description);
        } else {
            // Fallback to excerpt if no custom meta description is set
            return esc_attr(wp_trim_words($post->post_content, 30, '...'));
        }
    } elseif (is_category()) {
        $category_description = category_description();
        return esc_attr($category_description);
    } else {
        return "Nathalie Mota photographe freelance professionnelle spécialisée dans l'événementiel.";
    }
}

/* Fonction pour récupérer une image aléatoire pour le héros */
function get_random_hero_image() {
    // Requête pour récupérer les posts de type "photographies"
    $args = array(
        'post_type'      => 'photographies',
        'posts_per_page' => -1, // Récupérer tous les posts
        'post_status'    => 'publish',
    );

    $photo_query = new WP_Query($args);

    if ($photo_query->have_posts()) {
        $photos = array();

        // Parcourir les posts pour récupérer les images attachées
        while ($photo_query->have_posts()) {
            $photo_query->the_post();
            $attachments = get_attached_media('image', get_the_ID());

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $photos[] = $attachment->ID;
                }
            }
        }

        // Réinitialiser les données de la requête principale
        wp_reset_postdata();

        // Sélectionner une image aléatoire
        if (!empty($photos)) {
            $random_attachment_id = $photos[array_rand($photos)];
            return wp_get_attachment_image_src($random_attachment_id, 'hero')[0];
        }
    }

    return false;
}

/* Fonction pour charger plus de photographies */
function load_more_photos() {
    // Vérifie le nonce pour la sécurité
    if (!check_ajax_referer('load_more_nonce', 'security', false)) {
        wp_send_json_error('Nonce invalide.');
        wp_die();
    }

    // Récupère les paramètres de la requête AJAX
    $paged = intval($_POST['paged'] ?? 1);
    $category_id = intval($_POST['category_id'] ?? 0);
    $format_id = intval($_POST['format_id'] ?? 0);
    $order = in_array($_POST['order'] ?? 'DESC', ['ASC', 'DESC']) ? sanitize_text_field($_POST['order']) : 'DESC';

    // Crée une requête taxonomique si nécessaire
    $tax_query = array_filter([
        $category_id ? ['taxonomy' => 'evenement', 'field' => 'term_id', 'terms' => $category_id] : null,
        $format_id ? ['taxonomy' => 'format', 'field' => 'term_id', 'terms' => $format_id] : null,
    ]);

    // Définit les arguments de la requête WP_Query
    $args = array(
        'post_type'      => 'photographies',
        'posts_per_page' => 8,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'tax_query'      => $tax_query,
        'order'          => $order,
        'orderby'        => 'date',
    );

    // Exécute la requête WP_Query
    $photo_query = new WP_Query($args);

    if ($photo_query->have_posts()) {
        ob_start();
        while ($photo_query->have_posts()) {
            $photo_query->the_post();
            get_template_part('template-parts/image-template');
        }

        $content = ob_get_clean();
        $has_more_posts = $photo_query->max_num_pages > $paged;

        wp_send_json_success(array('content' => $content, 'has_more' => $has_more_posts));
    } else {
        wp_send_json_error('Aucune photo supplémentaire trouvée.');
    }

    // Réinitialise les données de la requête principale
    wp_reset_postdata();
    wp_die();
}

/* Fonction AJAX pour récupérer la photo précédente sur single-photographies.php */
function get_previous_photo_ajax() {

    // Vérifier le nonce pour la sécurité
    check_ajax_referer('photo_navigation_nonce', 'security');

    // Récupérer l'ID du post actuel depuis la requête AJAX
    $current_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ($current_post_id == 0) {
        wp_send_json_error('ID du post actuel non valide.');
        return;
    }

    // Créer une requête personnalisée pour récupérer le post précédent
    $args = array(
        'post_type'      => 'photographies', // Type de post personnalisé
        'posts_per_page' => 1,               // Un seul post
        'post_status'    => 'publish',       // Post publié
        'orderby'        => 'date',          // Trier par date
        'order'          => 'DESC',          // Ordre décroissant
        'post__not_in'   => array($current_post_id), // Exclure le post actuel
        'date_query'     => array(
            'before' => get_the_date('Y-m-d H:i:s', $current_post_id), // Récupérer les posts avant le post actuel
        ),
    );

    $previous_query = new WP_Query($args);

    $previous_image_url = '';
    $previous_post_url = '';

    if ($previous_query->have_posts()) {
        while ($previous_query->have_posts()) {
            $previous_query->the_post();
            $previous_post_id = get_the_ID();
            // Récupérer l'URL du post
            $previous_post_url = get_permalink($previous_post_id);

            // Récupérer l'image attachée
            $attachments = get_attached_media('image', $previous_post_id);
            if (!empty($attachments)) {
                $attachment = array_shift($attachments);
                $previous_image_url = wp_get_attachment_image_src($attachment->ID, 'photo-detail-thumb')[0];
            } else {
                error_log('Aucune image attachée trouvée pour ce post.');
            }
        }
    } else {
        error_log('Aucun post précédent trouvé.');
    }

    wp_reset_postdata(); // Réinitialiser les données de la requête principale

    // Retourner l'URL de l'image
    if (!empty($previous_image_url)) {
        wp_send_json_success(array('image_url' => $previous_image_url, 'post_url' => $previous_post_url));
    } else {
        wp_send_json_error('Aucune image trouvée pour la photo précédente.');
    }

    // Réinitialise les données de la requête principale
    wp_reset_postdata();
    wp_die();
}

/* Fonction AJAX pour récupérer la photo suivante sur single-photographies.php */
function get_next_photo_ajax() {

    // Vérifier le nonce pour la sécurité
    check_ajax_referer('photo_navigation_nonce', 'security');

    // Récupérer l'ID du post actuel depuis la requête AJAX
    $current_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ($current_post_id == 0) {
        wp_send_json_error('ID du post actuel non valide.');
        return;
    }

    // Créer une requête personnalisée pour récupérer le post suivant
    $args = array(
        'post_type'      => 'photographies', // Type de post personnalisé
        'posts_per_page' => 1,               // Un seul post
        'post_status'    => 'publish',       // Post publié
        'orderby'        => 'date',          // Trier par date
        'order'          => 'ASC',           // Ordre croissant pour obtenir le post suivant
        'post__not_in'   => array($current_post_id), // Exclure le post actuel
        'date_query'     => array(
            'after' => get_the_date('Y-m-d H:i:s', $current_post_id), // Récupérer les posts après le post actuel
        ),
    );

    $next_query = new WP_Query($args);

    $next_image_url = '';
    $next_post_url = '';

    if ($next_query->have_posts()) {
        while ($next_query->have_posts()) {
            $next_query->the_post();
            $next_post_id = get_the_ID();
            // Récupérer l'URL du post
            $next_post_url = get_permalink($next_post_id);

            // Récupérer l'image attachée
            $attachments = get_attached_media('image', $next_post_id);
            if (!empty($attachments)) {
                $attachment = array_shift($attachments);
                $next_image_url = wp_get_attachment_image_src($attachment->ID, 'photo-detail-thumb')[0];
            } else {
                error_log('Aucune image attachée trouvée pour ce post.');
            }
        }
    } else {
        error_log('Aucun post suivant trouvé.');
    }

    wp_reset_postdata(); // Réinitialiser les données de la requête principale

    // Retourner l'URL de l'image
    if (!empty($next_image_url)) {
        wp_send_json_success(array('image_url' => $next_image_url, 'post_url' => $next_post_url));
    } else {
        wp_send_json_error('Aucune image trouvée pour la photo suivante.');
    }

    // Réinitialise les données de la requête principale
    wp_reset_postdata();
    wp_die();
}

/* Fonction AJAX pour récupérer les informations des photographies */
function get_photo_data() {
    check_ajax_referer('lightbox_nonce', 'security');

    $photo_id = intval($_POST['photo_id'] ?? 0);

    if ($photo_id) {
        $photo_url = wp_get_attachment_image_url(get_post_thumbnail_id($photo_id), 'photo-detail') ?: wp_get_attachment_image_url(reset(get_attached_media('image', $photo_id))->ID, 'photo-detail');
        $photo_reference = get_field('reference_photo', $photo_id);
        $photo_category = wp_get_post_terms($photo_id, 'evenement')[0]->name ?? 'Non classé';

        if ($photo_url) {
            wp_send_json_success(['id' => $photo_id, 'url' => $photo_url, 'reference' => $photo_reference, 'category' => $photo_category]);
        } else {
            wp_send_json_error('Image non trouvée.');
        }
    } else {
        wp_send_json_error('Photo non trouvée.');
    }
    wp_die();
}

/* Fonction AJAX pour récupérer la photo précédente dans la lightbox */
function get_previous_lightbox_photo_ajax() {
    // Vérifier le nonce pour la sécurité
    check_ajax_referer('lightbox_nonce', 'security');

    // Récupérer l'ID du post actuel depuis la requête AJAX
    $current_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ($current_post_id == 0) {
        wp_send_json_error('ID du post actuel non valide.');
        return;
    }

    // Créer une requête personnalisée pour récupérer le post précédent
    $args = array(
        'post_type'      => 'photographies', // Type de post personnalisé
        'posts_per_page' => 1,               // Un seul post
        'post_status'    => 'publish',       // Post publié
        'orderby'        => 'date',          // Trier par date
        'order'          => 'DESC',          // Ordre décroissant
        'post__not_in'   => array($current_post_id), // Exclure le post actuel
        'date_query'     => array(
            'before' => get_the_date('Y-m-d H:i:s', $current_post_id), // Récupérer les posts avant le post actuel
        ),
    );

    $previous_query = new WP_Query($args);

    if ($previous_query->have_posts()) {
        $previous_query->the_post();
        $previous_post_id = get_the_ID();
        // Récupérer l'URL de l'image pour la lightbox
        $image_url = get_lightbox_image_url(get_post_thumbnail_id($previous_post_id));

        if (!$image_url) {
            // Si aucune image en miniature, récupérer la première image attachée
            $attachments = get_attached_media('image', $previous_post_id);
            if (!empty($attachments)) {
                $attachment = reset($attachments);
                $image_url = get_lightbox_image_url($attachment->ID);
            }
        }

        // Renvoyer l'ID du post et l'URL de l'image
        wp_send_json_success([
            'id'        => $previous_post_id,
            'url'       => $image_url,
            'reference' => get_field('reference_photo', $previous_post_id),
            'category'  => wp_get_post_terms($previous_post_id, 'evenement')[0]->name ?? 'Non classé'
        ]);
    } else {
        wp_send_json_error('Aucune photo précédente trouvée.');
    }

    wp_reset_postdata(); // Réinitialiser les données de la requête principale
    wp_die();
}

/* Fonction AJAX pour récupérer la photo suivante dans la lightbox */
function get_next_lightbox_photo_ajax() {

    // Vérifier le nonce pour la sécurité
    check_ajax_referer('lightbox_nonce', 'security');

    // Récupérer l'ID du post actuel depuis la requête AJAX
    $current_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ($current_post_id == 0) {
        wp_send_json_error('ID du post actuel non valide.');
        return;
    }

    // Créer une requête personnalisée pour récupérer le post suivant
    $args = array(
        'post_type'      => 'photographies', // Type de post personnalisé
        'posts_per_page' => 1,               // Un seul post
        'post_status'    => 'publish',       // Post publié
        'orderby'        => 'date',          // Trier par date
        'order'          => 'ASC',           // Ordre croissant pour obtenir le post suivant
        'post__not_in'   => array($current_post_id), // Exclure le post actuel
        'date_query'     => array(
            'after' => get_the_date('Y-m-d H:i:s', $current_post_id), // Récupérer les posts après le post actuel
        ),
    );

    $next_query = new WP_Query($args);

    if ($next_query->have_posts()) {
        $next_query->the_post();
        $next_post_id = get_the_ID();
        // Récupérer l'URL de l'image pour la lightbox
        $image_url = get_lightbox_image_url(get_post_thumbnail_id($next_post_id));

        if (!$image_url) {
            // Si aucune image en miniature, récupérer la première image attachée
            $attachments = get_attached_media('image', $next_post_id);
            if (!empty($attachments)) {
                $attachment = reset($attachments);
                $image_url = get_lightbox_image_url($attachment->ID);
            }
        }

        // Renvoyer l'ID du post et l'URL de l'image
        wp_send_json_success([
            'id'        => $next_post_id,
            'url'       => $image_url,
            'reference' => get_field('reference_photo', $next_post_id),
            'category'  => wp_get_post_terms($next_post_id, 'evenement')[0]->name ?? 'Non classé'
        ]);
    } else {
        wp_send_json_error('Aucune photo suivante trouvée.');
    }

    wp_reset_postdata(); // Réinitialiser les données de la requête principale
    wp_die();
}

/* Fonction pour récupérer l'URL de l'image pour la lightbox */
function get_lightbox_image_url($attachment_id) {
    if (!$attachment_id) {
        return false;
    }

    // Récupérer les informations de l'image pour vérifier l'orientation
    $image_data = wp_get_attachment_metadata($attachment_id);
    if (!$image_data) {
        return false;
    }

    // Calculer l'orientation
    $orientation = ($image_data['width'] > $image_data['height']) ? 'landscape' : 'portrait';

    // Retourner l'URL de l'image en fonction de l'orientation
    if ($orientation === 'landscape') {
        return wp_get_attachment_image_url($attachment_id, 'photo-lightbox-landscape');
    } else {
        return wp_get_attachment_image_url($attachment_id, 'photo-lightbox-portrait');
    }
}

// Action pour initialiser les taxonomies et le type de post personnalisé
add_action('init', 'nathaliemota_init');

// Action pour configurer le thème après son activation
add_action('after_setup_theme', 'nathaliemota_setup');

// Action pour enregistrer et inclure les styles et scripts
add_action('wp_enqueue_scripts', 'nathaliemota_register_assets');

// Actions AJAX pour récupérer la photo précédente
add_action('wp_ajax_get_previous_photo', 'get_previous_photo_ajax');
add_action('wp_ajax_nopriv_get_previous_photo', 'get_previous_photo_ajax');

// Actions AJAX pour récupérer la photo suivante
add_action('wp_ajax_get_next_photo', 'get_next_photo_ajax');
add_action('wp_ajax_nopriv_get_next_photo', 'get_next_photo_ajax');

// Actions AJAX pour charger plus de photos
add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

// Actions AJAX pour récupérer les données d'une photo
add_action('wp_ajax_get_photo_data', 'get_photo_data');
add_action('wp_ajax_nopriv_get_photo_data', 'get_photo_data');

// Actions AJAX pour récupérer la photo précédente dans la lightbox
add_action('wp_ajax_get_previous_lightbox_photo_ajax', 'get_previous_lightbox_photo_ajax');
add_action('wp_ajax_nopriv_get_previous_lightbox_photo_ajax', 'get_previous_lightbox_photo_ajax');

// Actions AJAX pour récupérer la photo suivante dans la lightbox
add_action('wp_ajax_get_next_lightbox_photo_ajax', 'get_next_lightbox_photo_ajax');
add_action('wp_ajax_nopriv_get_next_lightbox_photo_ajax', 'get_next_lightbox_photo_ajax');

add_action('add_meta_boxes', 'custom_meta_description_box');
add_action('save_post', 'save_custom_meta_description');
