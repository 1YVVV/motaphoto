// Fonction exportée utilisée pour attacher les évènements de clic
export function initialisationOverlay() {
    console.log("testFunction appelé !");
    // Fonction privée appelée depuis la fonction exportée
    function overlayPhoto() {
        document.querySelectorAll(".conteneur-photo__overlay--oeil").forEach(function(oeil) {
            // Supprime les anciens écouteurs pour éviter les doublons
            oeil.removeEventListener("click", gestionClic);
            // Ajoute les nouveaux écouteurs
            oeil.addEventListener("click", gestionClic);
        });
    }
    function gestionClic(event) {
        event.preventDefault();
        // Récupération de l'URL de l'attribut data-url
        const url = this.querySelector("img").getAttribute("data-url");
        // Si l'URL existe, redirection vers la page de la photo
        if (url) {
            window.location.href = url;
        } else {
            console.error('URL de redirection non trouvée.');
        }
    }
    // Attache les évènements sur les photos existantes
    overlayPhoto();
    // Ces évènements de clic doivent être réappliqués 
    // après chaque maj du contenu via AJAX
}