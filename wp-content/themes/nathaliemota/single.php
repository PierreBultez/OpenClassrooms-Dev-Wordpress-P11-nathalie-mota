<?php
/**
 * Template Name: Single Post
 * Description: Template par défaut pour afficher un article de blog (ou tout type de publication).
 */

get_header(); // Inclut le fichier header.php du thème
?>

<div class="container">
    <div class="content">
        <?php
        // La boucle WordPress pour les articles
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h1 class="post-title"><?php the_title(); ?></h1> <!-- Titre de l'article -->

                    <div class="post-meta">
                        <p>Publié le <?php the_date(); ?> par <?php the_author(); ?></p> <!-- Date et auteur -->
                        <p>Catégorie : <?php the_category(', '); ?> <!-- Liste des catégories -->
                    </div>

                    <div class="post-content">
                        <?php the_content(); ?> <!-- Contenu principal de l'article -->
                    </div>

                    <div class="post-tags">
                        <?php the_tags('<p>Tags : ', ', ', '</p>'); ?> <!-- Affichage des tags -->
                    </div>

                    <div class="post-navigation">
                        <div class="previous-post">
                            <?php previous_post_link('%link', 'Article précédent : %title'); ?>
                        </div>
                        <div class="next-post">
                            <?php next_post_link('%link', 'Article suivant : %title'); ?>
                        </div>
                    </div>

                </article>
            <?php
            endwhile;
        else :
            echo '<p>Aucun article trouvé.</p>';
        endif;
        ?>
    </div>

    <div class="sidebar">
        <?php get_sidebar(); ?> <!-- Inclut le fichier sidebar.php, s'il existe -->
    </div>
</div>

<?php
get_footer(); // Inclut le fichier footer.php du thème
?>
