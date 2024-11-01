'use strict';

(function($){

	class WPBlocksHubMyBlocks {

		constructor() {
	
			// load data from the hub and then setup layout
			this.loadHub();

		}

		/**
		 * Load Hub Data
		 */
		loadHub() {

			// load data
			let dataLoaded = new Promise((resolve, reject) => {

				$.ajax({
					url: ajaxurl,
					type: "POST",
					dataType: 'json',
					data: {
						'action' : `${wpbhVars.prefix}_load_my_blocks`,
						'nonce' : wpbhVars.ajaxNonce
					},
					onError: () => {
						reject();
					},
					complete: ( data, status ) => {

						if( status == 'error' ) {
							reject();
						} else {
							resolve();
						}

					},
					success: ( answer ) => {

						if( answer.success === true ) {

							$('.hub-loading-indicator').remove();

							$('#wpbh-hub-blocks').html( answer.data.blocksHTML );

							resolve();

						} else {
							reject();
						}

					}
				});

			}).then( () => {

				this.setupHubLayout();
				this.handleFilterForm();

			}).catch( () => {

				window.WPBlocksHubHelper.showAlert( wpbhVars.loadingError, '<p>' + wpbhVars.hubLoadingErrorText + '</p>' );

			});

		}

		/**
		 * Hub Layout
		 */
		setupHubLayout() {

			// toggle filters
			$('button.handlediv').on('click', function( e ) {
				e.preventDefault();

				const $btn = $(this),
				$block = $btn.parents('.postbox');

				$block.toggleClass('closed');

			});

			// sticky blocks on settings page
			const $stickyBlocks = $( '.wpbh-stick-in-parent');

			const makeStickyBlocks = () => {
				if( $stickyBlocks.length ) {
					$stickyBlocks.stick_in_parent({
						offset_top: $('#wpadminbar').outerHeight() + 15
					});
				}
			};

			$( window ).resize( function() {
				let windowWidth = $( window ).width();
				if( windowWidth > 1600 ) {
					makeStickyBlocks();
				} else {
					$stickyBlocks.trigger( 'sticky_kit:detach');
				}
			});

			$( window ).trigger('resize');

		}

		/**
		 * Filter form
		 */
		handleFilterForm() {

			$('#save').on( 'click', function( e ) {
				e.preventDefault();

				const $btn = $(this),
				$hubHolder = $( '#wpbh-hub-blocks'),
				$loader = $('#loading-indicator'),
				$form = $('#wpbh-search-form');

				if( $btn.is( '[disabled]') ) {
					return false;
				}

				$btn.attr('disabled', 'disabled');
				$loader.addClass('active');

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : `${wpbhVars.prefix}_load_my_blocks`,
						'data' : $form.serialize(),
						'nonce' : wpbhVars.ajaxNonce
					},
					beforeSend: function() {

						$btn.val( $btn.data('txt-inprogress') )

					},
					success: function( response ) {

						$btn.removeAttr('disabled');
						$loader.removeClass('active');
						$btn.val( $btn.data('txt-default') );
						$hubHolder.html( response.data.blocksHTML );
						$(document.body).trigger('sticky_kit:recalc');

					}
				});

			});

		}
	
	}

	new WPBlocksHubMyBlocks();
	
})( window.jQuery );