<?php
// Récupérer le numéro de la page actuelle
$page = get_query_var("paged") ? get_query_var("paged") : 1;
// Récupérer toutes les taxonomies associées au type de publication photo
$taxonomies = get_object_taxonomies("photo");
// Puis exclure la taxonomie post_format
$taxonomies = array_diff($taxonomies, array("post_format"));
// Récupérer les valeurs des champs personnalisés
// $annees = get_field_objects(); // Exemple pour les années, à remplir dynamiquement

// Définir les arguments pour la requête sur les photos
$args = [
    "post_type" => "photo",
    "posts_per_page" => 8,
    "orderby" => "date",
    "order" => "DESC",
    "paged" => $page,
];
// Requête sur les photos avec les arguments définis
$photo_query = new WP_Query($args);
?>

<div class="conteneur">
    <div class="filtres">
        <div class="filtres__taxonomie">
            <!-- Menu déroulant pour les taxonomies -->
            <?php
            foreach ($taxonomies as $taxonomie) {
                // Récupérer le label de la taxonomie
                $label_taxonomie = get_taxonomy($taxonomie)->labels->name;
                ?>
                <div class="select-filtre__style">
                    <div class="select-filtre" data-taxonomie="<?php echo esc_attr($taxonomie); ?>">
                        <div class="select-filtre__option">
                            <!-- Stocke le label par défaut dans un attribut data -->
                            <span data-label="<?php echo esc_html($label_taxonomie); ?>"><?php echo esc_html($label_taxonomie); ?></span>
                            <div class="fleche"></div>
                        </div>
                        <div class="select-filtre__liste-options">
                            <!-- Option de réinitialisation -->
                            <span class="choix-option option-vide" data-value=""></span>
                            <?php
                            foreach (get_terms($taxonomie) as $terme) {
                                ?>
                                <span class="choix-option" data-value="<?php echo esc_attr($terme->slug); ?>"><?php echo esc_html($terme->name); ?></span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="filtres__tri">
            <div class="select-filtre__style">
                <div class="select-filtre" id="tri-annee">
                    <div class="select-filtre__option">
                        <span data-label="Trier par">Trier par</span>
                        <div class="fleche"></div>
                    </div>
                    <div class="select-filtre__liste-options">
                        <!-- Option de réinitialisation -->
                        <span class="choix-option option-vide" data-value=""></span>
                        <span class="choix-option" data-value="ASC">Année croissante</span>
                        <span class="choix-option" data-value="DESC">Année décroissante</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="galerie-photos">
        <?php
        // S'il y a des publications correspondant à la requête
        if ($photo_query->have_posts()) {
            // Boucle à travers les posts trouvés
            while ($photo_query->have_posts()) {
                // Pointe le premier post lors du premier appel puis se déplace au suivant
                // utilisée dans les thèmes WordPress pour itérer à travers les résultats d'une requête personnalisée
                $photo_query->the_post();
                // Définir les variables à passer au template part
                $titre = get_the_title();
                $categories = wp_get_post_terms(get_the_ID(), 'categ');
                $categorie = !empty($categories) ? $categories[0]->name : 'Catégorie par défaut';
                $photo_id = get_the_ID();
                echo "<figure class='conteneur-photo'>";
                if (has_post_thumbnail()) {
                    // Affiche l'image mise en avant avec une taille personnalisée
                    the_post_thumbnail("catalogue", ["class" => "conteneur-photo__img"]);
                    // Inclure le template part avec des variables locales
                    include locate_template('template-parts/overlay-photo.php');
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

    <div id="bouton-pagination">
        <button id="charger-plus" class="charger-plus">Charger plus</button>
    </div>
</div>
