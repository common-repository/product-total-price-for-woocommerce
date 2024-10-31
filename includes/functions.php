<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( 'Do not open this file directly.' );
}

add_filter( 'wp_localize_wcptp_data', 'wcptp_localize_data', 10, 2 );

if( !function_exists( 'wcptp_localize_data' ) ){
    /**
     * Localize data
     * 
     * @since 1.1.1
     */
    function wcptp_localize_data( $wcptp_data, $product ){
        $wcptp_data['price'] = wcptp_get_price( $product );
        $wcptp_data['regular_price'] = wcptp_get_price( $product );
        return $wcptp_data;
    }
}

if( !function_exists( 'wcptp_get_price' ) ){
    /**
     * Get product prices
     * 
     * @since 1.1.1
     */
    function wcptp_get_price( $product ) {
        if ( $product->is_type( 'variable' ) ) {
            $prices = $product->get_variation_prices( true );
            return $prices['price'];
        }
        return $product->get_price();
    }
}

// This code block should be removed
add_filter( 'wcptp_allowed_product_type', function( $types ){
	$types[] = 'woosb';
	return $types;
}, 10 );