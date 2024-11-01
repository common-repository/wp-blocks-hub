'use strict';

(function($){

	class WPBlocksHubBlocks {

		constructor() {
	
			this.setupBlockPreview();
			this.handleBlockControls();

		}

		/**
		 * Block preview
		 */
		setupBlockPreview() {

			$( '#wpbh-hub').on( 'mouseenter', '.wpbh-block-item', function() {

				let $video = $(this).find('video');

				if( $video.length && $video[0].readyState === 4 ) {
					$video[0].play();
				}

			}).on( 'mouseleave', '.wpbh-block-item', function() {

				let $video = $(this).find('video');

				if( $video.length && $video[0].readyState === 4 ) {
					$video[0].pause();
					$video[0].currentTime = 0;
				}

			});

		}

		/**
		 * Block controls
		 */
		handleBlockControls() {

			const self = this;

			// download / activate / deactivate actions
			$('#wpbh-hub-blocks').on( 'click', '.wpbh-block-do-action', function( e ) {
				e.preventDefault();
				let $btn = $(this),
				$block = $btn.parents('.wpbh-block-item'),
				action = $btn.attr('data-block-action'),
				blockId = $btn.attr('data-block-id'),
				blockSlug = $btn.attr('data-block-slug');

				if( $block.hasClass('premium') && (action == 'download' || action == 'activate') && wpbhVars.isPremiumActive == 'no' ) {
					window.WPBlocksHubHelper.showAlert( wpbhVars.licenseRequiredTitle, '<p>' + wpbhVars.licenseRequiredContent + '</p>' );
					return false;
				}

				self.lockBlocksInterface( $btn );

				switch( action ) {
					case 'download':						

						$.ajax({
							url: ajaxurl,
							type: "POST",
							data: {
								'action' : `${wpbhVars.prefix}_block_download`,
								'nonce' : wpbhVars.ajaxNonce,
								'blockSlug' : blockSlug,
								'blockId' : blockId
							},
							success: function( answer ) {
		
								if( answer.success === true ) {
		
									$btn.attr( 'data-block-action', answer.data.btnActionNow );
									$btn.text( answer.data.btnTextNow );

								} else {
		
									window.WPBlocksHubHelper.showAlert( answer.data.errorTitle, '<p>' + answer.data.errorText + '</p>' );
		
								} 
		
								self.unlockBlocksInterface();
								
							}
						});

					break;
					case 'activate':

						$.ajax({
							url: ajaxurl,
							type: "POST",
							data: {
								'action' : `${wpbhVars.prefix}_block_activate`,
								'nonce' : wpbhVars.ajaxNonce,
								'blockSlug' : blockSlug
							},
							success: function( answer ) {
		
								if( answer.success === true ) {
									$btn.attr( 'data-block-action', answer.data.btnActionNow );
									$btn.text( answer.data.btnTextNow );

									if( $('#wpbh-myblocks-filters').length ) {
										if( $('#wpbh-myblocks-filters input[name=filter]:checked').val() == 'inactive' ) {
											$block.fadeOut( function() {
												$block.remove();
											});
										}
									}

									$block.find('.wpbh-block-copy-shortcode').removeClass('hidden');

								} else {
									window.WPBlocksHubHelper.showAlert( answer.data.errorTitle, '<p>' + answer.data.errorText + '</p>' );
								}

								self.unlockBlocksInterface();
								
							}
						});

					break;
					case 'deactivate':

						$.ajax({
							url: ajaxurl,
							type: "POST",
							data: {
								'action' : `${wpbhVars.prefix}_block_deactivate`,
								'nonce' : wpbhVars.ajaxNonce,
								'blockSlug' : blockSlug
							},
							success: function( answer ) {
		
								if( answer.success === true ) {
									$btn.attr( 'data-block-action', answer.data.btnActionNow );
									$btn.text( answer.data.btnTextNow );

									if( $('#wpbh-myblocks-filters').length ) {
										if( $('#wpbh-myblocks-filters input[name=filter]:checked').val() == 'active' ) {
											$block.fadeOut( function() {
												$block.remove();
											});
										}
									}

									$block.find('.wpbh-block-copy-shortcode').addClass('hidden');

								} else {
									window.WPBlocksHubHelper.showAlert( answer.data.errorTitle, '<p>' + answer.data.errorText + '</p>' );
								}

								self.unlockBlocksInterface();
								
							}
						});

					break;
				}

			});

			// remove block action
			$('#wpbh-hub-blocks').on( 'click', '.wpbh-block-remove', function( e ) {
				e.preventDefault();
				let $btn = $(this),
				$block = $btn.parents('.wpbh-block-item'),
				blockSlug = $btn.attr('data-block-slug'),
				blockId = $btn.attr('data-block-id');
				self.lockBlocksInterface( $btn );

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : `${wpbhVars.prefix}_block_remove`,
						'nonce' : wpbhVars.ajaxNonce,
						'blockSlug' : blockSlug,
						'blockId' : blockId
					},
					success: function( answer ) {

						if( typeof answer == 'object' && answer.success === true ) {

							$block.fadeOut( function() {
								$block.remove();
							});

						} else {

							window.WPBlocksHubHelper.showAlert( answer.data.errorTitle, '<p>' + answer.data.errorText + '</p>' );

						} 

						self.unlockBlocksInterface();
						
					}
				});

			});

			// update block action
			$('#wpbh-hub-blocks').on( 'click', '.wpbh-block-update', function( e ) {
				e.preventDefault();
				let $btn = $(this),
				$block = $btn.parents('.wpbh-block-item'),
				blockSlug = $btn.attr('data-block-slug'),
				blockId = $btn.attr('data-block-id');
				self.lockBlocksInterface( $btn );

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : `${wpbhVars.prefix}_block_update`,
						'nonce' : wpbhVars.ajaxNonce,
						'blockSlug' : blockSlug,
						'blockId' : blockId
					},
					success: function( answer ) {

						if( typeof answer == 'object' && answer.success === true ) {

							$block.find('.wpbh-block-update').remove();

						} else {

							window.WPBlocksHubHelper.showAlert( answer.data.errorTitle, '<p>' + answer.data.errorText + '</p>' );

						} 

						self.unlockBlocksInterface();
						
					}
				});

			});

			// copy shortcode to clipboard
			$('#wpbh-hub-blocks').on( 'click', '.wpbh-block-copy-shortcode', function( e ) {
				e.preventDefault();

				let $btn = $(this),
				shortcode = $btn.data('shortcode');

				self.lockBlocksInterface( $btn, false );

				window.WPBlocksHubHelper.copyToClipboard( shortcode );
				window.WPBlocksHubHelper.showAlert( wpbhVars.done, '<p>' + wpbhVars.shortcodeCopied + '</p>' );

				self.unlockBlocksInterface();

			});

		}

		/**
		 * Lock interface
		 */
		lockBlocksInterface( $currentBtn, showLoader = true ) {

			let $btn = $currentBtn,
			$parent = $btn.parent(),
			$buttons = $('#wpbh-hub-blocks').find('.button'),
			$loader = $parent.find('.wpbh-loader');

			if( $btn.is('[disabled]') ) {
				return false;
			}

			$btn.addClass('button-primary');
			$buttons.attr('disabled', 'disabled');

			if( showLoader ) {
				$loader.removeClass('hidden');
			}

		}

		/**
		 * Lock interface
		 */
		unlockBlocksInterface() {

			let $buttons = $('#wpbh-hub-blocks .button'),
			$loader = $('#wpbh-hub-blocks .wpbh-loader');

			$buttons.removeAttr( 'disabled').removeClass('button-primary');
			$loader.addClass('hidden');

		}

	}

	window.WPBlocksHubBlocks = new WPBlocksHubBlocks();
	
})( window.jQuery );