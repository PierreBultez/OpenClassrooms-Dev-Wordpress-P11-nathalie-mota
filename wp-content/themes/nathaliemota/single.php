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
                    <div class="entry-meta">
                        <span class="posted-on"><?php echo get_the_date(); ?></span>
                        <span class="byline"> by <?php the_author(); ?></span>
                    </div>
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

                <footer class="entry-footer">
                    <?php the_category(); ?>
                    <?php the_tags(); ?>
                </footer>

            </article>

            <nav class="post-navigation">
                <div class="nav-previous"><?php previous_post_link( '%link', 'Previous Post: %title' ); ?></div>
                <div class="nav-next"><?php next_post_link( '%link', 'Next Post: %title' ); ?></div>
            </nav>

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
