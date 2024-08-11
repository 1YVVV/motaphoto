<?php
$hero_post_id = get_option('hero_post_id'); // ID du post utilisé pour le hero, stocké dans les options du thème ou ailleurs
echo $hero_post_id;
if ($hero_post_id) {
    $hero_image_url = get_the_post_thumbnail_url($hero_post_id, 'full');
    if ($hero_image_url) {
        echo '<style>
            .hero {
                background-image: url("' . esc_url($hero_image_url) . '");
            }
        </style>';
    }
}
?>