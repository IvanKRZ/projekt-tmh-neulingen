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
    return $classes;
}

add_filter('body_class', 'body_css_class');
add_action( 'wp_enqueue_scripts', 'tmh_neulingen_style' );

?>