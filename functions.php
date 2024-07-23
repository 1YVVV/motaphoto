<?php
// Chargement des styles et scripts
function theme_enqueue_styles() {
	// Chargement du style principal
    wp_enqueue_style("mota-style", get_theme_file_uri("style.css"));
    // Chargement de Font Awesome
    // wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), null);
}
add_action("wp_enqueue_scripts", "theme_enqueue_styles");

// Support du logo personnalisé
function fonctionnalites_theme() {
    // Support du logo personnalisé
    add_theme_support("custom-logo");

    // Activation des images mises en avant
    add_theme_support("post-thumbnails");
    // set_post_thumbnail_size( 258, 145, true );

    // Tailles personnalisées des photos du catalogue
    add_image_size("catalogue", 564, 465, true);
    add_image_size("catalogue-mobile", 317, 278, true);
    add_image_size("catalogue-miniature", 80, 70, true);
}
add_action("after_setup_theme", "fonctionnalites_theme");

// Enregistrement des menus de navigation
function enregistrer_menus() {
    register_nav_menus( array(
        "menu_principal" => __( "Menu principal" ),
        "menu_secondaire" => __( "Menu secondaire" ),
    ) );
}
add_action( "init", "enregistrer_menus" );

// Seuil de taille des images téléversées
function seuil_max_taille_images() {
    return 1920; // ou toute autre valeur en pixels
}
add_filter("big_image_size_threshold", "seuil_max_taille_images");


// Oui, les menus de navigation doivent être enregistrés à l'aide du hook init.
// Le hook init est appelé après que WordPress a terminé le chargement des fichiers de configuration
// et des options du thème, mais avant que le thème ne commence à exécuter des actions plus spécifiques.
// Pourquoi utiliser init pour les Menus
// Timing : init est le moment approprié pour enregistrer des éléments comme des menus,
// des taxonomies, des post types personnalisés, etc., car ces éléments doivent être disponibles
// avant que WordPress commence à exécuter d'autres actions qui pourraient en dépendre.
// Ordre des Actions : after_setup_theme est utilisé pour configurer le thème,
// tandis que init est utilisé pour enregistrer les éléments qui seront utilisés
// tout au long du cycle de vie de WordPress.