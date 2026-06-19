<?php

function tmh_neulingen_style() {
    $version = wp_get_theme()->get( 'Version' );

    // Loading CSS
    wp_enqueue_style(
        'my-style',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        $version
    );

    // Loading JS
    wp_enqueue_script(
        'my-script',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        '1.0',
        true
    );
}

function body_css_class($classes) {
    if (is_front_page()) {
        $classes[] = 'front-page';
    }
    if (is_page('contact')) {
        $classes[] = 'contact-page';
    }
    if (is_page('aktuelles')) {
        $classes[] = 'aktuelles-page';
    }
    if (is_page('training')) {
        $classes[] = 'training-page';
    }
    if (is_page('uberuns')) {
        $classes[] = 'uberuns-page';
    }
    if (is_page('galerie')) {
        $classes[] = 'galerie-page';
    }
    if (is_page('kalender')) {
        $classes[] = 'kalender-page';
    }
    return $classes;
}

function handle_contact_form() {
    $vorname    = sanitize_text_field($_POST['vorname']);
    $nachname    = sanitize_text_field($_POST['nachname']);
    $email   = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $body = "Von: $vorname $nachname\nEmail: $email\n\nNachricht:\n$message";

    $sent = wp_mail(
        'szkepy@gmail.com',
        'Neue Nachricht von ' . $vorname . ' ' . $nachname,
        $body,
        $headers
    );

    $status = $sent ? 'success' : 'error';

    wp_redirect(
        add_query_arg('status', $status, get_permalink(get_page_by_path('contact')))
    );
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
    wp_enqueue_style(
        'lightbox',
        get_template_directory_uri() . '/assets/css/lightbox.css',
        [],
        '2.11.4'
    );
    wp_enqueue_script(
        'lightbox',
        get_template_directory_uri() . '/assets/js/lightbox.js',
        ['jquery'],
        '2.11.4',
        true
    );
}

function lightbox_config() {
    if (is_page('galerie')) : ?>
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': '%1 / %2'
        });
    </script>
    <?php endif;
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
            'src'              => true,
            'title'            => true,
            'name'             => true,
            'id'               => true,
            'sandbox'          => true,
            'scrolling'        => true,
            'frameborder'      => true,
            'allowtransparency' => true,
            'allowfullscreen'  => true,
            'width'            => true,
            'height'           => true,
            'class'            => true,
            'style'            => true,
        ];
    }
    return $tags;
}

add_action('customize_register', 'tmh_customizer_settings');
add_filter('wp_kses_allowed_html', 'tmh_allow_iframe', 10, 2);
add_action('init', 'tmh_register_training_cpt');
add_action('customize_register', 'tmh_customizer_settings');
add_action('wp_footer', 'lightbox_config');
add_action('init', 'register_gallery_taxonomy');
add_action('after_setup_theme', 'theme_setup');
add_action('wp_enqueue_scripts', 'enqueue_lightbox');
add_action('init', 'register_gallery_post_type');
add_action( 'wp_enqueue_scripts', 'my_theme_fonts' );
add_action('admin_post_nopriv_contact_form', 'handle_contact_form');
add_action('admin_post_contact_form', 'handle_contact_form');
add_filter('body_class', 'body_css_class');
add_action( 'wp_enqueue_scripts', 'tmh_neulingen_style' );

?>