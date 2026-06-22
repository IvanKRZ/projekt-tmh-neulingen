<?php
/**
 * Template Name: Training
 */
$GLOBALS['hide_legal_elements'] = true;
get_header();

$training_query = new WP_Query([
    'post_type'      => 'training',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
]);

$trainings = [];
if ($training_query->have_posts()) {
    $idx = 0;
    while ($training_query->have_posts()) {
        $training_query->the_post();
        $trainings[] = [
            'title'   => get_the_title(),
            'img_url' => get_the_post_thumbnail_url(get_the_ID(), 'full') ?: '',
            'desc'    => apply_filters('the_content', get_the_content()),
            'color'   => 'page' . ($idx + 1),
        ];
        $idx++;
    }
    wp_reset_postdata();
}
?>

<div class="training-menu" id="trainingMenu" role="dialog" aria-modal="true" aria-label="Trainingsübersicht" aria-hidden="true">
    <button class="training-menu__close" id="trainingMenuClose" aria-label="Menü schließen">✕</button>
    <nav aria-label="Trainingsübersicht">
        <ul>
            <?php foreach ($trainings as $i => $t) : ?>
                <li>
                    <a href="#training-<?php echo $i; ?>" data-index="<?php echo $i; ?>">
                        <?php echo esc_html($t['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>

<div class="training-page-menu" id="trainingPageMenu"
     role="dialog" aria-modal="true" aria-label="Seitennavigation" aria-hidden="true"
     style="display:none">
    <button class="training-page-menu__close" id="trainingPageMenuClose" aria-label="Menü schließen">✕</button>
    <nav aria-label="Seitennavigation">
        <?php wp_nav_menu([
            'theme_location' => 'training_page_nav',
            'container'      => false,
            'menu_class'     => '',
            'fallback_cb'    => false,
        ]); ?>
    </nav>
</div>

<main id="main-content">
    <div class="training-wrapper" id="trainingWrapper">

        <?php foreach ($trainings as $i => $t) : ?>
            <section class="training-section training-section--<?php echo esc_attr($t['color']); ?>"
                     id="training-<?php echo $i; ?>"
                     aria-labelledby="training-title-<?php echo $i; ?>">

                <div class="training-paws" aria-hidden="true">
                    <?php for ($p = 1; $p <= 6; $p++) : ?>
                        <img class="paw paw-<?php echo $p; ?>"
                             src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/contact-paw.png"
                             alt="">
                    <?php endfor; ?>
                </div>

                <button class="training-nav-menu-btn"
                        aria-label="Training Menü öffnen"
                        aria-expanded="false"
                        aria-controls="trainingMenu">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/menu.webp"
                         alt="" aria-hidden="true">
                </button>

                <button class="training-nav-btn training-nav-btn--prev"
                        data-dir="-1"
                        aria-label="Vorheriges Training"
                        <?php echo $i === 0 ? 'disabled aria-disabled="true"' : ''; ?>>
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/arrow-up.webp"
                         alt="" aria-hidden="true">
                </button>

                <div class="training-content">
                    <?php if ($t['img_url']) : ?>
                        <img src="<?php echo esc_url($t['img_url']); ?>"
                             alt="<?php echo esc_attr($t['title']); ?>"
                             loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>">
                    <?php endif; ?>
                    <h2 id="training-title-<?php echo $i; ?>"><?php echo esc_html($t['title']); ?></h2>
                </div>

                <button class="training-nav-btn training-nav-btn--next"
                        data-dir="1"
                        aria-label="Nächstes Training"
                        <?php echo $i === count($trainings) - 1 ? 'disabled aria-disabled="true"' : ''; ?>>
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/arrow-down.webp"
                         alt="" aria-hidden="true">
                </button>

                <button class="training-mehr"
                        data-index="<?php echo $i; ?>"
                        data-title="<?php echo esc_attr($t['title']); ?>"
                        aria-label="<?php echo esc_attr('Mehr erfahren über ' . $t['title']); ?>"
                        aria-expanded="false"
                        aria-controls="trainingDetail">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/mehr.webp"
                        alt="" aria-hidden="true">
                    <span class="training-mehr__label">Mehr<br>erfahren</span>
                </button>

            </section>
        <?php endforeach; ?>

    </div>

    <nav class="training-bottom-nav" aria-label="Seitennavigation">
        <?php
        $locations = get_nav_menu_locations();
        $menu_id   = $locations['training_page_nav'] ?? 0;
        $nav_items = $menu_id ? wp_get_nav_menu_items($menu_id) : [];

        if ($nav_items) :
            foreach ($nav_items as $idx => $item) : ?>
                <a href="<?php echo esc_url($item->url); ?>"><?php echo esc_html($item->title); ?></a>
                <?php if ($idx < count($nav_items) - 1) : ?>
                    <span aria-hidden="true">|</span>
                <?php endif; ?>
            <?php endforeach;
        else : ?>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('aktuelles'))); ?>">Aktuelles</a>
            <span aria-hidden="true">|</span>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('uberuns'))); ?>">Über uns</a>
            <span aria-hidden="true">|</span>
            <a href="<?php echo esc_url(home_url('/')); ?>">Homepage</a>
            <span aria-hidden="true">|</span>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('galerie'))); ?>">Galerie</a>
            <span aria-hidden="true">|</span>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>">Kontakt & Anfahrt</a>
        <?php endif; ?>
        <div class="hamburger-menu-training-page" id="trainingHamburger"
            role="button" tabindex="0" aria-expanded="false"
            aria-controls="trainingPageMenu" aria-label="Menü öffnen">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </div>
    </nav>

    <div class="training-detail" id="trainingDetail"
         role="dialog" aria-modal="true" aria-hidden="true"
         aria-labelledby="trainingDetailTitle">
        <img class="training-detail__close" id="trainingDetailClose" aria-label="Schließen"
             src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/closebtn.png">
        <div class="training-detail__content">
            <h2 class="training-detail__title" id="trainingDetailTitle"></h2>
            <div class="training-detail__desc" id="trainingDetailDesc"></div>
        </div>
    </div>

    <?php if (!empty($trainings)) :
        $schema_items = array_map(fn($t, $i) => [
            '@type'    => 'Service',
            'name'     => $t['title'],
            'position' => $i + 1,
        ], $trainings, array_keys($trainings));
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SportsClub",
        "name": "TMH Neulingen e.V.",
        "url": "<?php echo esc_url(home_url('/')); ?>",
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Trainingsangebote",
            "itemListElement": <?php echo wp_json_encode($schema_items, JSON_UNESCAPED_UNICODE); ?>
        }
    }
    </script>
    <?php endif; ?>

    <script>
    window.trainingDescriptions = <?php echo wp_json_encode(array_map(function($t) {
        return wp_kses_post($t['desc']);
    }, $trainings)); ?>;
    </script>
</main>

<?php get_footer(); ?>