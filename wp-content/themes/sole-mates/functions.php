<?php
/**
 * Sole Mates — Storefront child theme.
 * Keep this deliberately thin: the demo is about a vanilla
 * WordPress + WooCommerce store, not a custom theme.
 */

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'sole-mates-parent', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'sole-mates', get_stylesheet_uri(), array( 'sole-mates-parent' ), '0.1.0' );
} );

add_action( 'after_setup_theme', function () {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
} );

// Shop page: show 12 products, 3 per row — matches the 12-product demo catalog.
add_filter( 'loop_shop_per_page', function () { return 12; } );
add_filter( 'loop_shop_columns', function () { return 3; } );
