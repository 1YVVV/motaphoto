<?php
// Ajout du type module aux scripts spécifiques
function add_script_type_module($tag, $handle, $src) {
    if ('overlay' === $handle || 'filtres' === $handle) {
        $tag = '<script src="' . esc_url($src) . '" type="module"></script>';
    }
    return $tag;
}
add_filter('script_loader_tag', 'add_script_type_module', 10, 3);

// Chargement des styles et scripts
function theme_enqueue_styles() {
	// Chargement des styles principaux
    wp_enqueue_style("mota-style", get_theme_file_uri("style.css"));
    // Chargement des scripts du formulaire de contact
    wp_enqueue_script("menu-burger", get_theme_file_uri("js/burger.js"), [], null, true);
    // Chargement des scripts du formulaire de contact
    wp_enqueue_script("formulaire-contact", get_theme_file_uri("js/formulaire-contact.js"), ["jquery"], 1.0, true);
    // Chargement des scripts de l'overlay
    wp_enqueue_script("overlay", get_theme_file_uri("js/overlay.js"), ["jquery"], 1.0, true);
    // Chargement des scripts de la lightbox
    wp_enqueue_script("lightbox", get_theme_file_uri("js/lightbox.js"), ["jquery"], 1.0, true);
    // Chargement des scripts destinés aux filtres de la page d'accueil
    wp_enqueue_script("filtres", get_theme_file_uri("js/filtres.js"), ["jquery", "overlay"], 1.0, true);
    // Chargement des scripts d'affichage des miniatures de la single page
    wp_enqueue_script("single-miniature", get_theme_file_uri("js/single-miniature.js"), ["jquery"], 1.0, true);
    // Localisation des scripts pour AJAX
    wp_localize_script("filtres", "my_ajax_object", array(
        "ajax_url" => admin_url("admin-ajax.php"),
        "nonce" => wp_create_nonce("my_ajax_nonce")
    ));
    // wp_localize_script('overlay', 'my_ajax_object', array(
    //     'ajax_url' => admin_url('admin-ajax.php'),
    //     'nonce' => wp_create_nonce('my_ajax_nonce')
    // ));
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
    add_image_size("catalogue", 564, 495, true);
    add_image_size("catalogue-mobile", 317, 278, true);
    add_image_size("catalogue-miniature", 80, 70, true);
}
add_action("after_setup_theme", "fonctionnalites_theme");

// Enregistrement des menus de navigation
function enregistrer_menus() {
    register_nav_menus( array(
        "menu_principal" => __("Menu principal"),
        "menu_secondaire" => __("Menu secondaire"),
    ) );
}
add_action("init", "enregistrer_menus");

// Ajout de la classe ouverture-modale au lien de contact
function ajout_classe_ouverture_modale($atts, $item, $args) {
    // Vérifiez si le titre de l'élément du menu est "Contact"
    if ($item->title === "CONTACT") {
        // Ajoutez la classe "ouverture-modale" au lien
        $atts['class'] = "ouverture-modale";
    }
    return $atts;
}
add_filter("nav_menu_link_attributes", "ajout_classe_ouverture_modale", 10, 3);

// Ajout d'une photo de hero personnalisable
function photo_hero($wp_customize) {
    // Ajouter une section pour le hero
    $wp_customize->add_section("section_hero", array(
        "title"       => __("Section Hero", "mota"),
        "description" => __("Modifier la photo du hero et le texte", "mota"),
    ));
    // Ajouter un réglage pour la photo
    $wp_customize->add_setting( "image_hero", array(
        "default"           => "",
        "transport"         => "refresh",
        "sanitize_callback" => "esc_url_raw", // Utiliser esc_url_raw pour l'URL de l'image
    ));
    // Ajouter un contrôle pour la photo
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, "controle_image_hero", array(
        "label"    => __("Image Hero", "mota"),
        "section"  => "section_hero",
        "settings" => "image_hero",
    )));
}
add_action("customize_register", "photo_hero");

// Seuil de taille des images téléversées
function seuil_max_taille_photos() {
    return 1920; // ou toute autre valeur en pixels
}
add_filter("big_image_size_threshold", "seuil_max_taille_photos");

// Les menus de navigation doivent être enregistrés à l'aide du hook init.
// Le hook init est appelé après que WordPress a terminé le chargement des fichiers de configuration
// et des options du thème, mais avant que le thème ne commence à exécuter des actions plus spécifiques.
// Pourquoi utiliser init pour les Menus
// Timing : init est le moment approprié pour enregistrer des éléments comme des menus,
// des taxonomies, des post types personnalisés, etc., car ces éléments doivent être disponibles
// avant que WordPress commence à exécuter d'autres actions qui pourraient en dépendre.
// Ordre des Actions : after_setup_theme est utilisé pour configurer le thème,
// tandis que init est utilisé pour enregistrer les éléments qui seront utilisés
// tout au long du cycle de vie de WordPress.

// Filtrage des photos
function load_filtered_photos() {
    // Vérifiez la nonce pour les requêtes AJAX
    check_ajax_referer("my_ajax_nonce", "nonce");
    
    // Initialiser la requête de taxonomie
    $tax_query = array("relation" => "AND");

    // Ajoutez les taxonomies sélectionnées à la requête
    if (isset($_POST["categories"]) && !empty($_POST["categories"])) {
        // $categories = array_map("sanitize_text_field", $_POST["categories"]);
        $tax_query[] = array(
            "taxonomy" => "categ",
            "field"    => "slug",
            "terms"    => $_POST["categories"],
            "operator" => "IN"
        );
    }
    if (isset($_POST["formats"]) && !empty($_POST["formats"])) {
        // $formats = array_map("sanitize_text_field", $_POST["formats"]);
        $tax_query[] = array(
            "taxonomy" => "format",
            "field"    => "slug",
            "terms"    => $_POST["formats"],
            "operator" => "IN"
        );
    }

    // Préparer les arguments pour WP_Query
    $args = array(
        "post_type" => "photo",
        "posts_per_page" => 8,
        "paged" => isset($_POST["page"]) ? intval($_POST["page"]) : 1,
    );

    // Ajouter la tax_query uniquement si elle n'est pas vide
    if (!empty($tax_query)) {
        $args["tax_query"] = $tax_query;
    }

    // On ajoute le tri par année si sélectionné dans le tri sinon on affiche par date de publication décroissante
    if (isset($_POST["tri_annee"]) && in_array($_POST["tri_annee"], array("ASC", "DESC"))) {
        $args["meta_key"] = "annee"; // Récupération du nom du champ ACF pour l'année
        $args["orderby"] = array(
            'meta_value_num' => $_POST["tri_annee"],
            'date' => 'DESC'
        );
    } else {
        $args["orderby"] = "date"; // Tri par défaut par date de publication
        $args["order"] = "DESC"; // Ordre décroissant par défaut
    }

    // Exécuter la requête
    $query = new WP_Query($args);

    // Générer le HTML des résultats
    if ($query->have_posts()) {
        $html = "";
        while ($query->have_posts()) {
            $query->the_post();
            $annee = get_field("annee");
            $titre = get_the_title(); // Récupérer le titre
            $categories = wp_get_post_terms(get_the_ID(), 'categ'); // Récupérer les catégories
            $categorie = !empty($categories) ? $categories[0]->name : 'Catégorie par défaut'; // Utiliser la première catégorie ou une valeur par défaut    
            $photo_id = get_the_ID(); // ID de la photo

            $html .= "<figure class='conteneur-photo'>";
            if (has_post_thumbnail()) {
                $html .= get_the_post_thumbnail($photo_id, "catalogue", ["class" => "conteneur-photo__photo"]);
                // Méthode efficace pour inclure dynamiquement du HTML généré 
                // par des fichiers PHP dans votre réponse AJAX
                // Capturez le contenu du template part
                ob_start();
                // Inclure le template part avec des variables locales
                include locate_template('template-parts/overlay-photo.php');
                $html .= ob_get_clean();
            } else {
                $html .= "<p>Aucune image mise en avant pour cette publication</p>";
            }
            // Affiche l'année pour vérifier le tri
            // $html .= esc_html($annee);
            $html .= "</figure>";
        }
        wp_send_json_success($html);
    } else {
        wp_send_json_error(esc_html__("Aucune photo trouvée"));
    }

    wp_die();
}
add_action("wp_ajax_load_filtered_photos", "load_filtered_photos");
add_action("wp_ajax_nopriv_load_filtered_photos", "load_filtered_photos");

