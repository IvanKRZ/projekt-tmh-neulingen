<?php
/**
 * Template Name: Über uns
 */
get_header(); ?>

<main id="main-content">
    <section class="about-us-section" aria-labelledby="uberuns-heading">
        <div class="about-us-left-container">
            <h1 class="about-us" id="uberuns-heading"><?php the_title(); ?></h1>
            <?php the_content(); ?>
        </div>
        <div class="about-us-right-container" aria-labelledby="membership-heading">
            <?php
            $mitglied_page = get_page_by_path('mitgliedschaft');
            if ($mitglied_page) : ?>
                <h2 class="membership" id="membership-heading">
                    <?php echo esc_html(get_the_title($mitglied_page)); ?>
                </h2>
                <?php echo apply_filters('the_content', $mitglied_page->post_content); ?>
            <?php endif; ?>
        </div>

    </section>

    <section class="donation-section" aria-label="Spendenbereich">
        <?php $gooding_url = get_theme_mod('gooding_iframe_src'); ?>
        <?php if ($gooding_url) : ?>
            <section class="donation-section" aria-label="Spendenbereich">
                <iframe
                    sandbox="allow-top-navigation allow-forms allow-popups-to-escape-sandbox allow-same-origin allow-popups allow-scripts"
                    frameborder="0"
                    allowtransparency="true"
                    scrolling="yes"
                    title="Gooding Spendenbereich – TMH Neulingen"
                    src="<?php echo esc_url($gooding_url); ?>">
                </iframe>
            </section>
        <?php endif; ?>
    </section>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "AboutPage",
        "name": "Über uns – TMH Neulingen",
        "description": "Informationen über den Hundesportverein TMH Neulingen e.V. und Mitgliedschaft",
        "url": "<?php echo esc_url(get_permalink()); ?>",
        "mainEntity": {
            "@type": "SportsClub",
            "name": "TMH Neulingen e.V.",
            "url": "<?php echo esc_url(home_url('/')); ?>",
            "sport": "Hundesport"
        }
    }
    </script>
</main>

<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>