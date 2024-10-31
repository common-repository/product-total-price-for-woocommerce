=== Product Total Price for WooCommerce ===
Contributors: autocircle
Donate link: https://www.patreon.com/autocircle
Tags: subtotal, price sub total, price preview, dynamic price, price display, woocommerce, total price, final price, price times quantity, calculate price, price calculator
Requires at least: 4.0
Tested up to: 6.2
Stable tag: 1.1.4
Requires PHP: 5.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html	

An addon for WooCommerce that will help visitors to understand the final product price when product's quantity changes.

== Description ==

**Product Total Price for WooCommerce** is an addon for WooCommerce based site where visitors of your site can see the total price when they increase or decrease the quantity number. 

= How it works =

The plugin has build based on the concept of PnP (Plug n Play). So you don't have to worry about settings.

This plugin uses WooCommerce settings to get the pricing option, so you don't have to worry about how the price is displayed.

= Requirements =
WooCommerce 3.0 or later.

== Installation ==
1. Visit Plugins > Add New
2. Search for "Product Total Price for WooCommerce"
3. Activate "Product Total Price for WooCommerce" from your plugins page
4. That's all


== Frequently asked questions ==
= Why I don't find any settings? =
Yes! You really don't need anything for this plugin.

= Is there any shortcode?
Yes! Here is the shortcode `[WOO-TOTAL-PRICE]`

= Is there any way to hide or relocate default total price text?
Yes! There is an filter hook called `wcptp_custom_location_by_action_hook`.
This filter hook returns array of action hooks. By adding or removing hook you can easily control the location. Also you can display product total price text in multiple location by add your desired location hooks.
Example:
`
add_filter( 'wcptp_custom_location_by_action_hook', function( $hooks ){
	$hooks[] = 'your_new_location_hook'; 
	return $hooks;
} );
`

= Can I hide this Product Total Price text based of any custom condition?
Yes! There also a filter hook called `wcptp_any_other_condition`.
This filter hook return true. You can disable or hide Product Total Price text by simply returning false based on any condition.

Exxample:
`
add_filter( 'wcptp_any_other_condition', function( $bool, $product, $allowed_product_types ){
	if( condition == true ) {
		return false;
	} else {
		return true;
	}
}, 3, 10 );
`

= WPML ready? =
Yes!

= How to change the text "Total Price" to custom? =
You can paste this code to your current theme's functions.php:

`
add_filter( 'wcptp_price_html', 'wcptp_price_html' );
function wcptp_price_html( $wcptp_price_html ) {
	return str_replace( 'Total Price', 'Order Total', $wcptp_price_html );
}
`
This is an example that will change "Total Price" to "Order Total".

= Will this work with other plugins of WooCommerce? =
Yes allmost with all plugins and themes.

= This plugin currently supports only 'simple' and 'variable' product types. Is there any way to support other product types? =
Yes, that could be easily done by adding following code snippet to your theme's function.php file.
`
add_filter( 'wcptp_allowed_product_type', function( $types ){
	$types[] = 'woosb';
	return $types;
}, 10 );
`
The above code snippet will allow you to show the Total Product Price for almost any types of products.

= How to show prefix and suffix text of Total Price? 
To show text as prefix and suffix of total price there are 02 filters. Use these filters to display your desired text.
`
apply_filters( 'wcptp_prefix', __return_false() );

apply_filters( 'wcptp_suffix', __return_false() );
`
<strong>Example:</strong>
`
add_filter( 'wcptp_suffix', function(){
	return "(excl. VAT)";
});
`

== Screenshots ==

1. Screenshot 1
2. Screenshot 2

== Changelog ==
= 1.1.4 =
* Added shortcode [WOO-TOTAL-PRICE]
* Added 2 new filter hook
* Elementor supported by adding shortcode

= 1.1.3 =
* Added new filters to display prefix and suffix

= 1.1.2 =
* Fully compatible with WPC Product Bundles for WooCommerce plugin as per support issue https://wordpress.org/support/topic/variable-product-bundle/

= 1.1.1 =
Release Date: September 3rd, 2021

Added below features as per request of WordPress support topic https://wordpress.org/support/topic/variable-product-price-not-changing/

Enhancements:

* Variable product support

Bug Fixes:

* Total price preview loading automatically

= 1.1.0 =
* WooCommerce missing notice added
* Tested with WP version 5.8
* Tested with WC version 5.5.2
* Code cleanup done

= 1.0.0 =
* Initial release!

