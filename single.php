<?php get_header();

// Récupération du nom de la photo dans l'URL
$nom_photo = get_query_var("photo");

// Construction des critères de recherche
$args = [
    "post_type" => "photo",
    "name" => $nom_photo,
    "posts_per_page" => 1,
];

// Requête pour récupérer les infos de la photo correspondante
$requete_infos = new WP_Query($args);

if ($requete_infos->have_posts()) {
    while ($requete_infos->have_posts()) {
        // Pointe le premier post lors du premier appel puis se déplace au suivant
        // utilisée pour itérer à travers les résultats d'une requête personnalisée
        $requete_infos->the_post();
        // Récupération des champs personnalisés
        $annee = get_field("annee");
        $reference = get_field("reference");
        $type = get_field("type_photo");
        // Récupération des taxonomies
        $categories = wp_get_post_terms(get_the_ID(), "categ");
        $formats = wp_get_post_terms(get_the_ID(), "format");
?>

<div class="conteneur">
    <section class="single-haut">
        <!-- Partie gauche information -->
        <article class="single-haut__info">
            <h1><?php echo the_title();?></h1>
            <p id="reference-photo" data-reference="<?php echo esc_html($reference); ?>">RÉFÉRENCE : <?php echo esc_html($reference); ?></p>
            <p>CATÉGORIE : 
                <?php
                if (is_array($categories)) {
                    foreach ($categories as $categorie) {
                        // Condition pour prévenir les erreurs,
                        // plus sécurisé et robuste avec le contrôle du type et l'existence de données 
                        if (is_object($categorie) && property_exists($categorie, "name")) {
                            echo esc_html($categorie->name);
                        }
                    }
                }
                else {
                    echo "Pas de catégories disponibles";
                }
                ?>
            </p>
            <p>FORMAT : 
                <?php
                if (is_array($formats)) {
                    foreach ($formats as $format) {
                        if (is_object($format) && property_exists($format, "name")) {
                            echo esc_html($format->name);
                        }
                    }
                }
                else {
                    echo "Pas de formats disponibles";
                }
                ?>
            </p>
            <p>TYPE : <?php echo esc_html($type); ?></p>
            <p class="annee">ANNÉE : <?php echo esc_html($annee); ?></p>
        </article>
        <!-- Partie droite photo -->
        <figure class="single-haut__photo">
            <?php the_post_thumbnail("large", ["class" => "single-haut__photo--grande"]); ?>
        </figure>
    </section>
    
    <!-- Gestion des miniatures -->
    <?php
    $current_id = get_the_ID(); // ID de la photo actuelle

    // Récupération de la photo suivante
    $next_post = get_posts(array(
        "post_type" => "photo", // Type de publication
        "posts_per_page" => 1,
        "meta_key" => "annee", // Champ personnalisé pour l'année
        "orderby" => array(
            "meta_value_num" => "ASC", // Tri par la valeur numérique du champ 'annee'
            "date" => "ASC" // Puis tri par la date de publication
        ),
        "date_query" => array(
            array("after" => get_the_date("Y-m-d H:i:s", $current_id))
        ),
    ));
    $next_url = $next_post ? get_permalink($next_post[0]->ID) : "#";
    $next_thumbnail = $next_post ? get_the_post_thumbnail_url($next_post[0]->ID, "catalogue-miniature") : "";

    // Récupération de la photo précédente
    $prev_post = get_posts(array(
        "post_type" => "photo",
        "posts_per_page" => 1,
        "meta_key" => "annee",
        "orderby" => array(
            "meta_value_num" => "DESC", // Tri par la valeur numérique du champ 'annee'
            "date" => "DESC" // Puis tri par la date de publication
        ),
        "date_query" => array(
            array("before" => get_the_date("Y-m-d H:i:s", $current_id))
        ),
    ));
    $prev_url = $prev_post ? get_permalink($prev_post[0]->ID) : "#";
    $prev_thumbnail = $prev_post ? get_the_post_thumbnail_url($prev_post[0]->ID, "catalogue-miniature") : "";
    ?>

    <section class="single-milieu">
        <!-- Partie gauche contact -->
        <article class="single-milieu__contact">
            <p>Cette photo vous intéresse ?</p>
            <button class="ouverture-modale">Contact</button>
        </article>
        <!-- Partie droite photo -->
        <div class="single-milieu__photo">
            <div class="single-milieu__photo--vignette">
                <figure class="miniature">
                    <img src="<?php echo esc_url(get_the_post_thumbnail_url($current_id, 'catalogue-miniature')); ?>" alt="Miniature actuelle">
                </figure>
                <div class="fleches">
                    <a href="<?php echo esc_url($prev_url); ?>" class="fleche" data-direction="precedente" data-apercu-url="<?php echo esc_url($prev_thumbnail); ?>">
                        <img src="<?php echo get_theme_file_uri("assets/images/icones/fleche-gauche.svg"); ?>" alt="Flèche gauche">
                    </a>
                    <a href="<?php echo esc_url($next_url); ?>" class="fleche" data-direction="suivante" data-apercu-url="<?php echo esc_url($next_thumbnail); ?>">
                        <img src="<?php echo get_theme_file_uri("assets/images/icones/fleche-droite.svg"); ?>" alt="Flèche droite">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="single-bas">
        <h2>VOUS AIMEREZ AUSSI</h2>
		<div id="galerie-photos">
			<?php
			// Vérifie si $categories est un tableau (donc plusieurs données à traiter)
			if (is_array($categories)) {
				// Extrait les ID des catégories de la photo actuelle
				$identifiants_categories = wp_list_pluck($categories, "term_id");
				
				// Arguments pour récupérer 2 photos aléatoires de la même catégorie
				$arguments = [
					"post_type" => "photo", // Type de post à récupérer (dans ce cas, photo)
					"posts_per_page" => 2, // Nombre de posts à afficher (ici, 2)
					"orderby" => "rand", // Tri aléatoire des posts
					// Filtrer les photos par catégorie
					"tax_query" => array(
						array(
							"taxonomy" => "categ", // Taxonomie à utiliser (ici, categ)
							"field" => "term_id", // Champ à utiliser pour la taxonomie (ici, term_id)
							"terms" => $identifiants_categories, // Identifiants des termes à filtrer (les catégories de la photo actuelle)
						),
					),
					"post__not_in" => array(get_the_ID()), // Exclut la photo actuelle de la requête
				];
				
				// Requête WP_Query avec les arguments définis
				$requete_photos = new WP_Query($arguments);
				
				// S'il y a des publications correspondant à la requête
				if ($requete_photos->have_posts()) {
					// Boucle à travers les posts trouvés
					while ($requete_photos->have_posts()) {
						// Pointe le premier post lors du premier appel puis se déplace au suivant
            			// utilisée dans les thèmes WordPress pour itérer à travers les résultats d'une requête personnalisée
						$requete_photos->the_post();
                        // Définir les variables à passer au template part
                        $titre = get_the_title();
                        $categories = wp_get_post_terms(get_the_ID(), "categ");
                        $categorie = !empty($categories) ? $categories[0]->name : "Catégorie par défaut";
                        $photo_id = get_the_ID();
						// Affiche les photos trouvées
						echo "<figure class='conteneur-photo'>";
						the_post_thumbnail("catalogue", ["class" => "conteneur-photo__photo"]); 
						include locate_template("template-parts/overlay-photo.php");
                        echo "</figure>";
					}
					// Réinitialise les données post après la boucle personnalisée
					wp_reset_postdata();
				}
				else {
					// Message affiché si aucune photo n'est trouvée
					echo "<p>Aucune autre photo trouvée dans cette catégorie</p>";
				}
			}
			else {
				// Message affiché si aucune catégorie n'est disponible
				echo "<p>Aucune publication trouvée</p>";
			}
			?>
		</div>
    </section>
</div>

<?php
    } // Fin de la boucle while pour les posts
    wp_reset_postdata();
} // Fin de la condition if pour have_posts()
?>

<?php get_footer() ?>
