<?php
/**
 * Handle admin/frontend scripts
 *
 * @class    WC_Checkout_Terms_Popup_Scripts
 * @version  1.0.0
 * @category Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Checkout_Terms_Popup_Scripts Class.
 */
class WC_Checkout_Terms_Popup_Scripts {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontend_scripts' ) );
	}

	/**
	 * Enqueue admin scripts.
	 */
	public static function admin_scripts() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		$suffix    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register scripts.
		wp_register_script( 'wc-admin-terms-conditions-popup', plugins_url( '/assets/js/admin/admin' . $suffix . '.js', plugin_dir_path( __FILE__ ) ), array( 'jquery' ), WC_Checkout_Terms_Popup::VERSION );

		// WooCommerce admin pages.
		if ( in_array( $screen_id, wc_get_screen_ids() ) ) {
			wp_enqueue_script( 'wc-admin-terms-conditions-popup' );
		}
	}

	/**
	 * Enqueue frontend scripts.
	 */
	public static function frontend_scripts() {
		$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$assets_path = str_replace( array( 'http:', 'https:' ), '', WC_Checkout_Terms_Popup::plugin_url() ) . '/assets/';

		// Register styles.
		wp_register_style( 'wc-checkout-terms-popup', $assets_path . 'css/wc-checkout-terms-popup.css', array(), WC_Checkout_Terms_Popup::VERSION );

		// Register scripts.
		wp_register_script( 'wc-checkout-terms-popup', $assets_path . 'js/frontend/wc-checkout-terms-popup' . $suffix . '.js', array( 'jquery', 'thickbox' ), WC_Checkout_Terms_Popup::VERSION, true );

		// Enqueue checkout scripts.
		if ( is_checkout() && wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) {
			wp_enqueue_style( 'wc-checkout-terms-popup' );
			wp_enqueue_script( 'wc-checkout-terms-popup' );

			// Init Thickbox.
			add_thickbox();
		}
	}
}

WC_Checkout_Terms_Popup_Scripts::init();
