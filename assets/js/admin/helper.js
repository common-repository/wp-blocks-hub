'use strict';

(function($){

	class WPBlocksHubHelper {

		/**
		 * Show alert
		 */
		showAlert( title, content ) {

			const $dialog = $('#wpbh-dialog');

			$dialog.html( content );

			// init dialog window
			$dialog.dialog({
				title: title,
				dialogClass: 'wp-dialog',
				autoOpen: false,
				draggable: false,
				width: 'auto',
				modal: true,
				resizable: false,
				closeOnEscape: true,
				position: {
					my: "center",
					at: "center",
					of: window
				},
				'buttons': {
					'OK': function(event) {
						$dialog.dialog('close');
					}
				},
				open: function () {
					// close dialog by clicking the overlay behind it
					$('.ui-widget-overlay').on( 'click', function(){
						$dialog.dialog('close');
					})
				},
				create: function () {
					// style fix for WordPress admin
					$('.ui-dialog-titlebar-close').addClass('ui-button');
				}
			}).dialog('open');

		}

		/**
		 * Copy to clipboard
		 */
		copyToClipboard( text ) {
			let $temp = $( '<input>');
			$( 'body').append( $temp);
			$temp.val( text ).select();
			document.execCommand( 'copy');
			$temp.remove();
		}
		
	}

	window.WPBlocksHubHelper = new WPBlocksHubHelper();
	
})( window.jQuery );