<?php
/**
 * @version 1.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script type="text/template" id="tmpl-wcptp-total-price-template">
	<?php
		$price_label = '<span class="price-label">' . __( 'Total Price:', 'wc-total-price' ) . '</span>';
		$price_format = get_woocommerce_price_format();
		$prefix_text = apply_filters( 'wcptp_prefix', __return_false() );
		$prefix = $prefix_text ? '<span class="prefix">' . esc_html( $prefix_text ) . '</span>' : '';
		$currency_html = '<span class="currency woocommerce-Price-currencySymbol">{{{ data.currency }}}</span>';
		$price_html = '<span class="amount">{{{ data.price }}}</span>';
		$suffix_text = apply_filters( 'wcptp_suffix', __return_false() );
		$suffix = $suffix_text ? '<span class="suffix">' . esc_html( $suffix_text ) . '</span>' : '';
		ob_start();
		?>
		<span class="price product-final-price">
			<span class="woocommerce-Price-amount amount">
				<?php echo $price_label; ?>
				<?php echo $prefix; ?>
				<?php echo sprintf( $price_format, $currency_html, $price_html ); ?>
				<?php echo $suffix; ?>
			</span>
		</span>
		<?php 
		echo apply_filters( 'wcptp_price_html', ob_get_clean(), $price_format, $currency_html, $price_html );
	?>
</script>