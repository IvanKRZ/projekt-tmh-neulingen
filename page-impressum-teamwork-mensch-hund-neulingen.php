<?php
/**
 * Template Name: Impressum
 */
get_header();
?>

<main id="main-content">
    <section class="legal-class entry-content" aria-label="Impressum">
        <?php the_content(); ?>
    </section>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NGO",
        "name": "Teamwork Mensch & Hund Neulingen e.V.",
        "url": "<?php echo esc_url(home_url('/')); ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Göbricher Straße 25",
            "postalCode": "75245",
            "addressLocality": "Neulingen",
            "addressCountry": "DE"
        }
    }
    </script>
</main>

<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>