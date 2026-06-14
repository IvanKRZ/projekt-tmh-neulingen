<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TMH - Kontakt</title>
<body <?php body_class(); ?>>
    <?php get_header(); ?>

    <section class="contact-section" aria-label="Kontanktformular und Anfahrt Informationen">
        <div class="contact-section-left">
            <div class="contact-infos">
                <h4 class="kontakt-title">Kontakt</h4>
                <p class="kontakt-data">
                    Mobil & WhatsApp <br>
                    +49 152 21690603 <br>
                    Festnetz <br>
                    +49 7237 480712 <br>
                </p>
                <br>
                <h4 class="kontakt-title">Postalische Anfragen an:</h4>
                <p clasS="kontakt-data">
                    Teamwork Mensch & Hund Neulingen e.V. <br>
                    Göbricher Straße 25 <br>
                    75245 Neulingen
                </p>
                <br>
                <h4 class="kontakt-title">Anfahrt</h4>
                <p class="kontakt-data">
                    Der Hundeplatz des TMH Neulingen befindet sich in der Hinter der Hub 2/1, 75245 Neulingen-Bauschlott, direkt oberhalb des Recyclinghofs. <br>
                    Auto: Navi-Adresse „Hinter der Hub 2/1, 75245 Neulingen“. Ausreichend Parkplätze vorhanden. <br>
                    Bus: Haltestelle „Bauschlott Schloss“, anschließend ca. 2 Minuten Fußweg Richtung Recyclinghof. <br>
                    Orientierung: Das Gelände liegt direkt oberhalb des Recyclinghofs Bauschlott.
                </p>
            </div>
            <div class="arrive-infos">

            </div>
        </div>
        <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="contact_form">
            <label for="vorname">Vorname</label>
            <input type="text" name="vorname" placeholder="">
            <label for="nachname">Nachname</label>
            <input type="text" name="nachname" placeholder="">
            <label for="email">Email</label>
            <input class="input-email" type="email" name="email" placeholder="">
            <label for="message">Kommentar oder Nachricht</label>
            <textarea name="message" placeholder=""></textarea>
            <button type="submit">Senden</button>
            <image class="contact-page-paw" src="<?php echo get_template_directory_uri(); ?>/assets/images/contact-paw.png" alt="Dekorative Hundepfote">
        </form>
    </section>

    <?php get_template_part('floating-buttons');?>     
    <?php wp_footer(); ?>
</body>