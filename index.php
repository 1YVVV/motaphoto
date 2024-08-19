<?php get_header(); ?>
<body>
    <main id="accueil">
        <?php while(have_posts()) : the_post(); ?>
        <article>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail("medium") ?>
                <h2><?php the_title(); ?></h2>
            </a>
            <?php the_excerpt(); ?>
        </article>
        <?php endwhile; ?>
    </main>
</body>
<?php get_footer(); ?>
