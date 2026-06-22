<?php get_header();

$hero_title = get_theme_mod('homepage_hero_title', 'TMH Neulingen');
$hero_image = get_theme_mod('homepage_hero_image', get_template_directory_uri() . '/assets/images/hero-img.webp');

$front_page_id = get_option('page_on_front');
$front_page_content = '';
if ($front_page_id) {
    $raw = get_post_field('post_content', $front_page_id);
    $front_page_content = apply_filters('the_content', $raw);
}
?>

<main id="main-content">

    <section class="landing-page-hero-section" aria-labelledby="hero-heading">
        <h1 class="landing-page-hero-title" id="hero-heading">
            <?php echo esc_html($hero_title); ?>
        </h1>
        <img class="landing-page-hero"
             src="<?php echo esc_url($hero_image); ?>"
             alt="<?php echo esc_attr($hero_title . ' – Hundesportverein'); ?>"
             loading="eager"
             fetchpriority="high">
    </section>

    <div class="homepage-grid">

        <?php if (!empty(trim(strip_tags($front_page_content)))) : ?>
            <section class="homepage-content" aria-label="Willkommenstext">
                <?php echo wp_kses_post($front_page_content); ?>
            </section>
        <?php endif; ?>

        <?php if (get_theme_mod('homepage_show_latest_aktuelles', false)) :
            $latest = new WP_Query(['posts_per_page' => 1, 'post_status' => 'publish']);
            if ($latest->have_posts()) : $latest->the_post(); ?>
                <section class="homepage-latest" aria-label="Aktueller Beitrag">
                    <div class="blog-grid">
                        <article class="blog-item" aria-labelledby="latest-post-heading">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="blog-item__image">
                                    <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                        <?php the_post_thumbnail('large'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="blog-item__content">
                                <a href="<?php the_permalink(); ?>"
                                   class="blog-item__link"
                                   aria-label="<?php echo esc_attr(sprintf('Beitrag lesen: %s', get_the_title())); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2"
                                         aria-hidden="true" focusable="false">
                                        <line x1="7" y1="17" x2="17" y2="7"/>
                                        <polyline points="7 7 17 7 17 17"/>
                                    </svg>
                                </a>
                                <time class="blog-item__date" datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
                                    <?php echo esc_html(get_the_date('d.m.Y')); ?>
                                </time>
                                <h2 class="blog-item__title" id="latest-post-heading">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <p class="blog-item__excerpt">
                                    <?php echo esc_html(wp_trim_words(get_the_content(), 90, '...')); ?>
                                </p>
                            </div>
                        </article>
                    </div>
                </section>
            <?php wp_reset_postdata();
            endif;
        endif; ?>

    </div>

</main>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SportsClub",
    "name": "Teamwork Mensch & Hund Neulingen e.V.",
    "url": "<?php echo esc_url(home_url('/')); ?>",
    "description": "Ziel des Hundesportes ist die größtmögliche Harmonie zwischen Mensch und Hund.",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Göbricher Straße 25",
        "postalCode": "75245",
        "addressLocality": "Neulingen",
        "addressCountry": "DE"
    }
}
</script>

<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>