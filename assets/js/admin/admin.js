'use strict';

(function($){

	class WPBlocksHub {

		constructor() {
	
			// go premium notice
			this.handleGoPro();

			// dismiss notices
			this.handleWarningNotices();

			// show UI pointers
			this.showPointers();
	
			// featured post toggler
			this.handleFeaturedPostToggler();

			// make sortable taxonomies
			this.handleSortableTaxonomies();

			// SVG support
			this.makeSVGThumbnails();

		}

		/**
		 * Handle GoPro btn hover
		 */
		handleGoPro() {

			const $pointer = $('#gopremium-btn').pointer({
				content: $('#wpbh-go-premium-tooltip').html(),
				pointerClass: 'wp-pointer wpbh-gopro-pointer',
				position: {
					edge: 'top',
					align: 'left'
				}
			});

			$('#gopremium-btn').hover( function() {
				$pointer.pointer('open');
			}, function() {
				$pointer.pointer('close');
			});

		}

		/**
		 * Handle warning / error notices
		 */
		handleWarningNotices() {

			$('a.wpbh-dismiss').on( 'click', function( e ) {
				e.preventDefault();

				let $link = $(this),
				$msg = $link.parents( '.error'),
				dismissNotice = $link.data('dismiss');

				$msg.remove();

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : 'wpbh_admin_dismiss_notices',
						'notice' : dismissNotice
					}
				});

			});

		}
	
		/**
		 * Display pointers in admin panel
		 */
		showPointers() {
	
			if( Boolean( wpbhVars.displayWelcomePointer ) === true ) {
	
				$('#toplevel_page_wp-blocks-hub').pointer({
					content: wpbhVars.welcomePointerStr,
					position: {
						edge: 'left',
						align: 'center'
					},
					close: function() {
		
						$.ajax({
							url: ajaxurl,
							type: "POST",
							data: {
								'action' : 'wpbh_admin_dismiss_notices',
								'notice' : 'hide_welcome_pointer'
							}
						});
		
					}
				}).pointer('open');
	
			}

			if( Boolean( wpbhVars.displayTaxPointer ) === true ) {

				$('body.taxonomy-benefit_cat, body.taxonomy-person_cat, body.taxonomy-testimonial_cat, body.taxonomy-portfolio_cat').find('#the-list').pointer({
					content: wpbhVars.dragToOrderNotice,
					position: {
						edge: 'right',
						align: 'top'
					},
					close: function() {
		
						$.ajax({
							url: ajaxurl,
							type: "POST",
							data: {
								'action' : 'wpbh_admin_dismiss_notices',
								'notice' : 'hide_tax_pointer'
							}
						});
		
					}
				}).pointer('open');

			}

		}

		/**
		 * Toggle featured post
		 */
		handleFeaturedPostToggler() {

			$('.wpbh-featured-post-toggle').on( 'click', function( e ) {
				
				e.preventDefault();

				const $link = $(this),
				classFeatured = $link.data('class-featured'),
				classNormal = $link.data('class-normal'),
				$icon = $link.find('i');

				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action' : `${wpbhVars.prefix}_toggle_featured_post`,
						'post_id' : $link.data('post-id')
					},
					success: function( is_now_featured ) {

						if( is_now_featured == 'yes' ) {
							$icon.removeClass( classNormal ).addClass( classFeatured );
						} else {
							$icon.removeClass( classFeatured ).addClass( classNormal );
						}

					}
				});

			});

		}

		/**
		 * Drang-and drop sorting for custom post types
		 */
		handleSortableTaxonomies() {

			$('body.taxonomy-portfolio_cat #the-list, body.taxonomy-portfolio_tag #the-list, body.taxonomy-testimonial_cat #the-list, body.taxonomy-benefit_cat #the-list, body.taxonomy-person_cat #the-list').sortable({
				'items': 'tr',
				'axis': 'y',
				'helper': function(e, $ui) {
					$ui.children().children().each( function() {
						$(this).width( $(this).width() );
					});
					return $ui;
				},
				'update': function(e, ui) {

					$.post( ajaxurl, {
						action: `${wpbhVars.prefix}_order_taxonomy`,
						order: $('#the-list').sortable( 'serialize'),
					});

				}
			});

		}

		/**
		 * SVG support for thumbnails
		 */
		makeSVGThumbnails() {

			$('body.post-php .attachment-post-thumbnail').css( { minHeight: '200px'});

		}

	}

	new WPBlocksHub();
	
})( window.jQuery );