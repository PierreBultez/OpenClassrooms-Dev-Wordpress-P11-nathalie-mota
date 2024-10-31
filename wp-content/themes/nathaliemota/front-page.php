<?php get_header(); ?>
<div class="main">

    <div class="hero">
        <?php
        $random_hero_image = get_random_hero_image();
        if ($random_hero_image) {
            echo '<img src="' . esc_url($random_hero_image) . '" alt="">';
        } else {
            echo '<img src="' . get_stylesheet_directory_uri() . '/assets/images/nathalie-6.webp" alt="Photo d\'une soirée dansante montrant un couple enlacé">';
        }
        ?>
        <h1 class="hero-title">Photographe event</h1>
    </div>

    <?php get_template_part('template-parts/images-bloc'); ?>

</div>
<?php get_footer(); ?>
