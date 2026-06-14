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
        'hierarchical' => true, // mint a kategóriák
        'public'       => true,
        'show_ui'      => true,
        'show_in_menu' => true,
    ]);
}

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