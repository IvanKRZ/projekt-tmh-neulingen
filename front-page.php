<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMH - Neulingen</title>
    <?php
    wp_head();
    ?>
</head>
<body <?php body_class(); ?>>
    <?php get_header(); ?>

    <section class="landing-page-hero-section" aria-label="Willkommensbereich">
        <h1 class="landing-page-hero-title">TMH Neulingen</h1>
        <img class="landing-page-hero" src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-img.png" alt="">
    </section>

<?php get_template_part('legal-elements'); ?>
<?php get_template_part('floating-buttons'); ?>
<?php wp_footer(); ?>