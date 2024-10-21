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
    // Localiser le script pour passer l'URL d'admin AJAX et le nonce de sécurité
    wp_localize_script('nathaliemota-scripts', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),  // URL pour les requêtes AJAX
        'security' => wp_create_nonce('photo_navigation_nonce') // Nonce de sécurité
    ));
}

// Action pour récupérer la photo précédente
function get_previous_photo_ajax() {
    // Vérifier le nonce pour la sécurité
    check_ajax_referer( 'photo_navigation_nonce', 'security' );

    $previous_post = get_previous_post( true, '', '' );
    $previous_image_url = '';
        error_log($previous_post);
    if ( $previous_post ) {
        error_log('ID du post précédent : ' . $previous_post->ID); // Log l'ID de la photo précédente
        $attachments = get_attached_media( 'image', $previous_post->ID );
        if ( !empty( $attachments ) ) {
            $attachment = array_shift( $attachments );
            error_log('Image ID trouvée : ' . $attachment->ID); // Log l'ID de l'image trouvée
            $previous_image_url = wp_get_attachment_image_src( $attachment->ID, 'photo-detail' )[0];
        }
    }
    else {
        error_log('Pas de previous post');
    }

    // Retourner l'URL de l'image
    wp_send_json_success( $previous_image_url );
}

// Action pour récupérer la photo suivante
function get_next_photo_ajax() {
    // Vérifier le nonce pour la sécurité
    check_ajax_referer( 'photo_navigation_nonce', 'security' );

    $next_post = get_next_post( true, '', 'evenement' );
    $next_image_url = '';

    if ( $next_post ) {
        $attachments = get_attached_media( 'image', $next_post->ID );
        if ( !empty( $attachments ) ) {
            $attachment = array_shift( $attachments );
            $next_image_url = wp_get_attachment_image_src( $attachment->ID, 'photo-detail' )[0];
        }
    }

    // Retourner l'URL de l'image
    wp_send_json_success( $next_image_url );
}

add_action('init', 'nathaliemota_init');
add_action ('after_setup_theme', 'nathaliemota_setup');
add_action('wp_enqueue_scripts', 'nathaliemota_register_assets');
// Enregistrer les actions AJAX pour les utilisateurs connectés et non connectés
add_action( 'wp_ajax_get_previous_photo', 'get_previous_photo_ajax' );
add_action( 'wp_ajax_nopriv_get_previous_photo', 'get_previous_photo_ajax' );
add_action( 'wp_ajax_get_next_photo', 'get_next_photo_ajax' );
add_action( 'wp_ajax_nopriv_get_next_photo', 'get_next_photo_ajax' );
