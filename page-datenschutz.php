<?php
/**
 * Template Name: Datenschutz
 */
get_header();
?>

<main id="main-content">
    <section class="legal-class" aria-label="Datenschutzerklärung">
        <?php the_content(); ?>
    </section>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Datenschutzerklärung",
        "url": "<?php echo esc_url(get_permalink(get_page_by_path('datenschutz'))); ?>",
        "publisher": {
            "@type": "NGO",
            "name": "Teamwork Mensch & Hund Neulingen e.V.",
            "url": "<?php echo esc_url(home_url('/')); ?>"
        }
    }
    </script>
</main>

<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>