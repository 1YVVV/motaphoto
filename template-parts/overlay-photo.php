<?php
// Assurez-vous que ces variables sont définies avant d'inclure ce template part
$titre = isset($titre) ? $titre : 'Titre par défaut';
$categorie = isset($categorie) ? $categorie : 'Catégorie par défaut';
$photo_id = isset($photo_id) ? $photo_id : 0; // ID de la photo, valeur par défaut 0

// Générer l'URL de la page de détail
$data_url = esc_url(get_permalink($photo_id));
?>

<div class="conteneur-photo__overlay">
    <div class="conteneur-photo__overlay--oeil">
        <img src="<?php echo get_theme_file_uri('assets/images/icones/oeil.svg')?>" alt="Voir" data-url="<?php echo $data_url; ?>">
    </div>
    <div class="conteneur-photo__overlay--zoom">
        <img src="<?php echo get_theme_file_uri('assets/images/icones/zoom.svg')?>" alt="Agrandir">
    </div>
    <div class="conteneur-photo__overlay--titre"><?php echo esc_html($titre); ?></div>
    <div class="conteneur-photo__overlay--categorie"><?php echo esc_html($categorie); ?></div>
</div>