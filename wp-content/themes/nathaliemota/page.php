<?php get_header(); ?>

<main id="site-content" role="main">

    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . __( 'Pages:', 'your-theme-textdomain' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

                <?php if ( current_user_can( 'edit_post', get_the_ID() ) ) : ?>
                    <footer class="entry-footer">
                        <span class="edit-link">
                            <a href="<?php echo get_edit_post_link(); ?>">
                                <?php _e( 'Edit', 'your-theme-textdomain' ); ?>
                            </a>
                        </span>
                    </footer>
                <?php endif; ?>

            </article>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
        endwhile;
    endif;
    ?>

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
