<?php
// Récupérer le numéro de la page actuelle
$paged = get_query_var("paged") ? get_query_var("paged") : 1;
// Définir les arguments pour la requête
$args = [
    "post_type" => "photo",
    "posts_per_page" => 8,
    "orderby" => "date",
    "order" => "DESC",
    "paged" => $paged,
];
// Requête WP_Query avec les arguments définis
$photo_query = new WP_Query($args);
?>

<?php
// Récupérer les termes de la taxonomie "Catégorie" et "Format"
$categories = get_terms(['taxonomy' => 'categorie', 'hide_empty' => false]);
$formats = get_terms(['taxonomy' => 'format', 'hide_empty' => false]);

// Récupérer les valeurs des champs personnalisés
$references = get_field_objects(); // Suppose que vous avez des champs ACF
$annees = get_field_objects(); // Exemple pour les années, à remplir dynamiquement
$types = get_field_objects(); // Exemple pour les types, à remplir dynamiquement

// Exemple pour remplir les tableaux $annees et $types
// Vous pouvez remplacer ceci par des requêtes ou des fonctions pour récupérer des valeurs spécifiques
?>

<?php
// Récupérer les valeurs des filtres
$categorie_id = isset($_GET['categorie']) ? intval($_GET['categorie']) : '';
$format_id = isset($_GET['format']) ? intval($_GET['format']) : '';
$tri = isset($_GET['tri']) ? sanitize_text_field($_GET['tri']) : '';
$annee = isset($_GET['annee']) ? sanitize_text_field($_GET['annee']) : '';
$type_photo = isset($_GET['type_photo']) ? sanitize_text_field($_GET['type_photo']) : '';

// Définir les arguments pour la requête
$args = [
    "post_type" => "photo",
    "posts_per_page" => 8,
    "orderby" => "date",
    "order" => "DESC",
    "paged" => $paged,
    "tax_query" => [
        'relation' => 'AND',
    ],
    "meta_query" => [
        'relation' => 'AND', // Permet d'ajouter plusieurs conditions
    ],
];

// Ajouter les filtres de taxonomie
if ($categorie_id) {
    $args['tax_query'][] = [
        'taxonomy' => 'categorie',
        'field'    => 'term_id',
        'terms'    => $categorie_id,
    ];
}
if ($format_id) {
    $args['tax_query'][] = [
        'taxonomy' => 'format',
        'field'    => 'term_id',
        'terms'    => $format_id,
    ];
}

// Ajouter les filtres des champs personnalisés
if ($tri) {
    // Configurez le tri en fonction du champ sélectionné
    $args['meta_key'] = $tri; // Le champ de tri sélectionné
    $args['orderby'] = 'meta_value'; // Tri par valeur méta
}
if ($annee) {
    $args['meta_query'][] = [
        'key'     => 'annee',
        'value'   => $annee,
        'compare' => '='
    ];
}
if ($type_photo) {
    $args['meta_query'][] = [
        'key'     => 'type_photo',
        'value'   => $type_photo,
        'compare' => '='
    ];
}
// Requête WP_Query avec les arguments définis
$photo_query = new WP_Query($args);
?>

<div class="conteneur">

<div class="filtrage">
    <form id="filtrage-form" method="GET" action="">
        <!-- Menus déroulants pour taxonomies -->
        <div class="filtrage__menu">
            <label for="categorie">Catégorie:</label>
            <select name="categorie" id="categorie">
                <option value="">Tous</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?php echo esc_attr($categorie->term_id); ?>"><?php echo esc_html($categorie->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filtrage__menu">
            <label for="format">Format:</label>
            <select name="format" id="format">
                <option value="">Tous</option>
                <?php foreach ($formats as $format): ?>
                    <option value="<?php echo esc_attr($format->term_id); ?>"><?php echo esc_html($format->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Menu déroulant pour le tri -->
        <div class="filtrage__menu">
            <label for="tri">Trier par:</label>
            <select name="tri" id="tri">
                <option value="">Sélectionner...</option>
                <option value="reference">Référence</option>
                <option value="annee">Année</option>
                <option value="type_photo">Type de Photo</option>
            </select>
        </div>

        <!-- Autres filtres -->
        <div class="filtrage__menu">
            <label for="annee">Année:</label>
            <select name="annee" id="annee">
                <option value="">Toutes</option>
                <!-- Remplir avec les années disponibles -->
            </select>
        </div>

        <div class="filtrage__menu">
            <label for="type_photo">Type de Photo:</label>
            <select name="type_photo" id="type_photo">
                <option value="">Tous</option>
                <!-- Remplir avec les types de photos disponibles -->
            </select>
        </div>

        <input type="submit" value="Filtrer">
    </form>
</div>

    <section class="galerie">
        <?php
        // S'il y a des publications correspondant à la requête
        if ($photo_query->have_posts()) {
            // Boucle à travers les posts trouvés
            while ($photo_query->have_posts()) {
                // Pointe le premier post lors du premier appel puis se déplace au suivant
                // utilisée dans les thèmes WordPress pour itérer à travers les résultats d'une requête personnalisée
                $photo_query->the_post();
                echo "<figure class='galerie__item'>";
                if (has_post_thumbnail()) {
                    // Affiche l'image mise en avant avec une taille personnalisée
                    the_post_thumbnail("catalogue", ["class" => "galerie__item--img"]);
                }
                else {
                    echo "<p>Aucune image mise en avant pour cette publication</p>";
                }
                echo "</figure>";
            }
            // Réinitialise les données du post
            wp_reset_postdata(); 
        }
        else {
            echo "<p>Aucune publication trouvée<p>";
        }
        ?>
    </section>
</div>