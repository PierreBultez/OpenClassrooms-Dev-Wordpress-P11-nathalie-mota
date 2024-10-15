<?php

if ( ! function_exists( 'nathaliemota_setup' ) ) {
    function nathaliemota_setup () {
        add_theme_support('title-tag');
        add_theme_support('menus');
    }
add_action ('after_setup_theme', 'nathaliemota_setup');

?>