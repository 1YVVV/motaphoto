document.addEventListener('DOMContentLoaded', function() {
    // Gestion du clic sur l'œil pour afficher/masquer les textes
    document.querySelectorAll('.conteneur-photo__overlay--oeil').forEach(function(oeil) {
        oeil.addEventListener('click', function() {
            const overlay = this.closest('.conteneur-photo__overlay');
            const titre = overlay.querySelector('.conteneur-photo__overlay--titre');
            const categorie = overlay.querySelector('.conteneur-photo__overlay--categorie');
            
            // Alterner la visibilité des textes
            titre.style.display = (titre.style.display === 'block' || titre.style.display === '') ? 'none' : 'block';
            categorie.style.display = (categorie.style.display === 'block' || categorie.style.display === '') ? 'none' : 'block';
        });
    });

    // Gestion du clic sur le zoom pour ouvrir la lightbox
    document.querySelectorAll('.conteneur-photo__overlay--zoom').forEach(function(zoom) {
        zoom.addEventListener('click', function() {
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = lightbox.querySelector('.lightbox__img');
            const imageSrc = this.closest('.conteneur-photo').querySelector('.conteneur-photo__img').src;
            
            lightboxImg.src = imageSrc;
            lightbox.style.display = 'flex';
        });
    });

    // Gestion de la fermeture de la lightbox
    document.querySelector('.lightbox__close').addEventListener('click', function() {
        document.getElementById('lightbox').style.display = 'none';
    });

    // Fermer la lightbox en cliquant en dehors de l'image
    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});
