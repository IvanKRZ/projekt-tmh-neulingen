<?php wp_head(); ?>
</head>
<body>
    <body <?php body_class(); ?>>
    <div class="loading-screen" id="loadingScreen">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/loading-1.png" 
         alt="Loading" id="loadingImg">
        <h1 id="loadingText"></h1>
    </div>
    <nav class="navigation-menu" id="navMenu" aria-label="Hauptnavigation">
        <div class="navigation-container left-navigation-container" role="list">
            <div role="listitem">
                <a class="menu-button" href="<?php echo get_permalink(get_page_by_path('aktuelles')) ?>" aria-label="Aktuelles">
                    Aktuelles
                </a>
            </div>
            <div role="listitem">
                <a class="menu-button" href="<?php echo get_permalink(get_page_by_path('training')) ?>" aria-label="Training">
                    Training
                </a>
            </div>
        </div>
        <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="Zur Startseite">
            <img class="menu-logo" src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="TMH Neulingen Logo">
        </a>
        <div class="navigation-container right-navigation-container" role="list">
            <div role="listitem">
                <a class="menu-button" href="<?php echo get_permalink(get_page_by_path('uberuns')) ?>" aria-label="Über uns">
                    Über uns
                </a>
            </div>
            <div role="listitem">
                <a class="menu-button" href="<?php echo get_permalink(get_page_by_path('galerie')) ?>" aria-label="Galerie">
                    Galerie
                </a>
            </div>
        </div>
        <div class="hamburger-menu" id="hamburgerMenu">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <a href="<?php echo get_permalink(get_page_by_path('/')) ?>">Homepage</a>
            <a href="<?php echo get_permalink(get_page_by_path('aktuelles')) ?>">Aktuelles</a>
            <a href="<?php echo get_permalink(get_page_by_path('training')) ?>">Training</a>
            <a href="<?php echo get_permalink(get_page_by_path('uberuns')) ?>">Über uns</a>
            <a href="<?php echo get_permalink(get_page_by_path('galerie')) ?>">Galerie</a>
            <a href="<?php echo get_permalink(get_page_by_path('contact')) ?>">Kontakt</a>
            <a href="<?php echo get_permalink(get_page_by_path('kalender')) ?>">Kalender</a>
        </div>
    </nav>