<?php
/**
 * Checkout terms and conditions popup
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/terms-popup.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<aside id="<?php echo $id ?>" style="display:none;">
	<div class="<?php echo esc_attr( implode( ' ', $class_list ) ); ?>" data-title="<?php echo $title; ?>" data-terms_button="<?php echo esc_attr( $terms_button ); ?>" data-terms_scroll="<?php echo esc_attr( $terms_scroll ); ?>">
		<div class="wc-checkout-terms-popup__content">
			<?php echo wpautop( $content ); ?>
		</div>
		<?php if ( 'yes' == $terms_button ) : ?>
			<div class="wc-checkout-terms-popup__footer woocommerce">
				<input type="submit" id="wc-checkout-terms-popup__btn" class="button alt wc-checkout-terms-popup-agree" value="<?php _e( 'Agree', 'wc-checkout-terms-popup' ); ?>" onclick="tb_remove()" />
				<input type="submit" id="wc-checkout-terms-popup__btn" class="button wc-checkout-terms-popup-decline" value="<?php _e( 'Decline', 'wc-checkout-terms-popup' ); ?>" onclick="tb_remove()" />
			</div>
		<?php endif; ?>
	</div>
	<?php do_action( 'woocommerce_checkout_after_terms_popup' ); ?>
</aside>
