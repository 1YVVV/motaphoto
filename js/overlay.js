
// Fonction exportée utilisée pour attacher les évènements de clic
export function initialisationOverlay() {
    // Fonction privée appelée depuis la fonction exportée
    function overlayPhoto() {
        document.querySelectorAll(".conteneur-photo__overlay--oeil").forEach(function(oeil) {
            // Supprime les anciens écouteurs pour éviter les doublons
            oeil.removeEventListener("click", redirectionPhoto);
            // Ajoute les nouveaux écouteurs
            oeil.addEventListener("click", redirectionPhoto);
        });
        document.querySelectorAll(".conteneur-photo__overlay--zoom").forEach(function(zoom) {
            zoom.removeEventListener("click", ouvertureLightbox);
            zoom.addEventListener("click", ouvertureLightbox);
        });
    }
    // Redirection vers la page d'info de la photo au clic sur l'oeil
    function redirectionPhoto(event) {
        event.preventDefault();
        // Récupération de l'URL de l'attribut data-url
        const url = this.querySelector("img").getAttribute("data-url");
        // Si l'URL existe, redirection vers la page de la photo
        if (url) {
            window.location.href = url;
        } else {
            alert("URL de redirection non trouvée.");
        }
    }

    function ouvertureLightbox(event) {
        event.preventDefault();
        // Ici tu pourrais déclencher manuellement la lightbox.js si nécessaire
    }
    // Application des évènements sur les photos existantes
    overlayPhoto();
    // Ces évènements de clic doivent être réappliqués 
    // après chaque maj du contenu via AJAX
}