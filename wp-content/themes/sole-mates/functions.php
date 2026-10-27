<?php
/**
 * Sole Mates — Storefront child theme.
 * Visual polish only: Inter type, monochrome palette, announcement bar.
 * No new features, no sliders.
 */

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'sole-mates-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'sole-mates-parent', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'sole-mates', get_stylesheet_uri(), array( 'sole-mates-parent', 'sole-mates-inter' ), '0.2.0' );
} );

add_action( 'after_setup_theme', function () {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
} );

// Black announcement bar above the header.
add_action( 'wp_body_open', function () {
    echo '<div class="sm-announcement">Every pair $1 &middot; Test mode &middot; 30-day returns, unworn</div>';
} );

// Shop page: show 12 products, 4 per row — matches the 12-product demo catalog.
add_filter( 'loop_shop_per_page', function () { return 12; } );
add_filter( 'loop_shop_columns', function () { return 4; } );
