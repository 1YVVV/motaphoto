// Importation de la fonction d'initialisation des événements d'overlay
import { initialisationOverlay } from "./overlay.js";

document.addEventListener("DOMContentLoaded", function() {
    // On appelle la fonction importée lors du chargement initial
    initialisationOverlay();

    // Selecteurs personnalisés
    jQuery(document).ready(function($) {
        const filtre = document.querySelectorAll(".select-filtre");
        
        // Initialisation des filtres
        filtre.forEach(select => {
            const optionAffichee = select.querySelector(".select-filtre__option");
            const options = select.querySelectorAll(".choix-option");
            
            // Affichage-masquage des options de filtre
            optionAffichee.addEventListener("click", function() {
                select.classList.toggle("open");
            });
            
            // Sélection de l'option et mise à jour des photos
            options.forEach(option => {
                option.addEventListener("click", function() {
                    const valeurSelection = this.getAttribute("data-value");

                    // Réinitialisation du filtre si l'option "Aucun" est sélectionnée
                    if (valeurSelection === "") {
                        // Trouve le texte du label par défaut pour ce filtre
                        const label = select.querySelector(".select-filtre__option span").getAttribute("data-label");
                        // Affiche le label par défaut
                        optionAffichee.querySelector("span").textContent = label || "Sélectionner";
                        // Retirer toutes les sélections
                        options.forEach(opt => opt.classList.remove("selected"));
                    } else {
                        // Afficher la nouvelle option sélectionnée
                        optionAffichee.querySelector("span").textContent = this.textContent;
                        options.forEach(opt => opt.classList.remove("selected"));
                        this.classList.add("selected");
                    }
                    
                    // Fermer le menu déroulant
                    select.classList.remove("open");
                    
                    // Appeler la fonction pour mettre à jour les photos lors de la sélection du filtre
                    updatePhotos();
                });
            });
        });

        let pageActuelle = 1;

        // Chargement de plus de photos
        $('#charger-plus').on('click', function() {
            pageActuelle++;
            updatePhotos(pageActuelle); // Appelle la fonction avec la nouvelle page
        });

        function updatePhotos(page = 1) {
            const categorieSelectionnee = document.querySelector(".select-filtre[data-taxonomie='categ'] .choix-option.selected")?.getAttribute("data-value") || "";
            const formatSelectionne = document.querySelector(".select-filtre[data-taxonomie='format'] .choix-option.selected")?.getAttribute("data-value") || "";
            // Extrait la valeur de tri sélectionnée et utilise 'DESC' 
            // comme valeur par défaut si aucune option n’est sélectionnée
            const triAnnee = document.querySelector("#tri-annee .choix-option.selected")?.getAttribute("data-value") || "DESC";
            
            // Requête AJAX pour mettre à jour les photos
            $.ajax({
                url: my_ajax_object.ajax_url,
                type: "POST",
                data: {
                    action: "load_filtered_photos",
                    nonce: my_ajax_object.nonce,
                    categories: categorieSelectionnee,
                    formats: formatSelectionne,
                    tri_annee: triAnnee,
                    page: page // Envoie la page actuelle
                },
                success: function(reponse) {
                    if (reponse.success) {
                        if (page === 1) {
                            // Remplace les photos si on réinitialise la page
                            $("#galerie-photos").html(reponse.data);
                            pageActuelle = 1;
                        } else {
                            // Sinon on ajoute les nouvelles photos
                            $("#galerie-photos").append(reponse.data);
                        }
                        // Réapplique les évènements d'overlay après la maj
                        initialisationOverlay();
                    } else {
                        if (page === 1) {
                            $("#galerie-photos").html("<p>Aucune photo correspondante aux critères choisis</p>");
                        } else {
                            $("#galerie-photos").append("<p>Aucune photo trouvée</p>");
                        }
                    }
                },
                // Permet d'afficher le message d'erreur renvoyé par le serveur
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Ferme les menus déroulants lorsqu'on clique en dehors
        $(document).on("click", function(event) {
            if (!$(event.target).closest(".select-filtre").length) {
                $(".select-filtre").removeClass("open");
            }
        });
    });
});