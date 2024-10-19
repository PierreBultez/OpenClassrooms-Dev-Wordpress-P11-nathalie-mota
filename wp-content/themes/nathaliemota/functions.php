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
    wp_register_script('nathaliemota-scripts', get_stylesheet_directory_uri() . '/scripts/scripts.js', [], false, true);
    wp_enqueue_script('nathaliemota-scripts');
}

add_action('init', 'nathaliemota_init');
add_action ('after_setup_theme', 'nathaliemota_setup');
add_action('wp_enqueue_scripts', 'nathaliemota_register_assets');
