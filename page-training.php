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
            'desc' => get_the_excerpt(),
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
                    <a href="#training-<?php echo $i; ?>"
                       data-index="<?php echo $i; ?>">
                        <?php echo esc_html($t['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
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

                <div id="training-desc-<?php echo $i; ?>" hidden>
                    <?php echo wp_kses_post($t['desc']); ?>
                </div>
                <button class="training-mehr"
                        data-desc="<?php echo esc_attr($t['desc']); ?>"
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
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('aktuelles'))); ?>">Aktuelles</a>
        <span aria-hidden="true">|</span>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('uberuns'))); ?>">Über uns</a>
        <span aria-hidden="true">|</span>
        <a href="<?php echo esc_url(home_url('/')); ?>">Homepage</a>
        <span aria-hidden="true">|</span>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('galerie'))); ?>">Galerie</a>
        <span aria-hidden="true">|</span>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>">Kontakt & Anfahrt</a>
    </nav>

    <div class="training-detail" id="trainingDetail"
         role="dialog" aria-modal="true" aria-hidden="true"
         aria-labelledby="trainingDetailTitle">
        <button class="training-detail__close" id="trainingDetailClose" aria-label="Schließen">✕</button>
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

</main>

<?php get_footer(); ?>