function lightbox() {

    const photos = Array.from(document.querySelectorAll(".conteneur-photo__overlay--zoom")); // Sélection des éléments avec l'overlay zoom
    const lightbox = document.querySelector(".lightbox");
    const contenuLightbox = lightbox.querySelector(".lightbox__photo");
    const lightboxReference = lightbox.querySelector(".lightbox__details--reference");
    const lightboxCategorie = lightbox.querySelector(".lightbox__details--categorie");

    let indexPhoto = -1;

    // Tableau de stockage des informations relatives à chaque photo
    const tableauPhotos = photos.map(element => {
        const photoUrl = element.getAttribute("data-photo");
        const referencePhoto = element.getAttribute("data-reference");
        const categoriePhoto = element.getAttribute("data-categorie");
        return {
            src: photoUrl,
            reference: referencePhoto,
            categorie: categoriePhoto,
        };
    });

    // Détection du format de l'image
    function setImageOrientationClass(img, container) {
        // Crée un nouvel objet Image
        const image = new Image();
        image.src = img.src;

        // Attend que l'image soit complètement chargée
        image.onload = function() {
            const ratio = image.width / image.height;
            // Seuil pour le format carré ou presque carré
            const closeToSquare = Math.abs(ratio - 1) < 0.35; 

            if (image.width > image.height) {
                if (closeToSquare) {
                    container.classList.add("carre");
                    container.classList.remove("portrait", "paysage");
                } else {
                    container.classList.add("paysage");
                    container.classList.remove("portrait", "carre");
                }
            } else {
                container.classList.add("portrait");
                container.classList.remove("paysage", "carre");
            }
        };
    }

    // Ouverture de la Lightbox
    photos.forEach((element, index) => {
        element.addEventListener("click", function() {
            // Affichage de la Lightbox et configuration de l'index sélectionné
            lightbox.style.display = "flex";
            indexPhoto = index;

            // Chargement des informations de la photo correspondante
            const photoActuelle = tableauPhotos[indexPhoto];
            contenuLightbox.src = photoActuelle.src;
            lightboxReference.textContent = photoActuelle.reference;
            lightboxCategorie.textContent = photoActuelle.categorie;

             // Applique les classes CSS en fonction de l'orientation
             setImageOrientationClass(contenuLightbox, lightbox.querySelector(".lightbox__contenu"));
        });
    });

    // Navigation vers la photo suivante
    const photoSuivante = lightbox.querySelector(".lightbox__fleche--droite");
    photoSuivante.addEventListener("click", function() {
        if (indexPhoto >= 0) {
            indexPhoto = (indexPhoto + 1) % photos.length; // Incrémentation avec boucle
            const photoActuelle = tableauPhotos[indexPhoto];
            contenuLightbox.src = photoActuelle.src;
            lightboxReference.textContent = photoActuelle.reference;
            lightboxCategorie.textContent = photoActuelle.categorie;

            // Applique les classes CSS en fonction de l'orientation
            setImageOrientationClass(contenuLightbox, lightbox.querySelector(".lightbox__contenu"));
        }
    });

    // Navigation vers la photo précédente
    const photoPrecedente = lightbox.querySelector(".lightbox__fleche--gauche");
    photoPrecedente.addEventListener("click", function() {
        if (indexPhoto >= 0) {
            indexPhoto = (indexPhoto - 1 + photos.length) % photos.length; // Décrémentation avec boucle
            const photoActuelle = tableauPhotos[indexPhoto];
            contenuLightbox.src = photoActuelle.src;
            lightboxReference.textContent = photoActuelle.reference;
            lightboxCategorie.textContent = photoActuelle.categorie;

            // Applique les classes CSS en fonction de l'orientation
            setImageOrientationClass(contenuLightbox, lightbox.querySelector(".lightbox__contenu"));
        }
    });

    // Fermeture de la Lightbox
    function fermetureLightbox() {
        lightbox.style.display = "none";
        indexPhoto = -1;
    }

    // Attacher l'événement de fermeture de la Lightbox
    document.querySelector(".lightbox__close").addEventListener("click", fermetureLightbox);
}
document.addEventListener("DOMContentLoaded", lightbox);
