<nav class="legal-nav-bar" aria-label="Rechtliche Navigation">
    <ul>
        <li>
            <?php
            $impressum_url = get_permalink(get_page_by_path('impressum-teamwork-mensch-hund-neulingen'));
            $is_impressum = is_page('impressum');
            ?>
            <a class="legal-button"
               href="<?php echo esc_url($impressum_url); ?>"
               aria-label="Impressum"
               <?php if ($is_impressum) echo 'aria-current="page"'; ?>>
                Impressum
            </a>
        </li>
        <li>
            <?php
            $datenschutz_url = get_permalink(get_page_by_path('datenschutz'));
            $is_datenschutz = is_page('datenschutz');
            ?>
            <a class="legal-button"
               href="<?php echo esc_url($datenschutz_url); ?>"
               aria-label="Datenschutz"
               <?php if ($is_datenschutz) echo 'aria-current="page"'; ?>>
                Datenschutz
            </a>
        </li>
    </ul>
</nav>