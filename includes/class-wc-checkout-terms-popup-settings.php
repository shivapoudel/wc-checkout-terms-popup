<?php
/**
 * WooCommerce Checkout Terms Popup Settings
 *
 * @class    WC_Checkout_Terms_Popup_Settings
 * @version  1.0.0
 * @category Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Checkout_Terms_Popup_Settings Class.
 */
class WC_Checkout_Terms_Popup_Settings {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_filter( 'woocommerce_get_settings_checkout', array( __CLASS__, 'checkout_settings' ) );
	}

	/**
	 * Add new settings to the checkout settings page.
	 *
	 * @param  mixed $settings
	 * @return mixed
	 * @since  1.0.0
	 */
	public static function checkout_settings( $settings ) {
		$new_settings = array(
			array(
				'title'           => __( 'Terms and conditions popup', 'wc-checkout-terms-popup' ),
				'desc'            => __( 'Add an "Agree" and "Decline" button', 'wc-checkout-terms-popup' ),
				'id'              => 'woocommerce_terms_popup_footer_button',
				'default'         => 'no',
				'type'            => 'checkbox',
				'checkboxgroup'   => 'start',
				'show_if_checked' => 'option',
				'desc_tip'        => __( 'When launching popup, display footer with "Agree" and "Decline" button.', 'wc-checkout-terms-popup' ),
			),
			array(
				'desc'            => __( 'Force users scroll to the end to enable "Agree" button', 'wc-checkout-terms-popup' ),
				'id'              => 'woocommerce_terms_popup_force_scroll_end',
				'default'         => 'no',
				'type'            => 'checkbox',
				'checkboxgroup'   => 'end',
				'show_if_checked' => 'yes',
			),
		);

		$offset = isset( $settings['unforce_ssl_checkout'] ) ? 2 : 1;

		// Add new settings to the existing ones.
		foreach ( $settings as $key => $setting ) {
			if ( isset( $setting['id'] ) && 'woocommerce_terms_page_id' == $setting['id'] ) {
				array_splice( $settings, $key + $offset, 0, $new_settings );
				break;
			}
		}

		return $settings;
	}
}

WC_Checkout_Terms_Popup_Settings::init();
