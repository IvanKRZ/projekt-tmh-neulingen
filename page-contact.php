<?php
/**
 * Template Name: Kontakt
 */
get_header();
?>

<main id="main-content">
    <h1 style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">Kontakt & Anfahrt – TMH Neulingen e.V.</h1>

    <section class="contact-section" aria-label="Kontaktformular und Anfahrt">
        <div class="contact-section-left">
            <div class="contact-infos">
                <?php the_content(); ?>
            </div>
            <div class="arrive-infos"></div>
        </div>

        <form method="POST"
              action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
              aria-label="Kontaktformular">
            <input type="hidden" name="action" value="contact_form">

            <label for="vorname">Vorname <span class="required-mark">*</span></label>
            <input type="text" id="vorname" name="vorname"
                   required aria-required="true"
                   autocomplete="given-name" placeholder="">

            <label for="nachname">Nachname <span class="required-mark">*</span></label>
            <input type="text" id="nachname" name="nachname"
                   required aria-required="true"
                   autocomplete="family-name" placeholder="">

            <label for="email">Email <span class="required-mark">*</span></label>
            <input class="input-email" type="email" id="email" name="email"
                   required aria-required="true"
                   autocomplete="email" placeholder="">

            <label for="message">Kommentar oder Nachricht <span class="required-mark">*</span></label>
            <textarea id="message" name="message"
                      required aria-required="true" placeholder=""></textarea>

            <div class="contact-form-footer" style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                <button type="submit">Senden</button>
                <div class="contact-datenschutz">
                    <input type="checkbox" id="datenschutz" name="datenschutz"
                           class="datenschutz-checkbox"
                           required aria-required="true">
                    <label for="datenschutz">
                        Ich stimme der <a href="<?php echo get_permalink(get_page_by_path('datenschutz'))?>" class="datenschutz-link">Datenschutzerklärung</a> zu.
                    </label>
                </div>
            </div>

            <img class="contact-page-paw"
                 src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/contact-paw.png"
                 alt="" aria-hidden="true">
        </form>
    </section>
</main>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SportsClub",
    "name": "Teamwork Mensch & Hund Neulingen e.V.",
    "url": "<?php echo esc_url(home_url('/')); ?>",
    "telephone": ["+49 152 21690603", "+49 7237 480712"],
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Göbricher Straße 25",
        "postalCode": "75245",
        "addressLocality": "Neulingen",
        "addressCountry": "DE"
    },
    "location": {
        "@type": "Place",
        "name": "Hundeplatz TMH Neulingen",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Hinter der Hub 2/1",
            "postalCode": "75245",
            "addressLocality": "Neulingen-Bauschlott",
            "addressCountry": "DE"
        }
    }
}
</script>

<?php get_template_part('floating-buttons'); ?>
<?php get_footer(); ?>