<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMH - Training</title>
</head>
<?php
    wp_head();
?>
<body <?php body_class(); ?>>
        <section class="training-hero-section">
            <img src="" alt="">
            <h1 class="training-title"></h1>
        </section>
        <nav class="training-page-nav">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <p>
                    BACK TO HOME PAGE
                </p>
            </a>
        </nav>
    <?php get_footer(); ?>
</body>
</html>