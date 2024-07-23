<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo("name") ?> - <?php bloginfo("description") ?></title>
    <!-- Ajout de la barre d'outils du tableau de bord (avec wp_footer()) -->
    <?php wp_head(); ?>
    <!-- La fonction wp_head() permet également de lier des scripts 
    et autres feuilles de style via le fichier functions.php. -->
</head>
<body>
    <header>        
        <nav class="menu-principal">
            <!-- Fonction d'ajout d'un logo dans WP -->
            <div class="nav__logo">
                <?php the_custom_logo() ?>
            </div>
            <!-- Affichage du menu principal -->
            <?php
            wp_nav_menu(array(
                "theme_location" => "menu_principal",
                "container" => "false",
                "menu_class" => "nav__menu"
            ))
            ?>
        </nav>
    </header>
    