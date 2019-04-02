<?php
/**
 * Plugin Name: WooCommerce Checkout Terms Popup
 * Plugin URI: http://github.com/shivapoudel/wc-checkout-terms-popup
 * Description: WooCommerce extension to allow your Terms page to open in a popup window during the checkout process.
 * Version: 1.0.3
 * Author: Shiva Poudel
 * Author URI: http://shivapoudel.com
 * License: GPLv3 or later
 * Text Domain: wc-checkout-terms-popup
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_Checkout_Terms_Popup' ) ) :

	/**
	 * WC_Checkout_Terms_Popup class.
	 */
	class WC_Checkout_Terms_Popup {

		/**
		 * Plugin version.
	 *
		 * @var string
		 */
		const VERSION = '1.0.0';

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin.
		 */
		private function __construct() {
			// Load plugin text domain.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			// Checks with WooCommerce is installed.
			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
				$this->includes();

				// Hooks.
				add_action( 'wp_footer', array( $this, 'load_terms_popup_template' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
			}
		}

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 *
		 * Locales found in:
		 *      - WP_LANG_DIR/wc-checkout-terms-popup/wc-checkout-terms-popup-LOCALE.mo
		 *      - WP_LANG_DIR/plugins/wc-checkout-terms-popup-LOCALE.mo
		 */
		public function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'wc-checkout-terms-popup' );

			load_textdomain( 'wc-checkout-terms-popup', WP_LANG_DIR . '/wc-checkout-terms-popup/wc-checkout-terms-popup-' . $locale . '.mo' );
			load_plugin_textdomain( 'wc-checkout-terms-popup', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public static function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public static function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Includes.
		 */
		private function includes() {
			include_once dirname( __FILE__ ) . '/includes/class-wc-checkout-terms-popup-scripts.php';
			include_once dirname( __FILE__ ) . '/includes/class-wc-checkout-terms-popup-settings.php';
		}

		/**
		 * Load terms popup template.
		 */
		public function load_terms_popup_template() {
			$terms_page   = get_post( wc_get_page_id( 'terms' ) );
			$terms_button = get_option( 'woocommerce_terms_popup_footer_button' );
			$terms_scroll = get_option( 'woocommerce_terms_popup_force_scroll_end' );

			// Make sure WC terms page exists.
			if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) {
				$class_list = array( 'wc-checkout-terms-popup' );

				// Thickbox class list.
				if ( 'yes' == $terms_button ) {
					$class_list[] = 'wc-enabled-terms-button';

					if ( 'yes' == $terms_scroll ) {
						$class_list[] = 'wc-enabled-terms-scroll';
					}
				}

				// Load WC checkout terms popup template.
				wc_get_template(
					'checkout/terms-popup.php',
					array(
						'id'           => 'wc-terms-and-conditions-popup',
						'title'        => $terms_page->post_title,
						'content'      => $terms_page->post_content,
						'class_list'   => $class_list,
						'terms_button' => $terms_button,
						'terms_scroll' => $terms_scroll,
					),
					'',
					self::plugin_path() . '/templates/'
				);
			}
		}

		/**
		 * Display action links in the Plugins list table.
		 *
		 * @param  array $actions
		 * @return array
		 */
		public function plugin_action_links( $actions ) {
			$new_actions = array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout' ) . '" title="' . esc_attr( __( 'View settings', 'wc-checkout-terms-popup' ) ) . '">' . __( 'Settings', 'wc-checkout-terms-popup' ) . '</a>',
			);

			return array_merge( $new_actions, $actions );
		}

		/**
		 * WooCommerce fallback notice.
		 *
		 * @return string
		 */
		public function woocommerce_missing_notice() {
			echo '<div class="error notice is-dismissible"><p>' . sprintf( __( 'WooCommerce Terms & Conditions Popup depends on the last version of %s or later to work!', 'wc-checkout-terms-popup' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">' . __( 'WooCommerce 2.3', 'wc-checkout-terms-popup' ) . '</a>' ) . '</p></div>';
		}
	}

	add_action( 'plugins_loaded', array( 'WC_Checkout_Terms_Popup', 'get_instance' ) );

endif;
