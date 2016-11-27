jQuery( function ( $ ) {

	// Terms and Conditions Popup.
	$( 'select#woocommerce_terms_page_id' ).change( function() {
		if ( $( this ).val() ) {
			$( '#woocommerce_terms_popup_footer_button' ).closest( 'tr' ).show();
		} else {
			$( '#woocommerce_terms_popup_footer_button' ).closest( 'tr' ).hide();
		}
	}).change();
});
