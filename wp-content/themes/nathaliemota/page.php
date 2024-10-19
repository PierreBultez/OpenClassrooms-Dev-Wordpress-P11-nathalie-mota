<?php
/**
 * Template Name: Page
 * Description: Template par défaut pour afficher les pages statiques.
 */

get_header(); // Inclut le fichier header.php du thème
?>

<div class="container">
    <div class="content">
        <?php
        // La boucle WordPress : elle vérifie si des pages existent et les affiche.
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h1 class="page-title"><?php the_title(); ?></h1> <!-- Affiche le titre de la page -->
                    <div class="page-content">
                        <?php the_content(); ?> <!-- Affiche le contenu principal de la page -->
                    </div>
                </article>
            <?php
            endwhile;
        else :
            // Si aucune page n'est trouvée, afficher ce message :
            echo '<p>Aucune page trouvée.</p>';
        endif;
        ?>
    </div>
</div>

<?php
get_footer(); // Inclut le fichier footer.php du thème
?>
