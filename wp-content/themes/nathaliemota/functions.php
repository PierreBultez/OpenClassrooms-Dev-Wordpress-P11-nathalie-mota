<?php

function nathaliemota_setup () {
    add_theme_support('title-tag');
    add_theme_support('menus');
}

function nathaliemota_register_assets () {
    wp_register_style('nathaliemota', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('nathaliemota');
    wp_register_style('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_deregister_script('bootstrap');
    wp_register_script('bootstrap' ,'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], false, true);
    wp_enqueue_style('bootstrap');
    wp_enqueue_script('bootstrap');
}

add_action ('after_setup_theme', 'nathaliemota_setup');
add_action('wp_enqueue_scripts', 'nathaliemota_register_assets');

?>