<?php

function nathaliemota_init () {
    register_taxonomy('evenement', 'photographies', array(
        'hierarchical'  => true,
        'labels'        => array(
            'name'          => __('Événements'),
            'singular_name' => __('Événement'),
            'plural_name'   => __('Événements'),
            'search_items'  => __('Rechercher un événement'),
            'all_items'     => __('Tous les événements'),
            'edit_item'     => __('Editer l\'événement'),
            'update_item'   => __('Mettre à jour l\'événement'),
            'add_new_item'  => __('Ajouter un nouvel événement'),
            'new_item_name' => __('Nouvel événement'),
            'menu_name'     => __('Événements'),
        ),
        'show_in_rest'  => true,
        'show_admin_column' => true,
    ));
    register_taxonomy('format', 'photographies', array(
        'hierarchical'  => true,
        'labels'        => array(
            'name'          => __('Format de photo'),
            'singular_name' => __('Format de photo'),
            'plural_name'   => __('Formats de photo'),
            'search_items'  => __('Rechercher un format de photo'),
            'all_items'     => __('Tous les formats de photo'),
            'edit_item'     => __('Editer le format de photo'),
            'update_item'   => __('Mettre à jour le format de photo'),
            'add_new_item'  => __('Ajouter un nouveau format de photo'),
            'new_item_name' => __('Nouveau format de photo'),
            'menu_name'     => __('Formats de photo'),
        ),
        'show_in_rest'  => true,
        'show_admin_column' => true,
    ));
    register_post_type('photographies', array(
        'labels'        => array(
            'name'          => __('Photographies'),
            'singular_name' => __('Photographie'),
            'plural_name'   => __('Photographies'),
            'search_items'  => __('Rechercher une photographie'),
            'all_items'     => __('Toutes les photographies'),
            'edit_item'     => __('Editer une photographie'),
            'update_item'   => __('Mettre à jour la photographie'),
            'add_new'       => __('Ajouter une nouvelle photographie'),
            'add_new_item'  => __('Ajouter une nouvelle photographie'),
            'new_item_name' => __('Nouvelle photographie'),
            'menu_name'     => __('Photographies'),
            'not_found'     => __('Aucune photographie trouvée'),
        ),
        'public'        => true,
        'menu_icon'     => 'dashicons-camera',
        'menu_position' => 3,
    ));
}

function nathaliemota_setup () {
    add_theme_support('title-tag');
    // add_theme_support('menus'); ***** Pas besoin avec register_nav_menus() *****
    register_nav_menus( array(
        'main_menu' => __( 'Menu principal', '' ),
        'footer_menu'  => __( 'Menu de footer', '' ),
    ) );
    add_image_size( 'photo-detail', 844, 844 );
    add_image_size( 'photo-detail-thumb', 80, 80, true );
    add_image_size( 'photo-lightbox-landscape', 844, 563, true );
    add_image_size( 'photo-lightbox-portrait', 376, 563, true );
}

function nathaliemota_register_assets () {
    wp_register_style('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_deregister_script('bootstrap');
    wp_register_script('bootstrap' ,'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], false, true);
    wp_enqueue_style('bootstrap');
    wp_enqueue_script('bootstrap');
    wp_register_style('nathaliemota', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('nathaliemota');
    wp_register_style('nathaliemota-lightbox', get_stylesheet_directory_uri() . '/css/lightbox.css');
    wp_enqueue_style('nathaliemota-lightbox');
    wp_register_style('nathaliemota-mediaQueries', get_stylesheet_directory_uri() . '/css/mediaQueries.css');
    wp_enqueue_style('nathaliemota-mediaQueries');
    wp_register_style('fontawesome', get_stylesheet_directory_uri() . '/assets/fontawesome/css/fontawesome.css');
    wp_register_style('fontawesome-all', get_stylesheet_directory_uri() . '/assets/fontawesome/css/all.css');
    wp_enqueue_style('fontawesome');
    wp_enqueue_style('fontawesome-all');
    wp_register_script('nathaliemota-scripts', get_stylesheet_directory_uri() . '/scripts/scripts.js', ['jquery'], false, true);
    wp_enqueue_script('nathaliemota-scripts');
    wp_register_script('lightbox-script', get_stylesheet_directory_uri() . '/scripts/lightbox.js', ['jquery'], false, true);
    wp_enqueue_script('lightbox-script');
    wp_localize_script('lightbox-script', 'lightbox_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('lightbox_nonce'),
    ));

    // Script de la page d'accueil pour filtres et pagination
    if (is_front_page() || is_home()) {
        wp_register_script('home-scripts', get_stylesheet_directory_uri() . '/scripts/home-scripts.js', ['jquery'], false, true);
        wp_enqueue_script('home-scripts');
        wp_localize_script('home-scripts', 'filter_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('filter_nonce'),
        ));
        wp_localize_script('home-scripts', 'load_more_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('load_more_nonce'),
        ));
    }

    // Script de navigation pour les pages "single-photographies"
    if (is_singular('photographies')) {
        wp_register_script('single-photographies-scripts', get_stylesheet_directory_uri() . '/scripts/single-photographies-scripts.js', ['jquery'], false, true);
        wp_enqueue_script('single-photographies-scripts');
        wp_localize_script('single-photographies-scripts', 'photo_navigation_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'post_id'  => get_the_ID(),
            'security' => wp_create_nonce('photo_navigation_nonce'),
        ));
    }
}

function load_more_photos() {
    if (!check_ajax_referer('load_more_nonce', 'security', false)) {
        wp_send_json_error('Nonce invalide.');
        wp_die();
    }

    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $format_id = isset($_POST['format_id']) ? intval($_POST['format_id']) : 0;
    $order = isset($_POST['order']) && in_array($_POST['order'], array('ASC', 'DESC')) ? sanitize_text_field($_POST['order']) : 'DESC';

    // Log les valeurs pour s'assurer que le tri est correctement reçu
    // error_log("Valeur de tri transmise : " . $order);

    $tax_query = array('relation' => 'AND');

    if ($category_id) {
        $tax_query[] = array(
            'taxonomy' => 'evenement',
            'field'    => 'term_id',
            'terms'    => $category_id,
        );
    }

    if ($format_id) {
        $tax_query[] = array(
            'taxonomy' => 'format',
            'field'    => 'term_id',
            'terms'    => $format_id,
        );
    }

    $args = array(
        'post_type'      => 'photographies',
        'posts_per_page' => 8,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'tax_query'      => $tax_query,
        'order'          => $order,
        'orderby'        => 'date',
    );

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

    wp_reset_postdata();
    wp_die();
}

// Action pour récupérer la photo précédente
function get_previous_photo_ajax() {

    // Vérifier le nonce pour la sécurité
    check_ajax_referer( 'photo_navigation_nonce', 'security' );

    // Récupérer l'ID du post actuel depuis la requête AJAX
    $current_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ( $current_post_id == 0 ) {
        wp_send_json_error( 'ID du post actuel non valide.' );
        return;
    }

    // Créer une requête personnalisée pour récupérer le post précédent
    $args = array(
        'post_type'      => 'photographies', // Custom post type
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

    if ( $previous_query->have_posts() ) {
        while ( $previous_query->have_posts() ) {
            $previous_query->the_post();
            $previous_post_id = get_the_ID();
            // Récupérer l'URL du post
            $previous_post_url = get_permalink($previous_post_id);

            // Récupérer l'image attachée
            $attachments = get_attached_media( 'image', $previous_post_id );
            if ( !empty( $attachments ) ) {
                $attachment = array_shift( $attachments );
                $previous_image_url = wp_get_attachment_image_src( $attachment->ID, 'photo-detail-thumb' )[0];
            } else {
                error_log('Aucune image attachée trouvée pour ce post.');
            }
        }
    } else {
        error_log('Aucun post précédent trouvé.');
    }

    wp_reset_postdata(); // Réinitialiser les données de la requête principale

    // Retourner l'URL de l'image
    if ( !empty( $previous_image_url ) ) {
        wp_send_json_success( array( 'image_url' => $previous_image_url, 'post_url' => $previous_post_url ) );
    } else {
        wp_send_json_error( 'Aucune image trouvée pour la photo précédente.' );
    }
}

// Action pour récupérer la photo suivante
function get_next_photo_ajax() {

    // Vérifier le nonce pour la sécurité
    check_ajax_referer( 'photo_navigation_nonce', 'security' );

    // Récupérer l'ID du post actuel depuis la requête AJAX
    $current_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ( $current_post_id == 0 ) {
        wp_send_json_error( 'ID du post actuel non valide.' );
        return;
    }

    // Créer une requête personnalisée pour récupérer le post suivant
    $args = array(
        'post_type'      => 'photographies', // Custom post type
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

    if ( $next_query->have_posts() ) {
        while ( $next_query->have_posts() ) {
            $next_query->the_post();
            $next_post_id = get_the_ID();
            // Récupérer l'URL du post
            $next_post_url = get_permalink($next_post_id);

            // Récupérer l'image attachée
            $attachments = get_attached_media( 'image', $next_post_id );
            if ( !empty( $attachments ) ) {
                $attachment = array_shift( $attachments );
                $next_image_url = wp_get_attachment_image_src( $attachment->ID, 'photo-detail-thumb' )[0];
            } else {
                error_log('Aucune image attachée trouvée pour ce post.');
            }
        }
    } else {
        error_log('Aucun post suivant trouvé.');
    }

    wp_reset_postdata(); // Réinitialiser les données de la requête principale

    // Retourner l'URL de l'image
    if ( !empty( $next_image_url ) ) {
        wp_send_json_success( array( 'image_url' => $next_image_url, 'post_url' => $next_post_url ) );
    } else {
        wp_send_json_error( 'Aucune image trouvée pour la photo suivante.' );
    }
}

function get_photo_data() {
    // Vérification de la requête AJAX
    check_ajax_referer('lightbox_nonce', 'security');

    $photo_id = isset($_POST['photo_id']) ? intval($_POST['photo_id']) : 0;

    if ($photo_id) {
        // Récupérer les données de la photo
        $photo_url = wp_get_attachment_image_url(get_post_thumbnail_id($photo_id), 'photo-detail');
        // Si aucune miniature, on récupère la première image jointe
        if (!$photo_url) {
            $attachments = get_attached_media('image', $photo_id);
            if (!empty($attachments)) {
                $first_attachment = reset($attachments); // Obtenir la première image jointe
                $photo_url = wp_get_attachment_image_url($first_attachment->ID, 'photo-detail');
            }
        }
        $photo_reference = get_field('reference_photo', $photo_id);
        $photo_category_terms = wp_get_post_terms($photo_id, 'evenement');
        $photo_category = (!empty($photo_category_terms) && !is_wp_error($photo_category_terms)) ? $photo_category_terms[0]->name : 'Non classé';

        if ($photo_url) {
            wp_send_json_success([
                'id' => $photo_id,  // Ajout de l'ID de la photo
                'url' => $photo_url,
                'reference' => $photo_reference,
                'category' => $photo_category
            ]);
        } else {
            wp_send_json_error('Image non trouvée.');
        }
    } else {
        wp_send_json_error('Photo non trouvée.');
    }
    wp_die();
}

function get_previous_lightbox_photo_ajax() {

    // Vérifier le nonce pour la sécurité
    check_ajax_referer( 'lightbox_nonce', 'security' );

    // Récupérer l'ID du post actuel depuis la requête AJAX
    $current_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ( $current_post_id == 0 ) {
        wp_send_json_error( 'ID du post actuel non valide.' );
        return;
    }

    // Créer une requête personnalisée pour récupérer le post précédent
    $args = array(
        'post_type'      => 'photographies', // Custom post type
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
        // $image_url = wp_get_attachment_image_url(get_post_thumbnail_id($previous_post_id), 'photo-lightbox');
        $image_url = get_lightbox_image_url(get_post_thumbnail_id($previous_post_id));

        if (!$image_url) {
            $attachments = get_attached_media('image', $previous_post_id);
            if (!empty($attachments)) {
                $attachment = reset($attachments);
                $image_url = get_lightbox_image_url($attachment->ID);
            }
        }

        wp_send_json_success([
            'id'        => $previous_post_id,
            'url'       => $image_url,
            'reference' => get_field('reference_photo', $previous_post_id),
            'category'  => wp_get_post_terms($previous_post_id, 'evenement')[0]->name ?? 'Non classé'
        ]);
    } else {
        wp_send_json_error('Aucune photo précédente trouvée.');
    }

    wp_reset_postdata();
    wp_die();
}

function get_next_lightbox_photo_ajax() {

    // Vérifier le nonce pour la sécurité
    check_ajax_referer( 'lightbox_nonce', 'security' );

    // Récupérer l'ID du post actuel depuis la requête AJAX
    $current_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ( $current_post_id == 0 ) {
        wp_send_json_error( 'ID du post actuel non valide.' );
        return;
    }

    // Créer une requête personnalisée pour récupérer le post précédent
    $args = array(
        'post_type'      => 'photographies', // Custom post type
        'posts_per_page' => 1,               // Un seul post
        'post_status'    => 'publish',       // Post publié
        'orderby'        => 'date',          // Trier par date
        'order'          => 'ASC',          // Ordre décroissant
        'post__not_in'   => array($current_post_id), // Exclure le post actuel
        'date_query'     => array(
            'after' => get_the_date('Y-m-d H:i:s', $current_post_id), // Récupérer les posts avant le post actuel
        ),
    );

    $next_query = new WP_Query($args);

    if ($next_query->have_posts()) {
        $next_query->the_post();
        $next_post_id = get_the_ID();
        // $image_url = wp_get_attachment_image_url(get_post_thumbnail_id($next_post_id), 'photo-lightbox');
        $image_url = get_lightbox_image_url(get_post_thumbnail_id($next_post_id));

        if (!$image_url) {
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

    wp_reset_postdata();
    wp_die();
}

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

/* function get_adjacent_photo_data() {
    check_ajax_referer('lightbox_nonce', 'security');

    $current_photo_id = isset($_POST['photo_id']) ? intval($_POST['photo_id']) : 0;

    // Récupération des ID de la photo précédente et suivante
    $previous_photo = get_previous_post(['post_type' => 'photographies']);
    $next_photo = get_next_post(['post_type' => 'photographies']);

    $response = [];

    // Récupération des données pour la photo précédente
    if ($previous_photo) {
        $response['previous'] = [
            'id' => $previous_photo->ID,
            'url' => wp_get_attachment_image_url(get_post_thumbnail_id($previous_photo->ID), 'photo-detail') ?: '',
            'reference' => get_field('reference_photo', $previous_photo->ID),
            'category' => wp_get_post_terms($previous_photo->ID, 'evenement')[0]->name ?? 'Non classé'
        ];
    }

    // Récupération des données pour la photo suivante
    if ($next_photo) {
        $response['next'] = [
            'id' => $next_photo->ID,
            'url' => wp_get_attachment_image_url(get_post_thumbnail_id($next_photo->ID), 'photo-detail') ?: '',
            'reference' => get_field('reference_photo', $next_photo->ID),
            'category' => wp_get_post_terms($next_photo->ID, 'evenement')[0]->name ?? 'Non classé'
        ];
    }

    if ($response) {
        wp_send_json_success($response);
    } else {
        wp_send_json_error('Aucune photo suivante ou précédente trouvée.');
    }

    wp_die();
} */

add_action('init', 'nathaliemota_init');
add_action ('after_setup_theme', 'nathaliemota_setup');
add_action('wp_enqueue_scripts', 'nathaliemota_register_assets');
add_action( 'wp_ajax_get_previous_photo', 'get_previous_photo_ajax' );
add_action( 'wp_ajax_nopriv_get_previous_photo', 'get_previous_photo_ajax' );
add_action( 'wp_ajax_get_next_photo', 'get_next_photo_ajax' );
add_action( 'wp_ajax_nopriv_get_next_photo', 'get_next_photo_ajax' );
add_action( 'wp_ajax_load_more_photos', 'load_more_photos' );
add_action( 'wp_ajax_nopriv_load_more_photos', 'load_more_photos' );
add_action('wp_ajax_get_photo_data', 'get_photo_data');
add_action('wp_ajax_nopriv_get_photo_data', 'get_photo_data');
// add_action('wp_ajax_get_adjacent_photo_data' , 'get_adjacent_photo_data');
// add_action('wp_ajax_nopriv_get_adjacent_photo_data' , 'get_adjacent_photo_data');
add_action( 'wp_ajax_get_previous_lightbox_photo_ajax', 'get_previous_lightbox_photo_ajax' );
add_action( 'wp_ajax_nopriv_get_previous_lightbox_photo_ajax', 'get_previous_lightbox_photo_ajax' );
add_action( 'wp_ajax_get_next_lightbox_photo_ajax', 'get_next_lightbox_photo_ajax' );
add_action( 'wp_ajax_nopriv_get_next_lightbox_photo_ajax', 'get_next_lightbox_photo_ajax' );
