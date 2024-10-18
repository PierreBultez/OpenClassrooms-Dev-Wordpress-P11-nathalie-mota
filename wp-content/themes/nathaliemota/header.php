<!DOCTYPE html>
<html lang=fr>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body>
<header>
    <div class="logo">
        <img src="<?php echo get_stylesheet_directory_uri() . '/assets/logo/nathalie_mota_logo.png'; ?>" alt="Logo Nathalie Mota">
    </div>
    <button class="burger-menu" aria-label="Menu" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </button>
    <div class="menu">
        <ul class="menu-entry">
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><h3>Accueil</h3></a></li>
            <li><a href="#"><h3>À propos</h3></a></li>
            <li><a href="#contact"><h3>Contact</h3></a></li>
        </ul>
    </div>
    <!-- Modale de contact -->
    <div class="modal-overlay" id="contactModal">
        <div class="modal-content">
            <div class="modal-body">
                <div class="contact">
                    <img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/contact.png'; ?>" alt="Mot contact répété en boucle">
                </div>
                <div class="form-container">
                    <form action="#" method="post">
                        <div class="column-container">
                            <div class="col-1">
                                <label for="name">Nom</label>
                                <input type="text" id="name" name="name" required>

                                <label for="email">E-mail</label>
                                <input type="email" id="email" name="email" required>

                                <label for="photo-ref">Réf. Photo</label>
                                <input type="text" id="photo-ref" name="photo-ref">
                            </div>
                            <div class="col-2">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="5" required></textarea>

                                <button type="submit" class="btn-submit">Envoyer</button>
                            </div>
                        </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
