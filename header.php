<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<a href="#main-content"
   style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;"
   onfocus="this.style.cssText='position:fixed;top:0;left:0;width:auto;height:auto;padding:1rem 2rem;background:#000;color:#fff;font-size:1rem;z-index:99999;'">
    Zum Hauptinhalt springen
</a>

<div class="loading-screen" id="loadingScreen" aria-hidden="true">
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/loading-1.webp"
         alt="Eine animierte Hundetatze, die als Ladeindikator dient." id="loadingImg" aria-hidden="true">
    <h1 id="loadingText" aria-live="polite">Bitte warten – Inhalt wird geladen</h1>
</div>

<nav class="navigation-menu" id="navMenu" aria-label="Hauptnavigation">

    <div class="navigation-container left-navigation-container" role="list">
        <?php wp_nav_menu([
            'theme_location' => 'nav-left',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'walker'         => new TMH_Nav_Walker(),
            'fallback_cb'    => false,
        ]); ?>
    </div>

    <a href="<?php echo esc_url(home_url('/')); ?>"
       aria-label="Zur Startseite – <?php bloginfo('name'); ?>">
        <?php
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) :
            $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
        ?>
            <img class="menu-logo"
                 src="<?php echo esc_url($logo_url); ?>"
                 alt="<?php bloginfo('name'); ?> Logo"
                 title="TMH Neulingen – Zur Startseite">
        <?php else : ?>
            <img class="menu-logo"
                 src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo.png"
                 alt="<?php bloginfo('name'); ?> Logo"
                 title="TMH Neulingen – Zur Startseite">
        <?php endif; ?>
    </a>

    <div class="navigation-container right-navigation-container" role="list">
        <?php wp_nav_menu([
            'theme_location' => 'nav-right',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'walker'         => new TMH_Nav_Walker(),
            'fallback_cb'    => false,
        ]); ?>
    </div>

    <div class="hamburger-menu" id="hamburgerMenu"
         role="button"
         tabindex="0"
         aria-expanded="false"
         aria-controls="mobileMenu"
         aria-label="Menü öffnen">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
    </div>

    <div class="mobile-menu" id="mobileMenu"
         aria-hidden="true"
         role="navigation"
         aria-label="Mobile Navigation">
        <?php wp_nav_menu([
            'theme_location' => 'nav-mobile',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'walker'         => new TMH_Mobile_Walker(),
            'fallback_cb'    => false,
        ]); ?>
    </div>

</nav>