<?php
// Définition des variables avant l'intégration du template part
$titre = isset($titre) ? $titre : "Titre par défaut";
$categorie = isset($categorie) ? $categorie : "Catégorie par défaut";

// // Récupération de l'ID de la photo, valeur par défaut 0
$photo_id = isset($photo_id) ? $photo_id : 0;
// Récupération de l'URL de la page de la photo
$data_url = esc_url(get_permalink($photo_id));
// Récupération de la référence de la photo
$reference = get_field("reference");
?>

<div class="conteneur-photo__overlay">
    <div class="conteneur-photo__overlay--oeil">
        <img src="<?php echo get_theme_file_uri('assets/images/icones/oeil.svg')?>" alt="Voir" data-url="<?php echo $data_url; ?>">
    </div>
    <!-- L'attribut data-photo contient l'URL de la photo en taille réelle -->
    <div class="conteneur-photo__overlay--zoom" data-photo="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id($photo_id), 'full'); ?>" data-reference="<?php echo esc_html($reference); ?>" data-categorie="<?php echo esc_html($categorie); ?>">
        <img src="<?php echo get_theme_file_uri('assets/images/icones/zoom.svg')?>" alt="Agrandir">
    </div>
    <div class="conteneur-photo__overlay--titre"><?php echo esc_html($titre); ?></div>
    <div class="conteneur-photo__overlay--categorie"><?php echo esc_html($categorie); ?></div>
</div>
