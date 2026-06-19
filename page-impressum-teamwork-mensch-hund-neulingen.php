<?php
/**
 * Template Name: Impressum
 */
get_header();
?>

<main>
    <section class="legal-class" aria-labelledby="impressum-heading">
        <?php the_content(); ?>
    </section>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NGO",
        "name": "TMH Neulingen – Teamwork Mensch & Hund e. V.",
        "url": "https://tmh-neulingen.de",
        "email": "info@tmh-neulingen.de",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Hinten auf der Hub 2/1",
            "postalCode": "75245",
            "addressLocality": "Neulingen-Bauschlott",
            "addressCountry": "DE"
        }
    }
    </script>
</main>
<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>