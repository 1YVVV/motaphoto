        <nav class ="menu-secondaire">
            <!-- Affichage du menu secondaire -->
            <?php
            wp_nav_menu(array(
                "theme_location" => "menu_secondaire",
                "container" => false,
                "menu_class" => "nav__menu"
            ));
            ?>
        </nav>
        <!-- Ajout de la barre d'outils du tableau de bord (avec wp_head()) -->
        <?php wp_footer() ?>
    </body>
</html>
<!-- Pour que les widgets se positionnent correctement, vous devrez compléter
le code CSS et devrez ajouter des widgets dans la rubrique Apparence - Widgets. -->