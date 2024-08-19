jQuery(document).ready(function($) {
    let miniatureActuelle;

    // Lors du survol de la flèche
    $(".fleche").on("mouseover", function() {
        // Récupérer et stocker l'URL actuelle de la miniature
        miniatureActuelle = $(".miniature img").attr("src");
        // Récupérer l'URL de la miniature à prévisualiser depuis l'attribut data-preview-url
        const apercuUrl = $(this).data("apercu-url");
        // Vérifier si l'URL est définie
        if (apercuUrl) {
            // Ajouter une classe pour l'animation de fade-out
            $(".miniature img").addClass("hidden");
            // Attendre la fin de la transition avant de
            // mettre à jour la miniature avec l'URL de prévisualisation
            setTimeout(function() {
                $(".miniature img").attr("src", apercuUrl).removeClass("hidden");
            }, 200); // Correspond à la durée de la transition
        }
    });

    // Lorsque l'on quitte le survol de la flèche
    $(".fleche").on("mouseout", function() {
        // Ajouter une classe pour l'animation de fade-out
        $(".miniature img").addClass("hidden");
        // Attendre la fin de la transition avant de restaurer la miniature actuelle
        setTimeout(function() {
            $(".miniature img").attr("src", miniatureActuelle).removeClass("hidden");
        }, 200);
    });
    
    // Lors du clic sur la flèche (optionnel)
    // $(".fleche").on("click", function() {
    //     // Laisser le lien faire son travail de redirection
    //     // Pas besoin de code ici si le lien est géré directement par l'attribut href
    // });
});

// Explications
// $(".fleche").on("mouseover", function() {...});
// Lorsqu'une flèche est survolée, ce code récupère l'URL de la miniature 
// depuis l'attribut data-preview-url de la flèche survolée et met à jour
//  la source de l'image dans la balise figure.miniature

// $(".miniature img").attr("src", previewUrl);
// Cette ligne met à jour l'attribut src de l'image 
// dans la balise figure.miniature avec l'URL de la miniature

// Clic sur la flèche : Le comportement par défaut du lien (redirection) 
// est conservé et n'a pas besoin d'être modifié en JavaScript


// Simplification : Le code est plus simple car il se base 
// uniquement sur les URLs déjà préparées par le PHP dans l'attribut data-url
// Suppression de AJAX : Le chargement dynamique via AJAX
//  n'est plus nécessaire, puisque tout est géré via le HTML initialement rendu.
// Maintien des fonctionnalités : L'affichage des miniatures 
// sur le survol des flèches est toujours présent, mais simplifié.

// Exactement, même sans AJAX, tu peux naviguer entre les photos sans recharger toute la page. Voici comment cela fonctionne :
// Explication
//     Préchargement des données : Lorsque la page est initialement chargée, les URLs des photos précédente et suivante (basées sur la date ou l'année de publication) sont déjà calculées et intégrées dans le HTML via WordPress.
//     Changement de photo en JavaScript : En utilisant JavaScript (ou jQuery), tu peux mettre à jour l'image affichée en modifiant simplement le src de l'élément <img> qui affiche la photo. Ce changement n'entraîne pas un rechargement complet de la page, seulement la modification d'un élément spécifique.
// Comment cela fonctionne
//     Préparer les URLs : Dans le code PHP, tu détermines à l'avance les URLs des photos précédente et suivante et les insères dans des attributs data ou directement dans le HTML.
//     Interception du clic : Avec JavaScript, tu interceptes les clics sur les flèches de navigation.
//     Mise à jour de la photo : Au lieu de recharger toute la page, tu modifies uniquement l'élément <img> pour afficher la nouvelle photo.