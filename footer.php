        <nav class ="menu-secondaire">
            <!-- Affichage du menu secondaire -->
            <?php
            wp_nav_menu(array(
                "theme_location" => "menu_secondaire",
                "container" => false,
                "menu_class" => "nav__menu footer-menu"
            ));
            ?>
        </nav>

        <!-- Modale du formulaire de contact -->
        <?php get_template_part("template-parts/formulaire-contact"); ?>

        <!-- Lightbox -->
        <?php get_template_part("template-parts/lightbox"); ?>
        
        <!-- Ajout de la barre d'outils du tableau de bord (avec wp_head()) -->
        <?php wp_footer() ?>
    </body>
</html>