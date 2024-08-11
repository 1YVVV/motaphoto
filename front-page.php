<?php get_header(); ?>

<main class="site-main">
    <section class="hero" style="background-image: url('<?php echo esc_url( get_theme_mod("image_hero") ); ?>');">
        <h1 class="contour"><?php bloginfo("description") ?></h1>
    </section>
    <section>
        <?php get_template_part("template-parts/catalogue-photos"); ?>
    </section>
</main>

<?php get_footer(); ?>
