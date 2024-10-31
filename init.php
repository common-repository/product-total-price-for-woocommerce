<?php
/**
 * Plugin Name: Product Total Price for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/product-total-price-for-woocommerce/
 * Description: An addon for WooCommerce that will help visitors to understand the final product price when product's quantity changes.
 * Author: autocircle
 * Author URI: https://devhelp.us/
 * 
 * Version:              1.1.4
 * Requires at least:    4.0.0
 * Tested up to:         6.2
 * WC requires at least: 3.0.0
 * WC tested up to: 	 7.5.1
 * 
 * 
 * Text Domain: wc-total-price
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Do not open this file directly.' );
}

if ( !function_exists('is_plugin_active') ){
    /**
    * Including Plugin file for security
    * Include_once
    * 
    * @since 1.0.0
    */
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! is_plugin_active('woocommerce/woocommerce.php') ) {
    add_action( 'admin_notices', 'wcptp_admin_notice_missing_main_plugin' );
    return;
}

if ( ! defined( 'WCPTP_VERSION' ) ) {
    
    define( 'WCPTP_VERSION', '1.1.4');
    
}

if ( ! defined( 'WCPTP_BASE_NAME' ) ) {
    
    /**
     * @return string woo-total-price/init.php
     */
    define( 'WCPTP_BASE_NAME', plugin_basename( __FILE__ ) );
    
}

if ( ! defined( 'WCPTP_BASE_DIR' ) ) {
    
    /**
     * Returns directory base path
     * 
     * @return string directory base path
     * 
     */
    define( 'WCPTP_BASE_DIR', plugin_dir_path( __FILE__ ) );
    
}

if ( ! defined( 'WCPTP_BASE_URL' ) ) {
    
    /**
     * Returns  Directory url
     * 
     * @return string Directory url
     */    
    define( 'WCPTP_BASE_URL', plugins_url() . '/'. plugin_basename( dirname( __FILE__ ) ) . '/' );
    
}

if ( ! function_exists( 'wcptp_admin_notice_missing_main_plugin' ) ){
    /**
     * If WooComerce not activated then show a warning message
     * 
     * @since 1.1.0
     * @return void
     */
    function wcptp_admin_notice_missing_main_plugin(){
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

           $message = sprintf(
                   esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'wc-total-price' ),
                   '<strong>' . esc_html__( 'Product Total Price for WooCommerce', 'wc-total-price' ) . '</strong>',
                   '<strong><a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="_blank">' . esc_html__( 'WooCommerce', 'woocommerce' ) . '</a></strong>'
           );

           printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );

    }
}

class WCPTP {
    protected static $_instance = null;
        
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct() {
        add_action( 'init', function(){
            load_plugin_textdomain( 'wc-total-price', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        } );
        
        add_action( 'woocommerce_loaded', array( $this, 'wcptp_init' ) );
    }
    
    public function wcptp_init() {
        if ( ! is_admin() ) {
                include_once untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/includes/functions.php';

                $location_hooks = [ 'woocommerce_single_product_summary' ];

                $location_hooks = apply_filters( 'wcptp_custom_location_by_action_hook', $location_hooks );
                if ( is_array( $location_hooks ) ) {
                    foreach( $location_hooks as $ahook ) {
                        add_action( $ahook, array( $this, 'wcptp_total_product_price_html' ), 31 );
                    }
                }

                add_shortcode( 'WOO-TOTAL-PRICE', array( $this, 'wcptp_total_product_price_html' ) );

                add_action( 'wp_enqueue_scripts', array( $this, 'load_script' ), 5 );
        }
    }
    
    public function wcptp_total_product_price_html(){
        global $product;

        $allowed_product_types = apply_filters( 'wcptp_allowed_product_type', array( 'simple', 'variable' ), $product );

        $other_conditions = apply_filters( 'wcptp_any_other_condition', __return_true(), $product, $allowed_product_types );
        
        if( $product->is_type( $allowed_product_types ) && $other_conditions ){
            echo self::total_price_div();
        }
    }
    
    public static function total_price_div(){
        return '<span class="wcptp-total-price"></span>';
    }


    public function load_script(){
        if ( ! is_single() ) { return; }
        
        global $post;
        $product = wc_get_product( $post->ID );
        
        if ( ! empty( $product ) ) {
            
            wp_register_script( 'attr_change_script', plugin_dir_url( __FILE__ ) . 'assets/js/attrchange.js', array( 'jquery' ), WCPTP_VERSION, true );
            wp_enqueue_script( 'attr_change_script' );
            wp_register_script( 'wcptp_script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery', 'wp-util', 'wc-add-to-cart-variation' ), WCPTP_VERSION, true );
            wp_enqueue_script( 'wcptp_script' );
            $wcptp_data = array(
                'precision' 			=> wc_get_price_decimals(),
				'thousand_separator' 	=> wc_get_price_thousand_separator(),
				'decimal_separator'  	=> wc_get_price_decimal_separator(),
				'currency_symbol' 		=> get_woocommerce_currency_symbol(),
				'product_type'			=> $product->get_type(),
				'price'					=> $product->get_price()
            );
            wp_localize_script( 'wcptp_script', 'wcptp_data', apply_filters( 'wp_localize_wcptp_data', $wcptp_data, $product ) );
            $wcptp_tempates_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/';
			$wcptp_price_template = apply_filters( 'wcptp_price_total_template_file', 'price-total.php', $product );
			wc_get_template( $wcptp_price_template, array(), '', $wcptp_tempates_path );
        }
    }
    
}
WCPTP::instance();