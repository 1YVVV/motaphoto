<div id="lightbox" class="lightbox">
    <!-- Flèche gauche -->
    <div class="lightbox__fleche lightbox__fleche--gauche">
        <img src="<?php echo get_theme_file_uri('assets/images/icones/precedente.svg')?>" alt="Flèche gauche">
        <p>Précédente</p>
    </div>   
    
    <div class="lightbox__contenu">
        <img class="lightbox__photo" src="" alt="Photo en grand format">
        <div class="lightbox__details">
            <div class="lightbox__details--reference"></div>
            <div class="lightbox__details--categorie"></div>
        </div>
    </div>

    <!-- Flèche droite -->
    <div class="lightbox__fleche lightbox__fleche--droite">
        <p>Suivante</p>
        <img src="<?php echo get_theme_file_uri('assets/images/icones/suivante.svg')?>" alt="Flèche droite">
    </div>

    <div class="lightbox__close">&times;</div>
</div>