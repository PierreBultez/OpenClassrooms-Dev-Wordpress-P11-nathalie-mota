<footer>
    <?php wp_nav_menu( array(
        'theme_location' => 'footer_menu',
        'menu_class' => 'footer-links',
        'container' => 'null',
    ) );
    ?>
    <?php get_template_part( 'template-parts/contact-modal' ); ?>
    <?php get_template_part( 'template-parts/contact-modal-photo' ); ?>
    <?php wp_footer(); ?>
</footer>
</body>
</html>
