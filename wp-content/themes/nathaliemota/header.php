<!DOCTYPE html>
<html lang=fr>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?php echo get_dynamic_meta_description(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body>
<header>
    <?php if (has_custom_logo()) : ?>
        <div class="logo">
            <?php the_custom_logo(); ?>
        </div>
    <?php endif; ?>
    <button class="burger-menu" aria-label="Menu" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </button>
    <?php wp_nav_menu( array(
        'theme_location' => 'main_menu',
        'menu_class' => 'menu-entry',
        'container_class' => 'menu',
        ) );
    ?>
</header>
