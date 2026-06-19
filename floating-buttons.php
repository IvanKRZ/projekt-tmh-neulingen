<nav class="floating-buttons" aria-label="Schnellzugriff">
    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>"
       class="floating-btn"
       aria-label="Zur Kontaktseite">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/contact-icon.png"
             alt=""
             aria-hidden="true">
    </a>
    <div class="floating-divider" aria-hidden="true"></div>
    <a href="<?php echo esc_url(get_permalink(get_page_by_path('kalender'))); ?>"
       class="floating-btn"
       aria-label="Zur Kalenderseite">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/calendar-icon.png"
             alt=""
             aria-hidden="true">
    </a>
</nav>