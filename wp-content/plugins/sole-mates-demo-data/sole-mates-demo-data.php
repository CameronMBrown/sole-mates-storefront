<?php
/**
 * Plugin Name: Sole Mates Demo Data
 * Description: Creates the 12-product Sole Mates catalog (variable products, $1 each) from data/products.json on activation. Deactivate / delete to remove the demo data hook — products themselves are normal WooCommerce products and can be deleted in Products > All Products.
 * Version: 0.1.1
 * Author: WSI Hackathon Team
 */

if ( ! defined( 'ABSPATH' ) ) exit;

register_activation_hook( __FILE__, 'sole_mates_create_demo_products' );

function sole_mates_create_demo_products() {
    if ( ! class_exists( 'WooCommerce' ) ) return;

    // When installed in WordPress, this file is at
    // wp-content/plugins/sole-mates-demo-data/sole-mates-demo-data.php,
    // so the data file is NOT 3 levels up in the repo — it must be
    // copied to wp-content/sole-mates-data/products.json (see README).
    // Check that location first, then a few other likely locations.
    $candidates = array(
        WP_CONTENT_DIR . '/sole-mates-data/products.json',
        WP_CONTENT_DIR . '/data/products.json',
        dirname( __FILE__ ) . '/data/products.json',
        dirname( __FILE__, 3 ) . '/data/products.json',
    );
    $json_path = '';
    foreach ( $candidates as $candidate ) {
        if ( file_exists( $candidate ) ) { $json_path = $candidate; break; }
    }
    if ( ! $json_path ) {
        error_log( 'Sole Mates Demo Data: products.json not found. Tried: ' . implode( ', ', $candidates ) );
        return;
    }

    $data = json_decode( file_get_contents( $json_path ), true );
    if ( empty( $data['products'] ) ) return;

    foreach ( $data['products'] as $item ) {
        if ( wc_get_product_id_by_sku( $item['sku'] ) ) continue; // already created

        $term = get_term_by( 'name', $item['type'], 'product_cat' );
        $cat_id = $term ? (int) $term->term_id : 0;
        if ( ! $cat_id ) {
            $new = wp_insert_term( $item['type'], 'product_cat' );
            $cat_id = is_wp_error( $new ) ? 0 : (int) $new['term_id'];
        }

        $size_attr = new WC_Product_Attribute();
        $size_attr->set_name( 'Size' );
        $size_attr->set_options( $item['sizes'] );
        $size_attr->set_visible( true );
        $size_attr->set_variation( true );

        $colour_attr = new WC_Product_Attribute();
        $colour_attr->set_name( 'Colour' );
        $colour_attr->set_options( array( $item['colour'] ) );
        $colour_attr->set_visible( true );
        $colour_attr->set_variation( false );

        $product = new WC_Product_Variable();
        $product->set_name( $item['name'] );
        $product->set_sku( $item['sku'] );
        $product->set_description( $item['desc'] . ' Material: ' . $item['material'] . ' Fit: ' . $item['fit'] . ' Returns: ' . $item['returns'] );
        $product->set_short_description( $item['short'] );
        $product->set_regular_price( '1' );
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'visible' );
        if ( $cat_id ) $product->set_category_ids( array( $cat_id ) );
        $product->set_attributes( array( $size_attr, $colour_attr ) );
        $product->save();

        foreach ( $item['sizes'] as $size ) {
            $variation = new WC_Product_Variation();
            $variation->set_parent_id( $product->get_id() );
            $variation->set_attributes( array( 'size' => $size ) );
            $variation->set_regular_price( '1' );
            $variation->set_status( 'publish' );
            $variation->set_manage_stock( true );
            $variation->set_stock_quantity( 10 );
            $variation->set_stock_status( 'instock' );
            $variation->save();
        }
    }
}
