jQuery(document).ready(function($) {
    // Ouverture de la modale
    $(".ouverture-modale").on("click", function(e) {
        // Empêche le comportement par défaut du lien ou bouton
        e.preventDefault();
        $("#modale").css("display", "flex").hide().fadeIn();
        
        // Récupération de la référence dans la page
        // avec l'id ajouté dans le paragraphe de la single ligne 35
        const referencePhoto = document.getElementById("reference-photo").getAttribute("data-reference");

        // Affichage de la référence dans le champ du formulaire CF7
        $("input[name='reference-photo']").val(referencePhoto);
    });
    
    // Fermeture de la modale en cliquant en dehors du contenu
    $("#modale").on("click", function(e) {
        if (e.target === this) { // Vérifie si le clic est sur l'overlay
            $(this).fadeOut(function() {
                $(this).css("display", "none");
            });
        }
    });
});