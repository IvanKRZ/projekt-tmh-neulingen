<?php
/**
 * Template Name: Datenschutz
 */
get_header();
?>

<main>
    <section class="legal-class" aria-labelledby="datenschutz-heading">
        <?php the_content(); ?>
    </section>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Datenschutzerklärung",
        "url": "https://tmh-neulingen.de/datenschutz",
        "publisher": {
            "@type": "NGO",
            "name": "TMH Neulingen – Teamwork Mensch & Hund e. V."
        }
    }
    </script>
</main>

<?php get_template_part('legal-elements'); ?>
<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>