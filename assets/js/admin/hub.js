'use strict';

(function($){

	class WPBlocksHub {

		constructor() {
	
			this.options = {
				'nextPage' : 2,
				'endlessLoadingPaused': false,
				'endlessLoading' : false
			}

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
						'action' : `${wpbhVars.prefix}_load_hub`,
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
							$('#wpbh-hub-status').html( answer.data.hubStatusHTML );
							$('#wpbh-hub-compatibility').html( answer.data.hubCompatibilityHTML );
							$('#wpbh-hub-categories').html( answer.data.hubCategoriesHTML );
							$('#wpbh-hub-tags').html( answer.data.hubTagsHTML );
							$('#wpbh-hub-action-btn').show();

							resolve();

						} else {
							reject();
						}

					}
				});

			}).then( () => {

				this.setupHubLayout();
				this.setupInfinitePagination();
				this.handleSearchForm();
				this.startTour();

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
		 * Search form
		 */
		handleSearchForm() {

			const self = this;

			$('#save').on( 'click', function( e ) {
				e.preventDefault();

				$('html,body').animate({ scrollTop: 0 }, 'slow');

				const $btn = $(this),
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
						'action' : `${wpbhVars.prefix}_load_blocks`,
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

						$('#wpbh-hub-blocks').html( response.data.blocksHTML );
						$(document.body).trigger('sticky_kit:recalc');

						self.options.endlessLoadingPaused = false;
						self.options.nextPage = 2;
						self.setupInfinitePagination();

					}
				});

			});

		}

		/**
		 * Infinite pagination
		 */
		setupInfinitePagination() {

			const $form = $('#wpbh-search-form'),
			$hubHolder = $( '#wpbh-hub-blocks'),
			self = this;

			function infScroll() {

				if( $('#wpbh-no-blocks').length ) {
					return false;
				}

				if( $(window).scrollTop() >= $(document).height() - $(window).height() - 50 ) {

					if( self.options.endlessLoadingPaused === true ) {
						return true;
					}

					if( self.options.endlessLoading == false ) {

						self.options.endlessLoading = true;

						$.ajax({
							url: ajaxurl,
							type: "POST",
							data: {
								'action' : `${wpbhVars.prefix}_load_blocks`,
								'data' : $form.serialize() + '&paged=' + self.options.nextPage,
								'nonce' : wpbhVars.ajaxNonce
							},
							beforeSend: function() {
								$hubHolder.addClass('loading');
							},
							success: function( response ) {
	
								$hubHolder.removeClass('loading');
	
								if( response.data.blocksHTML != '' ) {
									$hubHolder.append( response.data.blocksHTML );
									$(document.body).trigger('sticky_kit:recalc');
									self.options.nextPage++;
								} else {
									self.options.endlessLoadingPaused = true;
								}

								self.options.endlessLoading = false;
		
							}
						});	
					}

				}
			}

			$(window).off( 'scroll', infScroll ).on( 'scroll', infScroll );

		}
	
		/**
		 * A quick tour for new users
		 */
		startTour() {

			if( wpbhVars.displayTourPointers ) {

				$.ajax({
					url: ajaxurl,
					type: "POST",
					dataType: 'json',
					data: {
						'action' : `${wpbhVars.prefix}_load_tour_pointers`,
						'nonce' : wpbhVars.ajaxNonce
					},
					success: ( answer ) => {
	
						let pointers = [];
						const stepsCount = answer.data.length;

						$( answer.data ).each( function( i, item ) {

							pointers.push( $( item.element ).pointer({
								content: item.content,
								position: {
									edge: item.edge,
									align: item.align
								},
								buttons: function() {
									return $('<a href="#" class="button button-primary wpbh-tour-link" data-step="' + i + '" data-next="' + (i + 1) + '" data-max-steps="' + stepsCount + '">' + ( i + 1 == stepsCount ? wpbhVars.strFinishTour : wpbhVars.strNext) + '</a>');
								}
							}) );

						});

						pointers[0].pointer('open');

						$(document).on( 'click', '.wpbh-tour-link', function( e ) {
							e.preventDefault();

							const $btn = $(this),
							currStep = $btn.data( 'step'),
							nextStep = $btn.data( 'next'),
							maxSteps = $btn.data( 'max-steps');

							pointers[currStep].pointer('close');

							if( nextStep < maxSteps ) {
								pointers[nextStep].pointer('open');
							}

							if( nextStep == maxSteps ) {
								$.ajax({
									url: ajaxurl,
									type: "POST",
									data: {
										'action' : 'wpbh_admin_dismiss_notices',
										'notice' : 'hide_tour_pointers'
									}
								});
							}

						});

					}
				});

			}

		}

	}

	new WPBlocksHub();
	
})( window.jQuery );