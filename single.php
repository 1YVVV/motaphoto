<?php get_header() ?>

<!-- <main>
	<h2><?php the_title() ?></h2>
	<?php the_content() ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à la page d'accueil</a>
</main> -->
<?php
// get_header();

// Récupération du nom de la photo dans l'URL
$slug = get_query_var("photo");

// Construction des critères de recherche
$args = [
    "post_type" => "photo",
    "name" => $slug,
    "posts_per_page" => 1,
];

// Requête pour récupérer les infos de la photo correspondante
$requete_infos = new WP_Query($args);

if ($requete_infos->have_posts()) {
    while ($requete_infos->have_posts()) {
        $requete_infos->the_post();
        $annee = get_field("annee");
        $reference = get_field("reference");
        $type = get_field("type_photo");
        $categories = wp_get_post_terms(get_the_ID(), "categ");
        $formats = wp_get_post_terms(get_the_ID(), "format");
?>

<div class="conteneur">
    <section class="single-haut">
        <!-- Partie gauche information -->
        <article class="single-haut__info">
            <h1><?php echo the_title();?></h1>
            <p>RÉFÉRENCE : <?php echo esc_html($reference);?></p>
            <p>CATÉGORIE : 
                <?php
                if (is_array($categories)) {
                    foreach ($categories as $categorie) {
                        // Condition pour prévenir les erreurs,
                        // plus sécurisé et robuste avec le contrôle du type et l'existence de données 
                        if (is_object($categorie) && property_exists($categorie, 'name')) {
                            echo esc_html($categorie->name);
                        }
                    }
                } else {
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
                } else {
                    echo "Pas de formats disponibles";
                }
                ?>
            </p>
            <p>TYPE : <?php echo esc_html($type);?></p>
            <p class="annee">ANNÉE : <?php echo esc_html($annee);?> </p>
        </article>
        <!-- Partie droite photo -->
        <figure class="single-haut__photo">
            <?php the_post_thumbnail("large", ["class" => "single-haut__photo--grande"]); ?>
        </figure>
    </section>
    
    <section class="single-milieu">
        <!-- Partie gauche Contact -->
        <article class="single-milieu__contact">
            <p>Cette photo vous intéresse ?</p>
            <button>Contact</button>
        </article>
        <!-- Partie droite photo -->
        <div class="single-milieu__photo">
            <div class="single-milieu__photo--vignette">
                <figure class="miniature">
                    <!-- <?php //the_post_thumbnail("medium", ["class" => "single-bas__photo--miniature"]); ?> -->
                    <?php the_post_thumbnail("catalogue-miniature"); ?>
                </figure>
                <div class="fleches">
                    <img src="<?php echo get_theme_file_uri("assets/images/icones/fleche-gauche.svg"); ?>" alt="Flèche gauche">
                    <img src="<?php echo get_theme_file_uri("assets/images/icones/fleche-droite.svg"); ?>" alt="Flèche droite">
                </div>
            </div>
        </div>    
    </section>

    <section class="single-bas">
        <h2>VOUS AIMEREZ AUSSI</h2>
		<div class="galerie">
			<?php
			// Vérifie si $categories est un tableau (donc plusieurs données à traiter)
			if (is_array($categories)) {
				// Extrait les ID des catégories de la photo actuelle
				$identifiants_categories = wp_list_pluck($categories, "term_id");
				
				// Arguments pour récupérer 2 photos aléatoires de la même catégorie
				$arguments = [
					"post_type" => "photo", // Type de post à récupérer (dans ce cas, 'photo')
					"posts_per_page" => 2, // Nombre de posts à afficher (ici, 2)
					"orderby" => "rand", // Tri aléatoire des posts
					// Filtrer les photos par catégorie
					"tax_query" => array(
						array(
							"taxonomy" => "categ", // Taxonomie à utiliser (ici, 'categ')
							"field" => "term_id", // Champ à utiliser pour la taxonomie (ici, 'term_id')
							"terms" => $identifiants_categories, // IDs des termes à filtrer (les catégories de la photo actuelle)
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
						// Affiche les photos trouvées
						echo "<figure class='galerie__item'>";
						the_post_thumbnail("catalogue", ["class" => "galerie__item--img"]); 
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
