<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php wp_head(); ?>
</head>
<body>
    <nav class="navigation-menu" id="navMenu">
        <div class="navigation-container left-navigation-container">
            <p>
                <a class="menu-button" href="">
                    Aktuelles
                </a>
            </p>
            <p>
                <a class="menu-button" href="">
                    Training
                </a>
            </p>
        </div>
        <a href="">
            <img class="menu-logo" src="<?php echo get_template_directory_uri(); ?>/assets/images/dog.png" alt="Logo von TMH Neulingen">
        </a>
        <div class="navigation-container right-navigation-container">
            <p>
                <a class="menu-button" href="">
                    Über uns
                </a>
            </p>
            <p>
                <a class="menu-button" href="">
                    Galerie
                </a>
            </p>
        </div>
    </nav>
</body>
</html>