jQuery(document).ready(function($) {
    // Ouverture de la modale
    $(".ouverture-modale").on("click", function(e) {
        // Empêche le comportement par défaut du lien ou bouton
        e.preventDefault();
        $("#modale").fadeIn();
    });

    // Fermeture de la modale
    $("#modale").on("click", function(e) {
        if (e.target === this) {
            $(this).fadeOut();
        }
    });

    // Fermeture de la modale via un bouton spécifique
    $(".close-modale").on("click", function(z) {
        e.preventDefault();
        $("#modale").fadeOut();
    });
});