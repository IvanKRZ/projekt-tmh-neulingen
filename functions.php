<?php

function tmh_neulingen_style() {
    $version = wp_get_theme()->get( 'Version' );
    wp_enqueue_style(
        'my-style',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        $version
    );
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        filemtime(get_template_directory() . '/assets/js/main.js'),
        true
    );
}

function body_css_class($classes) {
    if (is_front_page())        { $classes[] = 'front-page'; }
    if (is_page('contact'))     { $classes[] = 'contact-page'; }
    if (is_page('aktuelles'))   { $classes[] = 'aktuelles-page'; }
    if (is_page('training'))    { $classes[] = 'training-page'; }
    if (is_page('uberuns'))     { $classes[] = 'uberuns-page'; }
    if (is_page('galerie'))     { $classes[] = 'galerie-page'; }
    if (is_page('kalender'))    { $classes[] = 'kalender-page'; }
    return $classes;
}

function handle_contact_form() {
    $vorname  = sanitize_text_field($_POST['vorname']);
    $nachname = sanitize_text_field($_POST['nachname']);
    $email    = sanitize_email($_POST['email']);
    $message  = sanitize_textarea_field($_POST['message']);

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $vorname . ' ' . $nachname . ' <' . $email . '>',
    ];
    $body = "Von: $vorname $nachname\nEmail: $email\n\nNachricht:\n$message";
    $sent = wp_mail(
        'szkepy@gmail.com',
        'Neue Nachricht von ' . $vorname . ' ' . $nachname,
        $body,
        $headers
    );
    $status = $sent ? 'success' : 'error';
    wp_redirect(add_query_arg('status', $status, get_permalink(get_page_by_path('contact'))));
    exit;
}

function my_theme_fonts() {
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poetsen+One&display=swap',
        array(),
        null
    );
}

function theme_setup() {
    add_theme_support('post-thumbnails');
}

function register_gallery_post_type() {
    register_post_type('gallery_image', [
        'labels' => [
            'name'          => 'Galerie fotos',
            'singular_name' => 'Galerie foto',
            'add_new_item'  => 'Neues Bild hinzufügen',
            'edit_item'     => 'Bild bearbeiten',
        ],
        'public'        => true,
        'has_archive'   => false,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-format-gallery',
        'supports'      => ['title', 'thumbnail', 'editor'],
    ]);
}

function enqueue_lightbox() {
    if (!is_page('galerie')) return;
    wp_enqueue_style(
        'lightbox-css',
        get_template_directory_uri() . '/assets/css/lightbox.css',
        [],
        filemtime(get_template_directory() . '/assets/css/lightbox.css')
    );
    wp_enqueue_script(
        'lightbox',
        get_template_directory_uri() . '/assets/js/lightbox.js',
        ['jquery'],
        '2.11.4',
        true
    );
    wp_add_inline_script('lightbox',
        "lightbox.option({'resizeDuration':200,'wrapAround':true,'albumLabel':'%1 / %2'});"
    );
}

function register_gallery_taxonomy() {
    register_taxonomy('gallery_album', 'gallery_image', [
        'labels' => [
            'name'          => 'Alben',
            'singular_name' => 'Album',
            'add_new_item'  => 'Neues Album',
            'edit_item'     => 'Album bearbeiten',
        ],
        'hierarchical' => true,
        'public'       => true,
        'show_ui'      => true,
        'show_in_menu' => true,
    ]);
}

add_theme_support('title-tag');
add_theme_support('custom-logo');

register_nav_menus([
    'nav-left'   => 'Navigation Links',
    'nav-right'  => 'Navigation Rechts',
    'nav-mobile' => 'Mobile Navigation',
]);

class TMH_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {}
    public function end_lvl(&$output, $depth = 0, $args = null) {}
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<div role="listitem"><a class="menu-button" href="' . esc_url($item->url) . '" aria-label="' . esc_attr($item->title) . '">' . esc_html($item->title) . '</a></div>';
    }
    public function end_el(&$output, $item, $depth = 0, $args = null) {}
}

class TMH_Mobile_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {}
    public function end_lvl(&$output, $depth = 0, $args = null) {}
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
    }
    public function end_el(&$output, $item, $depth = 0, $args = null) {}
}

function tmh_customizer_settings($wp_customize) {
    $wp_customize->add_section('tmh_donation', [
        'title'    => 'Spendenbereich',
        'priority' => 40,
    ]);
    $wp_customize->add_setting('gooding_iframe_src', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('gooding_iframe_src', [
        'label'       => 'Gooding Widget URL',
        'description' => 'Nur die src-URL aus dem iframe-Code einfügen',
        'section'     => 'tmh_donation',
        'type'        => 'url',
    ]);
}

function tmh_register_training_cpt() {
    register_post_type('training', [
        'labels' => [
            'name'          => 'Trainings',
            'singular_name' => 'Training',
            'add_new_item'  => 'Neues Training',
            'edit_item'     => 'Training bearbeiten',
            'not_found'     => 'Keine Trainings gefunden',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'menu_icon'    => 'dashicons-pets',
        'rewrite'      => false,
    ]);
}

function tmh_allow_iframe($tags, $context) {
    if ($context === 'post') {
        $tags['iframe'] = [
            'src'               => true, 'title'            => true,
            'name'              => true, 'id'               => true,
            'sandbox'           => true, 'scrolling'        => true,
            'frameborder'       => true, 'allowtransparency' => true,
            'allowfullscreen'   => true, 'width'            => true,
            'height'            => true, 'class'            => true,
            'style'             => true,
        ];
    }
    return $tags;
}

function tmh_register_nav_menus() {
    register_nav_menus([
        'training_page_nav' => 'Training Seite Navigation',
    ]);
}

function tmh_homepage_customizer($wp_customize) {
    $wp_customize->add_section('tmh_homepage', [
        'title'    => 'Startseite',
        'priority' => 30,
    ]);
    $wp_customize->add_setting('homepage_hero_title', [
        'default'           => 'TMH Neulingen',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('homepage_hero_title', [
        'label'   => 'Hero-Titel',
        'section' => 'tmh_homepage',
        'type'    => 'text',
    ]);
    $wp_customize->add_setting('homepage_hero_image', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'homepage_hero_image', [
        'label'   => 'Hero-Bild',
        'section' => 'tmh_homepage',
    ]));
    $wp_customize->add_setting('homepage_show_latest_aktuelles', [
        'default'           => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('homepage_show_latest_aktuelles', [
        'label'   => 'Neuesten Aktuelles-Beitrag anzeigen',
        'section' => 'tmh_homepage',
        'type'    => 'checkbox',
    ]);
}

// ============ KALENDER CPT ============
function tmh_register_kalender_cpt() {
    register_post_type('tmh_event', [
        'labels' => [
            'name'          => 'Termine',
            'singular_name' => 'Termin',
            'add_new_item'  => 'Neuen Termin hinzufügen',
            'edit_item'     => 'Termin bearbeiten',
            'not_found'     => 'Keine Termine gefunden',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'supports'     => ['title'],
        'menu_icon'    => 'dashicons-calendar-alt',
        'rewrite'      => false,
    ]);
}
add_action('init', 'tmh_register_kalender_cpt');

function tmh_event_meta_box_register() {
    add_meta_box('tmh_event_details', 'Termindetails', 'tmh_event_meta_box_html', 'tmh_event', 'normal', 'high');
}
add_action('add_meta_boxes', 'tmh_event_meta_box_register');

function tmh_event_meta_box_html($post) {
    wp_nonce_field('tmh_event_save', 'tmh_event_nonce');
    $day     = get_post_meta($post->ID, '_event_day',         true);
    $start   = get_post_meta($post->ID, '_event_start',       true);
    $end     = get_post_meta($post->ID, '_event_end',         true);
    $color   = get_post_meta($post->ID, '_event_color',       true);
    $trainer = get_post_meta($post->ID, '_event_trainer',     true);
    $desc    = get_post_meta($post->ID, '_event_description', true);

    $days = [
        'mon' => 'Montag',    'tue' => 'Dienstag', 'wed' => 'Mittwoch',
        'thu' => 'Donnerstag','fri' => 'Freitag',  'sat' => 'Samstag', 'sun' => 'Sonntag',
    ];
    $colors = [
        'yellow'           => 'Gelb',
        'pink'             => 'Pink',
        'blue'             => 'Blau',
        'green'            => 'Grün',
        'gray'             => 'Grau',
        'dusty-rose'       => 'Dusty Rose',
        'pastel-lemon'     => 'Pastell Zitrone',
        'light-lavender'   => 'Lavendel',
        'sky-blue'         => 'Himmelblau',
        'fresh-sage-green' => 'Salbeigrün',
        'warm-tangerine'   => 'Mandarine',
        'muted-rose'       => 'Altrosa',
        'khaki-green'      => 'Khaki',
        'seafoam-mint'     => 'Meeresminze',
    ];

    $times = [];
    for ($h = 8; $h <= 22; $h++) {
        $times[] = sprintf('%02d:00', $h);
        $times[] = sprintf('%02d:30', $h);
    }
    ?>
    <table class="form-table">
        <tr>
            <th><label for="event_day">Tag</label></th>
            <td>
                <select name="event_day" id="event_day">
                    <option value="">– wählen –</option>
                    <?php foreach ($days as $val => $label) : ?>
                        <option value="<?php echo esc_attr($val); ?>" <?php selected($day, $val); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="event_start">Beginn</label></th>
            <td>
                <select name="event_start" id="event_start">
                    <?php foreach ($times as $t) : ?>
                        <option value="<?php echo esc_attr($t); ?>" <?php selected($start, $t); ?>><?php echo esc_html($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="event_end">Ende</label></th>
            <td>
                <select name="event_end" id="event_end">
                    <?php foreach ($times as $t) : ?>
                        <option value="<?php echo esc_attr($t); ?>" <?php selected($end, $t); ?>><?php echo esc_html($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="event_color">Farbe</label></th>
            <td>
                <select name="event_color" id="event_color">
                    <?php foreach ($colors as $val => $label) : ?>
                        <option value="<?php echo esc_attr($val); ?>" <?php selected($color, $val); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="event_trainer">Trainer/in</label></th>
            <td>
                <input type="text" name="event_trainer" id="event_trainer"
                       value="<?php echo esc_attr($trainer); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="event_description">Beschreibung</label></th>
            <td>
                <textarea name="event_description" id="event_description"
                          rows="5" style="width:100%;"><?php echo esc_textarea($desc); ?></textarea>
                <p class="description">Optional — erscheint beim Anklicken des Termins im Kalender.</p>
            </td>
        </tr>
    </table>
    <?php
}

function tmh_event_save($post_id) {
    if (!isset($_POST['tmh_event_nonce']) || !wp_verify_nonce($_POST['tmh_event_nonce'], 'tmh_event_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    foreach ([
        '_event_day'     => 'event_day',
        '_event_start'   => 'event_start',
        '_event_end'     => 'event_end',
        '_event_color'   => 'event_color',
        '_event_trainer' => 'event_trainer',
    ] as $meta => $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta, sanitize_text_field($_POST[$field]));
        }
    }

    if (isset($_POST['event_description'])) {
        update_post_meta($post_id, '_event_description', sanitize_textarea_field($_POST['event_description']));
    }
}

// ===== KALENDER EINSTELLUNGEN =====

function tmh_kalender_settings_menu() {
    add_submenu_page(
        'edit.php?post_type=tmh_event',
        'Kalender Einstellungen',
        'Einstellungen',
        'manage_options',
        'tmh-kalender-settings',
        'tmh_kalender_settings_page'
    );
}
add_action('admin_menu', 'tmh_kalender_settings_menu');

function tmh_kalender_settings_page() {
    if (isset($_POST['tmh_kalender_save'])) {
        check_admin_referer('tmh_kalender_settings');
        update_option('tmh_kalender_header_bg', sanitize_hex_color($_POST['tmh_kalender_header_bg'] ?? '#FF6EB5'));
        update_option('tmh_kalender_header_fg', sanitize_hex_color($_POST['tmh_kalender_header_fg'] ?? '#ffffff'));
        echo '<div class="notice notice-success"><p>Gespeichert!</p></div>';
    }
    $header_bg = get_option('tmh_kalender_header_bg', '#FF6EB5');
    $header_fg = get_option('tmh_kalender_header_fg', '#ffffff');
    ?>
    <div class="wrap">
        <h1>Kalender Einstellungen</h1>
        <form method="post">
            <?php wp_nonce_field('tmh_kalender_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="tmh_header_bg">Kopfzeile Hintergrundfarbe</label></th>
                    <td>
                        <input type="color" id="tmh_header_bg" name="tmh_kalender_header_bg"
                               value="<?php echo esc_attr($header_bg); ?>">
                        <span style="margin-left:8px;font-family:monospace;"><?php echo esc_html($header_bg); ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="tmh_header_fg">Kopfzeile Textfarbe</label></th>
                    <td>
                        <input type="color" id="tmh_header_fg" name="tmh_kalender_header_fg"
                               value="<?php echo esc_attr($header_fg); ?>">
                        <span style="margin-left:8px;font-family:monospace;"><?php echo esc_html($header_fg); ?></span>
                    </td>
                </tr>
            </table>
            <p>
                <strong>Vorschau:</strong><br>
                <span style="display:inline-block;margin-top:6px;padding:0.6rem 2rem;border-radius:6px;
                             background:<?php echo esc_attr($header_bg); ?>;
                             color:<?php echo esc_attr($header_fg); ?>;
                             font-weight:bold;letter-spacing:3px;">
                    Trainingszeiten
                </span>
            </p>
            <input type="submit" name="tmh_kalender_save" class="button-primary" value="Speichern">
        </form>
    </div>
    <?php
}

// ============ XML SITEMAP ============

function tmh_sitemap_rewrite_rule() {
    add_rewrite_rule( '^sitemap\.xml$', 'index.php?tmh_sitemap=1', 'top' );
}
add_action( 'init', 'tmh_sitemap_rewrite_rule' );

function tmh_sitemap_query_var( $vars ) {
    $vars[] = 'tmh_sitemap';
    return $vars;
}
add_filter( 'query_vars', 'tmh_sitemap_query_var' );

function tmh_sitemap_output() {
    if ( ! get_query_var( 'tmh_sitemap' ) ) return;

    header( 'Content-Type: application/xml; charset=UTF-8' );

    $excluded_slugs = [ 'impressum', 'datenschutz', 'datenschutzerklarung' ];
    $urls = [];

    $pages = get_posts([
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ]);
    foreach ( $pages as $page ) {
        if ( in_array( $page->post_name, $excluded_slugs, true ) ) continue;
        $urls[] = [
            'loc'     => get_permalink( $page ),
            'lastmod' => get_the_modified_date( 'Y-m-d', $page ),
            'prio'    => ( $page->ID === (int) get_option( 'page_on_front' ) ) ? '1.0' : '0.8',
        ];
    }

    $posts = get_posts([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ]);
    foreach ( $posts as $post ) {
        $urls[] = [
            'loc'     => get_permalink( $post ),
            'lastmod' => get_the_modified_date( 'Y-m-d', $post ),
            'prio'    => '0.6',
        ];
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ( $urls as $url ) {
        echo "\t<url>\n";
        echo "\t\t<loc>"     . esc_url( $url['loc'] )      . "</loc>\n";
        echo "\t\t<lastmod>" . esc_html( $url['lastmod'] )  . "</lastmod>\n";
        echo "\t\t<priority>". esc_html( $url['prio'] )     . "</priority>\n";
        echo "\t</url>\n";
    }
    echo '</urlset>';
    exit;
}
add_action( 'template_redirect', 'tmh_sitemap_output' );

// ============ HOOKS ============

add_action('save_post_tmh_event', 'tmh_event_save');
add_action('customize_register', 'tmh_homepage_customizer');
add_filter('wpseo_accessible_post_types', function($post_types) {
    $post_types['training'] = 'training';
    return $post_types;
});
add_action('init', 'tmh_register_nav_menus');
add_action('customize_register', 'tmh_customizer_settings');
add_filter('wp_kses_allowed_html', 'tmh_allow_iframe', 10, 2);
add_action('init', 'tmh_register_training_cpt');
add_action('init', 'register_gallery_taxonomy');
add_action('after_setup_theme', 'theme_setup');
add_action('wp_enqueue_scripts', 'enqueue_lightbox');
add_action('init', 'register_gallery_post_type');
add_action('wp_enqueue_scripts', 'my_theme_fonts');
add_action('admin_post_nopriv_contact_form', 'handle_contact_form');
add_action('admin_post_contact_form', 'handle_contact_form');
add_filter('body_class', 'body_css_class');
add_action('wp_enqueue_scripts', 'tmh_neulingen_style');

add_filter('robots_txt', function($output) {
    return "User-agent: *\n"
        . "Disallow: /wp-admin/\n"
        . "Disallow: /wp-includes/\n"
        . "Disallow: /wp-login.php\n"
        . "Disallow: /wp-register.php\n"
        . "Disallow: /xmlrpc.php\n"
        . "Disallow: /wp-json/\n"
        . "Disallow: /?s=\n"
        . "Disallow: /search/\n"
        . "Disallow: /trackback/\n"
        . "Disallow: /feed/\n"
        . "Disallow: /comments/feed/\n"
        . "Disallow: /?p=\n"
        . "Allow: /wp-admin/admin-ajax.php\n\n"
        . "User-agent: GPTBot\n"
        . "Disallow: /\n\n"
        . "User-agent: ChatGPT-User\n"
        . "Disallow: /\n\n"
        . "User-agent: CCBot\n"
        . "Disallow: /\n\n"
        . "User-agent: anthropic-ai\n"
        . "Disallow: /\n\n"
        . "User-agent: Claude-Web\n"
        . "Disallow: /\n\n"
        . "Sitemap: " . home_url('/sitemap.xml') . "\n";
}, PHP_INT_MAX);