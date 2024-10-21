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
    add_image_size( 'photo-detail', 844, 844, false );
}

function nathaliemota_register_assets () {
    wp_register_style('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_deregister_script('bootstrap');
    wp_register_script('bootstrap' ,'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], false, true);
    wp_enqueue_style('bootstrap');
    wp_enqueue_script('bootstrap');
    wp_register_style('nathaliemota', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('nathaliemota');
    wp_register_style('fontawesome', get_stylesheet_directory_uri() . '/assets/fontawesome/css/fontawesome.css');
    wp_register_style('fontawesome-all', get_stylesheet_directory_uri() . '/assets/fontawesome/css/all.css');
    wp_enqueue_style('fontawesome');
    wp_enqueue_style('fontawesome-all');
    wp_register_script('nathaliemota-scripts', get_stylesheet_directory_uri() . '/scripts/scripts.js', ['jquery'], false, true);
    wp_enqueue_script('nathaliemota-scripts');
    // Ajouter l'ID du post uniquement si on est sur un single du post type 'photographies'
    if ( is_singular('photographies') ) {
        wp_localize_script('nathaliemota-scripts', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('photo_navigation_nonce'),
            'post_id'  => get_the_ID(), // Ajouter l'ID du post actuel uniquement sur les posts du type 'photographies'
        ));
    } else {
        // Si pas sur un single, on localise seulement ajax_url et security
        wp_localize_script('nathaliemota-scripts', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('photo_navigation_nonce'),
        ));
    }
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

    if ( $previous_query->have_posts() ) {
        while ( $previous_query->have_posts() ) {
            $previous_query->the_post();
            $previous_post_id = get_the_ID();

            // Récupérer l'image attachée
            $attachments = get_attached_media( 'image', $previous_post_id );
            if ( !empty( $attachments ) ) {
                $attachment = array_shift( $attachments );
                $previous_image_url = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' )[0];
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
        wp_send_json_success( $previous_image_url );
    } else {
        wp_send_json_error( 'Aucune image trouvée pour la photo précédente.' );
    }
}

wp_localize_script('nathaliemota-scripts', 'ajax_object', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'security' => wp_create_nonce('photo_navigation_nonce'),
    'post_id'  => get_the_ID(), // Ajouter l'ID du post actuel
));

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

    if ( $next_query->have_posts() ) {
        while ( $next_query->have_posts() ) {
            $next_query->the_post();
            $next_post_id = get_the_ID();

            // Récupérer l'image attachée
            $attachments = get_attached_media( 'image', $next_post_id );
            if ( !empty( $attachments ) ) {
                $attachment = array_shift( $attachments );
                $next_image_url = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' )[0];
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
        wp_send_json_success( $next_image_url );
    } else {
        wp_send_json_error( 'Aucune image trouvée pour la photo suivante.' );
    }
}

add_action('init', 'nathaliemota_init');
add_action ('after_setup_theme', 'nathaliemota_setup');
add_action('wp_enqueue_scripts', 'nathaliemota_register_assets');
// Enregistrer les actions AJAX pour les utilisateurs connectés et non connectés
add_action( 'wp_ajax_get_previous_photo', 'get_previous_photo_ajax' );
add_action( 'wp_ajax_nopriv_get_previous_photo', 'get_previous_photo_ajax' );
add_action( 'wp_ajax_get_next_photo', 'get_next_photo_ajax' );
add_action( 'wp_ajax_nopriv_get_next_photo', 'get_next_photo_ajax' );
