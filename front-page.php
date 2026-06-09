<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    wp_head();
    ?>
</head>
<body <?php body_class(); ?>>
    <?php get_header(); ?>

    <section class="landing-page-hero-section">
        <h1 class="landing-page-hero-title">TMH Neulingen</h1>
        <img class="landing-page-hero" src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-doggo.png" alt="">
    </section>

    <?php wp_footer(); ?>
</body>
</html>