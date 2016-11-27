jQuery( document ).ready( function( $ ) {

	var wc_checkout_terms_popup = {
		init: function() {
			$( document.body ).bind( 'updated_checkout', this.terms_update );
			$( document.body ).on( 'click', '.wc-checkout-terms-open-modal', this.terms_accept );
			$( document.body ).on( 'click', '.wc-checkout-terms-popup-agree', this.terms_checked );
			$( document.body ).on( 'click', '.wc-checkout-terms-popup-decline', this.terms_unchecked );
		},
		terms_update: function() {
			var old_tb_remove = window.tb_remove;

			// Update terms on tb remove.
			window.tb_remove = function() {
				if( $( '#TB_closeAjaxWindow' ).length > 0 ) {
					if ( 'yes' === $( '.wc-checkout-terms-popup' ).data( 'terms_button' ) ) {
						wc_checkout_terms_popup.terms_unchecked();
					} else {
						wc_checkout_terms_popup.terms_checked();
					}
				}
				old_tb_remove();
			};

			// Force user to scroll terms.
			if ( 'yes' === $( '.wc-checkout-terms-popup' ).data( 'terms_scroll' ) ) {
				$( '.wc-checkout-terms-popup-agree' ).attr( 'disabled', 'disabled' );
			}

			// Update terms and conditions href.
			var title  = $( '.wc-checkout-terms-popup' ).data( 'title' );
			var width  = wc_checkout_terms_popup.modal_dimensions( $( window ).width(), 600 );
			var height = wc_checkout_terms_popup.modal_dimensions( $( window ).height(), 550 );
			var href   = '#TB_inline?width=' + width + '&height=' + height + '&inlineId=wc-terms-and-conditions-popup';

			// Update the terms and conditions link.
			$( '.wc-terms-and-conditions a' ).attr( 'title', title ).attr( 'href', href ).attr( 'class', 'thickbox wc-checkout-terms-open-modal' );
		},
		terms_accept: function( e ) {
			e.preventDefault();

			// Force user to enable agree button only after scrolling to end of the page.
			if ( 'yes' === $( '.wc-checkout-terms-popup' ).data( 'terms_scroll' ) ) {
				$( '#TB_ajaxContent' ).scroll( function() {
					if ( $( this ).scrollTop() + $( this ).innerHeight() + 2 >= $( this )[0].scrollHeight ) {
						$( '.wc-checkout-terms-popup-agree' ).removeAttr( 'disabled' );
					}
				});
			}
		},
		terms_checked: function() {
			$( '#terms' ).prop( 'checked', true );
		},
		terms_unchecked: function() {
			$( '#terms' ).prop( 'checked', false );
		},
		modal_dimensions: function( baseDimension, maxDimension ) {
			var dim = baseDimension * 0.8;

			// Set a max thickbox size.
			if ( dim > maxDimension ) {
				dim = maxDimension;
			}

			return dim;
		}
	};

	wc_checkout_terms_popup.init();
});
