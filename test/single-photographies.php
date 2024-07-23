<?php
	get_header();

// Récupération de l'identifiant de la photo (nom) dans l'URL
$slug = get_query_var('photo');

// Construction des critères de recherche
$args = [
    'post_type' => 'photo',
    'name' => $slug,
    'posts_per_page' => 1
];

// Requête auprès de la base de données wordpress pour récupérer la photo correspondante
$custom_query = new WP_Query($args);

if ($custom_query->have_posts()) :
    while ($custom_query->have_posts()) : $custom_query->the_post();

	$reference = get_field('reference');
	$type = get_field('type');
	$categories = wp_get_post_terms(get_the_ID(), 'categ');
	$formats = wp_get_post_terms(get_the_ID(), 'format');
?>
<div class="single">
	<!-- Partie de GAUCHE information-->
	 
    <div class="informations">
		<h2><?php echo the_title();?></h2>
		<p>RÉFÉRENCE : <?php echo esc_html($reference);?> </p>
		<p>CATÉGORIE : 
			<?php
			if (is_array($categories)) {
				foreach ($categories as $categorie) {
					if (is_object($categorie) && property_exists($categorie, 'name')) {
						echo esc_html($categorie->name);
					}
				}
			} else {
				echo 'Pas de catégories disponibles';
			}
			?>
		</p>
		<p>FORMAT : 
			<?php
			if (is_array($formats)) {
				foreach ($formats as $format) {
					if (is_object($format) && property_exists($format, 'name')) {
						echo esc_html($format->name);
					}
				}
			} else {
				echo 'Pas de formats disponibles';
			}
			?>
		</p>
		<p>TYPE : <?php echo esc_html($type);?> </p>
		<p>ANNÉE : <?php echo esc_html($annee);?> </p>
	</div>
</div>
		<!-- Zone droite - La photo -->
<div class="...">
	<div class="...">
		<?php the_post_thumbnail('large'); // Afficher l'image mise en avant ?>
	</div>
</div>


<?php
    endwhile;
    wp_reset_postdata();
endif;
?>