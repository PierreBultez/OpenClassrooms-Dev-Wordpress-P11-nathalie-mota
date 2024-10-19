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
                            <input type="text" id="name" name="name" autocomplete="name" required>
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" autocomplete="email" required>
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
